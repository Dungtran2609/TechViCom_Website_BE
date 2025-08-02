<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('cartItems.product', 'cartItems.productVariant')
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart) {
            return response()->json(['message' => 'Giỏ hàng trống'], 404);
        }

        return response()->json($cart);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $product = Product::findOrFail($request->product_id);
        $price = $request->product_variant_id
            ? ProductVariant::findOrFail($request->product_variant_id)->price
            : $product->price;

        $cartItem = CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'product_variant_id' => $request->product_variant_id,
            ],
            [
                'quantity' => $request->quantity,
                'price' => $price,
            ]
        );

        $cart->total = $cart->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $cart->save();

        return response()->json($cartItem, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::findOrFail($id);
        $cartItem->update(['quantity' => $request->quantity]);

        $cart = $cartItem->cart;
        $cart->total = $cart->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $cart->save();

        return response()->json($cartItem);
    }

    public function destroy($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cart = $cartItem->cart;
        $cartItem->delete();

        $cart->total = $cart->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $cart->save();

        return response()->json(null, 204);
    }
}