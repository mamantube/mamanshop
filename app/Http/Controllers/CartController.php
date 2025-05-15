<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function allCart()
    {   
        try {
            $cartItems = CartItem::with("product", "user")
                ->get();

            $totalPrice = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });
    
            return response()->json(["data" => $cartItems], 200);
    
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cart items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function cartById($id)
    {
        $item = CartItem::where("id", $id)->where("user_id", Auth::id())->with("product")->first();

        if (!$item) {
            return response()->json(["message" => "Cart tidak ditemukan"], 404);
        }

        return response()->json(["data" => $item], 200);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            "product_id" => "required|exists:products,id",
            "quantity" => "nullable|min:1|max: ". Product::find($request->product_id)->stock,
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $user = Auth::user();
                $product = Product::lockForUpdate()->find($request->product_id);
                $quantity = $request->input("quantity", 1);

                if ($user->id !== Auth::id()) {
                    throw new \Exception("Customer tidak sesuai");
                }

                if ($product->stock < $quantity) {
                    throw new \Exception("Stok tidak mencukupi");
                }



                $cartItem = CartItem::firstOrNew(
                    [
                        'user_id' => $user->id,
                        'product_id' => $product->id
                    ],
                );
                $cartItem->quantity = ($cartItem->quantity ?? 1) + $quantity;
                $cartItem->save();

                $product->decrement("stock", $quantity);

                return response()->json(["message" => "Produk berhasil ditambahkan ke cart", "data" => $cartItem-> load("product")], 200);
            });
        } catch (Exception $e) {
            return response()->json(["message" => "gagal menambahkan produk", "error" => $e->getMessage()], 500);
        }

    }

    public function removeFromCart($id)
    {
        $item = CartItem::where("id", $id)->where("user_id", Auth::id())->firstOrFail();
        $item->delete();

        return response()->json(["message" => "Produk dihapus dari cart"], 200);
    }

    public function payment($id)
    {
        try {
            $cartItem = CartItem::where("id", $id)->first();
    
            $cartItem->payment = "success";
            $cartItem->save();

            return response()->json(["message" => "Pembayaran berhasil", "data" => $cartItem], 200);
        } catch (Exception $e) {
            return response()->json(["message" => "pembayaran gagal", "error" => $e->getMessage()], 500);
        }
    }
}
