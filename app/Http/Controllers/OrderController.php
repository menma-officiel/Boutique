<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:50',
            'customer_address' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $quantity = $data['quantity'] ?? 1;
        $total = $product->price * $quantity;

        $order = Order::create([
            'product_id' => $product->id,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_address' => $data['customer_address'] ?? null,
            'quantity' => $quantity,
            'total_price' => $total,
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);

        $whatsapp = config('app.whatsapp_number') ?: env('WHATSAPP_NUMBER');

        $message = sprintf("Commande pour %s - %s x%d\nNom: %s\nTéléphone: %s\nAdresse: %s\nTotal: %s €",
            $product->name,
            $product->name,
            $quantity,
            $order->customer_name,
            $order->customer_phone,
            $order->customer_address ?? '-',
            number_format($order->total_price, 2)
        );

        $encoded = rawurlencode($message);

        if ($whatsapp) {
            $url = "https://wa.me/" . preg_replace('/[^0-9]/', '', $whatsapp) . "?text={$encoded}";
        } else {
            // fallback: open generic wa.me with message
            $url = "https://wa.me/?text={$encoded}";
        }

        // Mark whatsapp_sent true if we will rely on user to click link
        $order->whatsapp_sent = true;
        $order->save();

        return redirect()->away($url);
    }
}
