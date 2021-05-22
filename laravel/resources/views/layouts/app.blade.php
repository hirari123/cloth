<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>@yierd('title')|{{ config('app.name', 'Laravel') }}</title>
  <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
</head>
<body>
  <nav class="navbar navbar-light bg-light">
    <div class="container">
      <a href="/" class="navbar-brand">{{ config('app.name', 'Laravel' )}}</a>
      <a href="#" class="fas fa-shopping-cart"></a>
    </div>
  </nav>
  @yield('content')
</body>
</html>