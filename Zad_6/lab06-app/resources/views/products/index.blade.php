<!DOCTYPE html>

<html lang="pl">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Lista Produktów</title>

<style>

body {

font-family: Arial, sans-serif;

margin: 20px;

background-color: #f5f5f5;

}

h1 {

color: #333;

}

.product-list {

list-style: none;

padding: 0;

}

.product-item {

background: white;

padding: 15px;

margin-bottom: 10px;

border-radius: 5px;

box-shadow: 0 2px 5px rgba(0,0,0,0.1);

}

.product-name {

font-size: 18px;

font-weight: bold;

color: #333;

}

.product-price {

color: #e74c3c;

font-size: 16px;

margin-top: 5px;

}

.product-quantity {

color: #7f8c8d;

font-size: 14px;

margin-top: 5px;

}

.product-description {

color: #555;

margin-top: 5px;

font-size: 14px;

}

a {

color: #3498db;

text-decoration: none;

}

a:hover {

text-decoration: underline;

}

</style>

</head>

<body>

<h1>Lista Produktów</h1>


@if($products->count() > 0)

<ul class="product-list">

@foreach($products as $product)

<li class="product-item">

<div class="product-name">{{ $product->name }}</div>

@if($product->description)

<div class="product-description">{{ $product->description }}</div>

@endif

<div class="product-price">Cena: {{ number_format($product->price, 2) }} PLN</div>

<div class="product-quantity">Ilość: {{ $product->quantity }} szt.</div>

<a href="{{ route('products.show', $product->id) }}">Szczegóły</a>

</li>

@endforeach

</ul>

@else

<p>Brak produktów w bazie danych.</p>

@endif

</body>

</html>
