@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Commentaires</h1>

    <div class="mb-4 flex items-center gap-4">
        <form method="GET" action="{{ route('admin.comments.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Recherche auteur ou contenu" class="border px-2 py-1">
            <select name="product_id" class="border px-2 py-1">
                <option value="">Tous les produits</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
            <select name="approved" class="border px-2 py-1">
                <option value="">Tous</option>
                <option value="1" {{ request('approved') === '1' ? 'selected' : '' }}>Approuvés</option>
                <option value="0" {{ request('approved') === '0' ? 'selected' : '' }}>Non approuvés</option>
            </select>
            <button class="bg-blue-600 text-white px-3 py-1">Filtrer</button>
        </form>

        <div class="ml-auto">
            <form id="bulk-form" method="POST" action="{{ route('admin.comments.bulk') }}">
                @csrf
                <input type="hidden" name="action" id="bulk-action">
                <!-- hidden ids will be appended by JS -->
                <button type="button" class="bg-green-600 text-white px-3 py-1" onclick="submitBulk('approve')">Approuver sélection</button>
                <button type="button" class="bg-yellow-600 text-white px-3 py-1" onclick="submitBulk('disapprove')">Désapprouver sélection</button>
                <button type="button" class="bg-red-600 text-white px-3 py-1" onclick="if(confirm('Supprimer la sélection ?')) submitBulk('delete')">Supprimer sélection</button>
            </form>
        </div>
    </div>

    <form id="comments-list-form">
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="border px-3 py-2"><input type="checkbox" id="select-all"></th>
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
                <td class="border px-3 py-2"><input type="checkbox" class="select-item" value="{{ $c->id }}"></td>
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
    </form>

    <div class="mt-4">
        {{ $comments->links() }}
    </div>
</div>

<script>
    function submitBulk(action) {
        const checked = Array.from(document.querySelectorAll('.select-item:checked')).map(i => i.value);
        if (checked.length === 0) {
            alert('Aucune sélection');
            return;
        }
        const form = document.getElementById('bulk-form');
        document.getElementById('bulk-action').value = action;

        // remove previous ids
        form.querySelectorAll('input[name="ids[]"]').forEach(n => n.remove());

        checked.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });

        form.submit();
    }

    document.getElementById('select-all').addEventListener('change', function(e) {
        document.querySelectorAll('.select-item').forEach(cb => cb.checked = e.target.checked);
    });
</script>
@endsection