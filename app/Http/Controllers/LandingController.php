<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Laboratory;
use App\Models\Setting;
use Illuminate\Http\Request;

// use App\Models\Pharmacy;
use App\Models\Representative;
use App\Models\Noticia;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactAdminMail;

class LandingController extends Controller
{
    public function index()
    {
        try {
            $banner = \App\Models\Banner::where('section', 'inicio')->first();
        } catch (\Exception $e) {
            $banner = null;
        }
        $topLaboratories = Laboratory::where('is_top', true)->get();
        $featuredProducts = Product::where('estado', true)->where('is_featured', true)->limit(4)->get();
        return view('landing.index', compact('topLaboratories', 'featuredProducts', 'banner'));
    }

    public function about()
    {
        try {
            $banner = \App\Models\Banner::where('section', 'ejecutivos')->first();
        } catch (\Exception $e) {
            $banner = null;
        }
        $representatives = Representative::where('estado', true)->with('locations.zona')->get();
        return view('landing.ejecutivos', compact('representatives', 'banner'));
    }

    public function nosotros()
    {
        try {
            $banner = \App\Models\Banner::where('section', 'nosotros')->first();
        } catch (\Exception $e) {
            $banner = null;
        }
        $settings = [
            'mision' => Setting::get('mision', 'Proveer soluciones que satisfagan las necesidades de clientes y proveedores a través de la comercialización de productos farmacéuticos y populares, garantizando calidad, eficiencia y competitividad. Además, se busca asegurar el crecimiento de la empresa, el bienestar de la comunidad y el desarrollo de los colaboradores.'),
            'vision' => Setting::get('vision', 'Droguería Sánchez Pharma será reconocida como una empresa líder en la industria farmacéutica a nivel regional, con potencial de expansión nacional e internacional, basada en principios éticos. Se enfoca en satisfacer las necesidades terapéuticas de la población, respetando a colaboradores, proveedores y clientes, y contribuyendo al país y al medio ambiente.'),
            'valores' => Setting::get('valores', 'Compromiso, Honestidad, Innovación, Servicio, Calidad'),
            'principios' => Setting::get('principios', '• Atención al cliente con excelencia\n• Distribución oportuna de medicamentos\n• Precios justos y accesibles\n• Compromiso con la salud pública\n• Ética profesional en todas nuestras acciones'),
            'historia' => Setting::get('historia', 'Droguería y Distribuidora Sánchez Pharma es una empresa chiclayana fundada en 2022, dedicada a la comercialización y distribución de productos farmacéuticos, médicos y de cuidado personal en la región Lambayeque. Fue creada con el objetivo de brindar un servicio confiable, accesible y comprometido con la salud de las familias peruanas'),
        ];
        return view('landing.nosotros', compact('settings', 'banner'));
    }

    public function noticias()
    {
        try {
            $banner = \App\Models\Banner::where('section', 'noticias')->first();
        } catch (\Exception $e) {
            $banner = null;
        }
        // Show active notices
        $noticias = Noticia::with(['laboratory', 'product'])
            ->where('estado', true)
            ->whereDate('fecha_inicial', '<=', now())
            ->whereDate('fecha_final', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('landing.noticias', compact('noticias', 'banner'));
    }

    public function products(Request $request)
    {
        try {
            $banner = \App\Models\Banner::where('section', 'productos')->first();
        } catch (\Exception $e) {
            $banner = null;
        }
        $laboratories = Laboratory::where('estado', true)->orderBy('descripcion', 'asc')->get();
        $query = Product::with('laboratory')->where('estado', true);
        
        if ($request->filled('lab')) {
            $query->where('laboratory_id', $request->lab);
        }
        
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nombre', 'like', '%' . $searchTerm . '%')
                  ->orWhere('codigo', 'like', '%' . $searchTerm . '%');
            });
        }

        $products = $query->paginate(12);

        return view('landing.productos', compact('products', 'laboratories', 'banner'));
    }

    public function productDetail(Product $product)
    {
        $product->load('laboratory');
        // Extract first word to find similar names (e.g. "Paracetamol")
        $firstWord = explode(' ', $product->nombre)[0];
        
        $relatedProducts = Product::where('nombre', 'like', $firstWord . '%')
            ->where('estado', true)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();
            
        // Fill up to 4 with same laboratory if not enough similar names
        if ($relatedProducts->count() < 4) {
            $moreProducts = Product::where('laboratory_id', $product->laboratory_id)
                ->where('estado', true)
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id')->toArray())
                ->limit(4 - $relatedProducts->count())
                ->get();
            $relatedProducts = $relatedProducts->merge($moreProducts);
        }
            
        return view('landing.product_detail', compact('product', 'relatedProducts'));
    }

    public function contact()
    {
        try {
            $banner = \App\Models\Banner::where('section', 'contacto')->first();
        } catch (\Exception $e) {
            $banner = null;
        }
        return view('landing.contacto', compact('banner'));
    }

    public function processContact(Request $request)
    {
        $validated = $request->validate([
            'empresa' => 'required|string|max:255',
            'ruc' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string'
        ]);

        try {
            $adminEmail = env('ADMIN_EMAIL', Setting::get('company_email', 'cesarAEC1234@gmail.com'));
            Mail::to($adminEmail)->send(new ContactAdminMail($validated));
        } catch (\Exception $e) {
            \Log::error("Error enviando email de contacto al admin: " . $e->getMessage());
        }

        return back()->with('success', 'Tu mensaje ha sido enviado correctamente. Nos pondremos en contacto contigo pronto.');
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('q');
        if (!$query) return response()->json([]);

        $products = Product::where('estado', true)
            ->where(function($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                  ->orWhere('codigo', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get(['id', 'nombre', 'imagen', 'precio']);

        $products->map(function($product) {
            $product->imagen_url = $product->imagen ? asset('storage/' . $product->imagen) : null;
            return $product;
        });

        return response()->json($products);
    }
}
