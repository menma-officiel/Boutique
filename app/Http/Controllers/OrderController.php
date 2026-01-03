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

        // Build the WhatsApp URL but do not redirect automatically. We show a confirmation page
        $url = $this->buildWhatsappUrl($order, $product, $quantity);

        return view('orders.confirm', compact('order', 'product', 'quantity', 'url'));
    }

    /**
     * Send WhatsApp: mark as sent and redirect to api.whatsapp
     */
    public function sendWhatsapp(Request $request, Order $order)
    {
        $product = $order->product;
        $quantity = $order->quantity;

        $urls = $this->buildWhatsappUrls($order, $product, $quantity);

        $order->whatsapp_sent = true;
        $order->save();

        // If AJAX/JSON expected, return URLs and let client open app/web link
        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json($urls);
        }

        // Fallback: redirect to web URL
        return redirect()->away($urls['web']);
    }

    private function buildWhatsappUrls(Order $order, Product $product, int $quantity = 1): array
    {
        $whatsapp = config('app.whatsapp_number') ?: env('WHATSAPP_NUMBER');

        $messageLines = [
            "Commande: {$product->name} x{$quantity}",
            "Order ID: {$order->id}",
            "Nom: {$order->customer_name}",
            "Téléphone client: {$order->customer_phone}",
            "Adresse: " . ($order->customer_address ?? '-'),
            "Total: " . number_format($order->total_price, 2) . ' €',
        ];

        $message = implode("\n", $messageLines);
        $encoded = rawurlencode($message);

        if ($whatsapp) {
            $adminPhone = preg_replace('/[^0-9]/', '', $whatsapp);
            $web = "https://api.whatsapp.com/send?phone={$adminPhone}&text={$encoded}";
            $app = "whatsapp://send?phone={$adminPhone}&text={$encoded}";

            return ['app' => $app, 'web' => $web];
        }

        $web = "https://wa.me/?text={$encoded}";

        return ['app' => $web, 'web' => $web];
    }
}
