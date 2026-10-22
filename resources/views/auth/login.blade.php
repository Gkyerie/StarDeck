<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — StarDeck</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #1b2a4a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 0.75rem;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.15);
        }
        .login-brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-brand h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1b2a4a;
            margin-bottom: 0.25rem;
        }
        .login-brand p {
            color: #64748b;
            font-size: 0.85rem;
        }
        .login-brand .icon {
            font-size: 2.5rem;
            color: #14b8a6;
        }
        .btn-login {
            background: #14b8a6;
            border-color: #14b8a6;
            color: #fff;
            font-weight: 600;
        }
        .btn-login:hover {
            background: #0d9488;
            border-color: #0d9488;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-brand">
            <div class="icon"><i class="bi bi-stars"></i></div>
            <h1>StarDeck</h1>
            <p>Sistem Pengelolaan Peminjaman<br>Board Game & Console</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2">
                @foreach($errors->all() as $error)
                    <div class="small">{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required autofocus placeholder="email@stardeck.local">
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                       required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-login w-100">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
            </button>
        </form>
    </div>
</body>
</html>
