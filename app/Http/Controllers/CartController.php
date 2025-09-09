<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('produk')
            ->where('user_id', auth()->id())
            ->get();

        return view('cart.index', compact('carts'));
    }

    public function add($id)
    {
        $cart = Cart::where('user_id', Auth::id())
                    ->where('produk_id', $id)
                    ->first();

        if ($cart) {
            $cart->increment('quantity');
        } else {
            Cart::create([
                'user_id'   => Auth::id(),
                'produk_id' => $id,
                'quantity'  => 1,
            ]);
        }

        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function remove($id)
    {
        $cart = Cart::findOrFail($id);
        if ($cart->user_id == Auth::id()) {
            $cart->delete();
        }
        return redirect()->back()->with('success', 'Produk dihapus dari keranjang!');
    }

    // ✅ method beli dimasukkan ke dalam class
    public function beli($id)
    {
        $cart = Cart::with('produk')->findOrFail($id);

        \DB::table('purchases')->insert([
            'user_id'   => auth()->id(),
            'produk_id' => $cart->produk_id,
            'quantity'  => $cart->quantity,
            'total'     => $cart->produk->harga * $cart->quantity,
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);

        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Berhasil membeli produk!');
    }
}
