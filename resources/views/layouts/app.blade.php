<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fishing Buddy</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Fonts --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Source+Sans+3:wght@400;600&display=swap">

    {{-- Bootstrap CSS --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        :root {
            --ink: #0f1417;
            --sea: #1b3b4c;
            --foam: #e6f2f1;
            --sun: #f2b654;
            --sand: #f7f3ec;
        }
        body {
            background:
                radial-gradient(1000px 600px at 10% -10%, #2a4a5a 0%, rgba(42,74,90,0) 60%),
                radial-gradient(800px 500px at 100% 0%, #223c4a 0%, rgba(34,60,74,0) 55%),
                var(--ink);
            color: var(--foam);
            font-family: "Source Sans 3", sans-serif;
        }
        h1, h2, h3, h4, h5, .navbar-brand {
            font-family: "Playfair Display", serif;
            letter-spacing: 0.5px;
        }
        .navbar {
            background: linear-gradient(90deg, #10171b 0%, #152027 60%, #1a2a33 100%) !important;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .navbar-brand {
            color: var(--sun) !important;
        }
        .card {
            background: #141c21;
            border: 1px solid rgba(255,255,255,0.06);
            color: var(--foam);
        }
        .list-group-item {
            background: #141c21;
            color: var(--foam);
            border-color: rgba(255,255,255,0.06);
        }
        .btn-primary {
            background-color: var(--sun);
            border-color: var(--sun);
            color: #1a1409;
        }
        .btn-outline-primary {
            border-color: var(--sun);
            color: var(--sun);
        }
        .btn-outline-primary:hover {
            background-color: var(--sun);
            color: #1a1409;
        }
        .form-control, .form-select {
            background: #0f1519;
            border-color: rgba(255,255,255,0.12);
            color: var(--foam);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--sun);
            box-shadow: 0 0 0 0.2rem rgba(242,182,84,0.2);
        }
        a, a:hover {
            color: var(--sun);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Fishing Buddy</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('spots.index') }}">Fishing Spots</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('catches.index') }}">Catches</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item">
                        <span class="navbar-text me-2">
                            {{ auth()->user()->name }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.show', auth()->user()) }}">Profile</a>
                    </li>

                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-outline-light">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    {{-- Flash + validation alerts --}}
    @include('partials._alerts')

    {{-- Page content --}}
    @yield('content')
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>
