@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Commandes</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="border px-3 py-2">ID</th>
                <th class="border px-3 py-2">Produit</th>
                <th class="border px-3 py-2">Client</th>
                <th class="border px-3 py-2">Téléphone</th>
                <th class="border px-3 py-2">Quantité</th>
                <th class="border px-3 py-2">Total</th>
                <th class="border px-3 py-2">Statut</th>
                <th class="border px-3 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $o)
            <tr>
                <td class="border px-3 py-2">{{ $o->id }}</td>
                <td class="border px-3 py-2">{{ $o->product->name ?? '—' }}</td>
                <td class="border px-3 py-2">{{ $o->customer_name }}</td>
                <td class="border px-3 py-2">{{ $o->customer_phone }}</td>
                <td class="border px-3 py-2">{{ $o->quantity }}</td>
                <td class="border px-3 py-2">{{ number_format($o->total_price,2) }} €</td>
                <td class="border px-3 py-2">{{ $o->status }}</td>
                <td class="border px-3 py-2">
                    <form method="POST" action="{{ route('admin.orders.update_status', $o) }}">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()">
                            <option {{ $o->status === 'pending' ? 'selected' : '' }}>pending</option>
                            <option {{ $o->status === 'shipped' ? 'selected' : '' }}>shipped</option>
                            <option {{ $o->status === 'delivered' ? 'selected' : '' }}>delivered</option>
                        </select>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection