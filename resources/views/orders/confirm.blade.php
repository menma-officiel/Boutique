@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-xl">
    <h1 class="text-2xl font-bold mb-4">Confirmation de la commande</h1>

    <div class="bg-white shadow rounded p-4 mb-6">
        <p><strong>Produit :</strong> {{ $product->name }}</p>
        <p><strong>Quantité :</strong> {{ $quantity }}</p>
        <p><strong>Nom :</strong> {{ $order->customer_name }}</p>
        <p><strong>Téléphone :</strong> {{ $order->customer_phone }}</p>
        <p><strong>Adresse :</strong> {{ $order->customer_address ?? '-' }}</p>
        <p class="mt-2"><strong>Total :</strong> {{ number_format($order->total_price,2) }} €</p>
        <p class="text-sm text-gray-500 mt-2">En cliquant sur « Ouvrir WhatsApp », vous ouvrirez WhatsApp avec un message pré-rempli contenant les détails de la commande. Le message sera envoyé depuis votre application WhatsApp.</p>
    </div>

    <form method="POST" action="{{ route('orders.send_whatsapp', $order) }}">
        @csrf
        <div class="flex gap-3">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Ouvrir WhatsApp</button>
            <a href="{{ route('products.show', $product) }}" class="px-4 py-2 border rounded text-gray-700">Retour au produit</a>
        </div>
    </form>

    <div class="mt-4 text-sm text-gray-500">
        Vous pouvez aussi <a target="_blank" rel="noopener" href="{{ $url }}" class="text-blue-600 underline">ouvrir WhatsApp dans un nouvel onglet</a>.
    </div>
</div>
@endsection