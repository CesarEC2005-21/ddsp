<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('landing.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $id = $request->product_id;
        $quantity = $request->quantity ?? 1;
        $product = Product::findOrFail($id);

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "name" => $product->nombre,
                "quantity" => $quantity,
                "price" => $product->precio,
                "image" => $product->imagen,
                "code" => $product->codigo,
                "lab" => $product->laboratory->descripcion ?? 'General'
            ];
        }

        session()->put('cart', $cart);
        $count = count($cart);
        return response()->json([
            'success' => true, 
            'cart_count' => $count,
            'cartCount' => $count
        ]);
    }

    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Carrito actualizado');
        }
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Producto eliminado');
        }
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Carrito vaciado');
    }
}
