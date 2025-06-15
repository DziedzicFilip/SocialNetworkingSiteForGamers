
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to ArcadeUnion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #23272f 0%, #1a1d23 100%);
            color: #fff;
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .arcade-title {
            font-size: 2.5rem;
            font-weight: bold;
            letter-spacing: 2px;
            color: #4f8cff;
            margin-bottom: 1rem;
        }
        .arcade-card {
            background: #23272f;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.25);
            padding: 2.5rem 2rem;
            max-width: 420px;
            margin: 60px auto;
            text-align: center;
        }
        .arcade-btn {
            margin: 0 8px;
            min-width: 120px;
        }
        .arcade-logo {
            width: 80px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    @if (Auth::check())
        <script>window.location = "{{ route('home') }}";</script>
    @endif

    <div class="arcade-card">
        <img src="{{ asset('IMG/arcade-logo.png') }}" alt="ArcadeUnion Logo" class="arcade-logo">
        <div class="arcade-title">Welcome to ArcadeUnion</div>
        <p class="mb-4">The social network for gamers.<br>
        Connect, create teams, join tournaments and share your passion for gaming!</p>
        <div>
            <a href="{{ route('login') }}" class="btn btn-primary arcade-btn">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-light arcade-btn">Register</a>
        </div>
    </div>
</body>
</html>