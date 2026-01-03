@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">{{ $product->name }}</h1>
        <div class="mb-4">
            <div class="h-64 bg-gray-100 flex items-center justify-center">
                @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="object-contain h-full">
                @else
                    <span class="text-gray-400">Image</span>
                @endif
            </div>
        </div>
        <p class="mb-4">{{ $product->description }}</p>
        <div class="mb-6">Prix : <strong>{{ number_format($product->price,2) }} €</strong></div>

        <form id="product-order-form" method="POST" action="{{ route('orders.store') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="mb-2">
                <label class="block text-sm font-medium">Nom</label>
                <input required name="customer_name" class="w-full border rounded px-2 py-1" autocomplete="name" />
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium">Téléphone</label>
                <input required name="customer_phone" type="tel" inputmode="tel" pattern="[0-9+\s\-()]*" class="w-full border rounded px-2 py-1" autocomplete="tel" />
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium">Adresse</label>
                <textarea name="customer_address" class="w-full border rounded px-2 py-1"></textarea>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium">Quantité</label>
                <input type="number" name="quantity" value="1" min="1" class="w-24 border rounded px-2 py-1" />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Notes</label>
                <textarea name="notes" class="w-full border rounded px-2 py-1"></textarea>
            </div>

            <div class="flex items-center gap-3">
                <button id="submit-order-btn" type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Commander maintenant (WhatsApp)</button>
                <a href="{{ route('products.index') }}" class="text-gray-600">Retour</a>
            </div>
        </form>

        <!-- Sticky CTA for mobile -->
        <div class="sm:hidden fixed bottom-4 left-0 right-0 flex justify-center pointer-events-auto">
            <button id="sticky-order-btn" class="bg-green-600 text-white px-6 py-3 rounded-full shadow-lg">Commander via WhatsApp</button>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function(){
            const form = document.getElementById('product-order-form');
            const sticky = document.getElementById('sticky-order-btn');

            if (sticky) {
                sticky.addEventListener('click', function(){
                    document.getElementById('product-order-form').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // on small screens, focus phone
                    setTimeout(()=>{ const phone = document.querySelector('input[name="customer_phone"]'); if (phone) phone.focus(); }, 600);
                });
            }

            // Keep default behaviour: submit goes to confirmation page
        });
        </script>
    </div>
</div>
@endsection