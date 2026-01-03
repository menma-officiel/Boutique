@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Produits</h1>
        <a href="{{ route('admin.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Nouveau produit</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="border px-3 py-2">ID</th>
                <th class="border px-3 py-2">Nom</th>
                <th class="border px-3 py-2">Prix</th>
                <th class="border px-3 py-2">Stock</th>
                <th class="border px-3 py-2">Actif</th>
                <th class="border px-3 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            <tr>
                <td class="border px-3 py-2">{{ $p->id }}</td>
                <td class="border px-3 py-2">{{ $p->name }}</td>
                <td class="border px-3 py-2">{{ number_format($p->price,2) }} €</td>
                <td class="border px-3 py-2">{{ $p->stock }}</td>
                <td class="border px-3 py-2">{{ $p->is_active ? 'Oui' : 'Non' }}</td>
                <td class="border px-3 py-2">
                    <a class="text-blue-600" href="{{ route('admin.products.edit', $p) }}">{{ __('Edit') }}</a>
                    <form class="inline" method="POST" action="{{ route('admin.products.destroy', $p) }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 ms-3" onclick="return confirm('Supprimer ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection