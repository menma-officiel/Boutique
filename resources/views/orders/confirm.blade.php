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

    <form id="order-form" method="POST" action="{{ route('orders.send_whatsapp', $order) }}">
        @csrf
        <div class="flex gap-3">
            <button id="open-whatsapp" type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Ouvrir WhatsApp</button>
            <a href="{{ route('products.show', $product) }}" class="px-4 py-2 border rounded text-gray-700">Retour au produit</a>
        </div>
    </form>

    <div class="mt-4 text-sm text-gray-500">
        Vous pouvez aussi <a id="open-whatsapp-link" target="_blank" rel="noopener" href="{{ $url }}" class="text-blue-600 underline">ouvrir WhatsApp dans un nouvel onglet</a>.
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('order-form');
        const openLink = document.getElementById('open-whatsapp-link');

        if (!form) return;

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const url = form.action;
            const token = document.querySelector('input[name="_token"]').value;

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });

                const data = await res.json();
                const appUrl = data.app;
                const webUrl = data.web;

                // Try to open native app first
                if (appUrl) window.location = appUrl;

                // Fallback to web after short delay
                setTimeout(function(){ window.location = webUrl; }, 800);

            } catch (err) {
                // On error, fallback to web link
                window.open(openLink.href, '_blank');
            }
        });
    });
    </script>
</div>
@endsection