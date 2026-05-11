@extends('layouts.app')

@section('content')
<h1 class="text-xl font-bold mb-4">Keranjang</h1>

@foreach($cart as $item)
<div class="bg-white p-3 mb-2 rounded shadow">
{{ $item['name'] }} - Rp {{ $item['price'] }}
</div>
@endforeach

@endsection
