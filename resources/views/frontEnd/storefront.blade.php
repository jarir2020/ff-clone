<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#179d55">
    <meta name="description" content="Natural and organic food at the best price from Falaq Food.">
    <title>Natural and Organic Food at the Best Price | Falaq Food</title>
    @vite(['resources/css/storefront.css', 'resources/js/storefront/app.js'])
</head>
<body>
    <div id="falaq-storefront"></div>
</body>
</html>
