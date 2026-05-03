<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Laboratory;
use Illuminate\Http\Request;

use App\Models\Pharmacy;
use App\Models\Representative;

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
        $pharmacies = Pharmacy::where('estado', true)->get();
        $representatives = Representative::where('estado', true)->with('zona')->get();
        return view('landing.nosotros', compact('pharmacies', 'representatives'));
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
}
