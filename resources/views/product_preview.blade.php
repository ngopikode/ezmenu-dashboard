<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} di {{ $restaurant->name }}</title>

    <!-- Open Graph Tags for Rich Social Media Previews -->
    <meta property="og:title" content="{{ $product->name }} di {{ $restaurant->name }}" />
    <meta property="og:description" content="{{ $product->description }}" />
    <meta property="og:image" content="{{ $image_url }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="{{ $restaurant->name }}" />

    <!-- Twitter Card Tags (Optional but Recommended) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $product->name }} di {{ $restaurant->name }}">
    <meta name="twitter:description" content="{{ $product->description }}">
    <meta name="twitter:image" content="{{ $image_url }}">

    <!--
      Meta Refresh to Redirect Users to the React App.
      The delay is set to 0 seconds. Bots will read the meta tags,
      while human users will be instantly redirected to the interactive React app.
    -->
    <meta http-equiv="refresh" content="0;url={{ $react_app_url }}#product-{{ $product->id }}">

</head>
<body>
    <h1>Memuat {{ $product->name }}...</h1>
    <p>Anda akan dialihkan ke menu kami. Jika tidak, silakan <a href="{{ $react_app_url }}#product-{{ $product->id }}">klik di sini</a>.</p>
</body>
</html>
