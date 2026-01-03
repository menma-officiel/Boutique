@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Commentaires</h1>

    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="border px-3 py-2">ID</th>
                <th class="border px-3 py-2">Produit</th>
                <th class="border px-3 py-2">Auteur</th>
                <th class="border px-3 py-2">Note</th>
                <th class="border px-3 py-2">Contenu</th>
                <th class="border px-3 py-2">Approuvé</th>
                <th class="border px-3 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $c)
            <tr>
                <td class="border px-3 py-2">{{ $c->id }}</td>
                <td class="border px-3 py-2">{{ $c->product->name ?? '—' }}</td>
                <td class="border px-3 py-2">{{ $c->author_name }}</td>
                <td class="border px-3 py-2">{{ $c->rating }}</td>
                <td class="border px-3 py-2">{{ $c->content }}</td>
                <td class="border px-3 py-2">{{ $c->approved ? 'Oui' : 'Non' }}</td>
                <td class="border px-3 py-2">
                    <form method="POST" action="{{ route('admin.comments.update', $c) }}" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="approved" value="{{ $c->approved ? 0 : 1 }}">
                        <button class="text-blue-600">{{ $c->approved ? 'Désapprouver' : 'Approuver' }}</button>
                    </form>
                    <form method="POST" action="{{ route('admin.comments.destroy', $c) }}" class="inline ms-3">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600" onclick="return confirm('Supprimer ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $comments->links() }}
    </div>
</div>
@endsection