
<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 
  <title>@yield('title','My App')</title>
 
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-…"
    crossorigin="anonymous"
  >
 @push('head')

<link rel="stylesheet" href="{{ asset('home.css') }}">
@endpush
  @stack('head') 
</head>
<body>
  @include('partials.navbar')

  <div class="container mt-4">
    @yield('content')
  </div>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-…"
    crossorigin="anonymous"
  ></script>
 
</body>
</html>