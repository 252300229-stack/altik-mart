<form action="/products/{{ $product->id }}" method="POST">
@csrf
@method('PUT')
<input type="text" name="name" value="{{ $product->name }}">
<button type="submit">Update</button>
</form>
