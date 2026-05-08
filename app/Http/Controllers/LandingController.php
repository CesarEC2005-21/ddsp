<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Laboratory;
use App\Models\Setting;
use Illuminate\Http\Request;

// use App\Models\Pharmacy;
use App\Models\Representative;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactAdminMail;

class LandingController extends Controller
{
    public function index()
    {
        $topLaboratories = Laboratory::where('is_top', true)->get();
        $featuredProducts = Product::where('estado', true)->where('is_featured', true)->limit(4)->get();
        return view('landing.index', compact('topLaboratories', 'featuredProducts'));
    }

    public function about()
    {
        $representatives = Representative::where('estado', true)->with('locations.zona')->get();
        return view('landing.ejecutivos', compact('representatives'));
    }

    public function nosotros()
    {
        $settings = [
            'mision' => Setting::get('mision', 'Brindar acceso a medicamentos de calidad a través de una red de distribución eficiente, garantizando la salud y bienestar de todos los peruanos.'),
            'vision' => Setting::get('vision', 'Ser la empresa líder en distribución farmacéutica en el Perú, reconocida por su compromiso con la salud y la innovación en el sector.'),
            'valores' => Setting::get('valores', 'Compromiso, Honestidad, Innovación, Servicio, Calidad'),
            'principios' => Setting::get('principios', '• Atención al cliente con excelencia\n• Distribución oportuna de medicamentos\n• Precios justos y accesibles\n• Compromiso con la salud pública\n• Ética profesional en todas nuestras acciones'),
            'historia' => Setting::get('historia', 'Sanchez Pharma E.I.R.L. nació con la misión de democratizar el acceso a medicamentos de calidad en el Perú. Con años de experiencia en el sector farmacéutico, hemos construido una red de distribución que llegaa cada rincón del país, partnering con laboratorios reconocidos y un equipo de ejecutivos comprometidos con la salud de los peruanos.'),
        ];
        return view('landing.nosotros', compact('settings'));
    }

    public function products(Request $request)
    {
        $laboratories = Laboratory::all();
        $query = Product::where('estado', true);
        
        if ($request->has('lab') && $request->lab != '') {
            $query->where('laboratory_id', $request->lab);
        }
        
        if ($request->has('search') && $request->search != '') {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);

        return view('landing.productos', compact('products', 'laboratories'));
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
        return view('landing.contacto');
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
