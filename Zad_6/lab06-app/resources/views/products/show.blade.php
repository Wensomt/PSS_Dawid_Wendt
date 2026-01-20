<!DOCTYPE html>

<html lang="pl">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>{{ $product->name }}</title>

<style>

body {

font-family: Arial, sans-serif;

margin: 20px;

background-color: #f5f5f5;

}

.container {

max-width: 600px;

background: white;

padding: 20px;

border-radius: 5px;

box-shadow: 0 2px 10px rgba(0,0,0,0.1);

}

h1 {

color: #333;

}

.field {

margin: 15px 0;

border-bottom: 1px solid #eee;

padding-bottom: 10px;

}

.label {

color: #7f8c8d;

font-size: 12px;

text-transform: uppercase;

}

.value {

color: #333;

font-size: 16px;

margin-top: 5px;

}

.back-link {

color: #3498db;

text-decoration: none;

}

.back-link:hover {

text-decoration: underline;

}

</style>

</head>

<body>

<div class="container">

<a href="{{ route('products.index') }}" class="back-link">← Powrót do listy</a>


<h1>{{ $product->name }}</h1>


@if($product->description)

<div class="field">

<div class="label">Opis</div>

<div class="value">{{ $product->description }}</div>

</div>

@endif


<div class="field">

<div class="label">Cena</div>

<div class="value">{{ number_format($product->price, 2) }} PLN</div>

</div>


<div class="field">

<div class="label">Ilość dostępna</div>

<div class="value">{{ $product->quantity }} szt.</div>

</div>


<div class="field">

<div class="label">Data dodania</div>

<div class="value">{{$product->created_at->format('d.m.Y H:i')}}</div>

</div>

</div>

</body>

</html>