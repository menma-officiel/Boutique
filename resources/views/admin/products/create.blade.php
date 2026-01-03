@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Nouveau produit</h1>

    <form method="POST" action="{{ route('admin.products.store') }}">
        @csrf
        <div class="mb-2">
            <label class="block">Nom</label>
            <input name="name" class="w-full border rounded px-2 py-1" required />
        </div>
        <div class="mb-2">
            <label class="block">Slug (optionnel)</label>
            <input name="slug" class="w-full border rounded px-2 py-1" />
        </div>
        <div class="mb-2">
            <label class="block">Prix</label>
            <input name="price" type="number" step="0.01" class="w-full border rounded px-2 py-1" required />
        </div>
        <div class="mb-2">
            <label class="block">Stock</label>
            <input name="stock" type="number" class="w-full border rounded px-2 py-1" />
        </div>
        <div class="mb-4">
            <label class="inline-flex items-center"><input type="checkbox" name="is_active" value="1" checked class="me-2"> Actif</label>
        </div>
        <div class="flex gap-3">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Créer</button>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border rounded">Annuler</a>
        </div>
    </form>
</div>
@endsection