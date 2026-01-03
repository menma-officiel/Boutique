@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Catalogue - Menma Shop</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="border rounded p-4">
                <div class="h-48 bg-gray-100 mb-4 flex items-center justify-center">
                    @if($product->image)
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="object-contain h-full">
                    @else
                        <span class="text-gray-400">Image</span>
                    @endif
                </div>
                <h2 class="font-semibold text-lg">{{ $product->name }}</h2>
                <p class="text-gray-600">{{ \Illuminate\Support\Str::limit($product->description, 80) }}</p>
                <div class="mt-3 flex items-center justify-between">
                    <div class="text-xl font-bold">{{ number_format($product->price, 2) }} €</div>
                    <a href="{{ route('products.show', $product) }}" class="bg-blue-600 text-white px-3 py-1 rounded">Voir</a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
@endsection