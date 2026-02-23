<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EuroTrip Planner')</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --ink: #1a1a2e; --paper: #f8f5f0; --cream: #ede8e1; --gold: #c9a84c; --gold-light: #f0d98a; --accent: #2d6a4f; --muted: #7a7060; --danger: #c1440e; }
        body { font-family: 'DM Sans', sans-serif; background: var(--ink); color: white; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1rem; }
        body::before {
            content: ''; position: fixed; inset: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(201,168,76,0.08) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(45,106,79,0.1) 0%, transparent 50%);
        }
        .auth-wrap { width: 100%; max-width: 420px; position: relative; z-index: 1; }
        .auth-brand { text-align: center; margin-bottom: 2rem; }
        .auth-brand h1 { font-family: 'Playfair Display', serif; font-size: 2.5rem; color: var(--gold); }
        .auth-brand p { color: #888; font-size: 0.875rem; margin-top: 0.25rem; }
        .auth-card { background: white; border-radius: 16px; padding: 2rem; color: var(--ink); }
        .auth-card h2 { font-family: 'Playfair Display', serif; font-size: 1.5rem; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.125rem; }
        .form-label { display: block; font-size: 0.8rem; font-weight: 500; margin-bottom: 0.35rem; color: var(--muted); letter-spacing: 0.04em; text-transform: uppercase; }
        .form-control { width: 100%; padding: 0.625rem 0.875rem; border: 1.5px solid var(--cream); border-radius: 8px; font-family: inherit; font-size: 0.9rem; color: var(--ink); outline: none; transition: border-color 0.2s; }
        .form-control:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(201,168,76,0.12); }
        .btn-primary { width: 100%; padding: 0.75rem; background: var(--ink); color: white; border: none; border-radius: 8px; font-family: inherit; font-size: 0.95rem; font-weight: 500; cursor: pointer; margin-top: 0.5rem; transition: all 0.2s; }
        .btn-primary:hover { background: #2d2d4e; }
        .auth-footer { text-align: center; margin-top: 1.25rem; font-size: 0.875rem; color: var(--muted); }
        .auth-footer a { color: var(--gold); font-weight: 500; }
        .alert { padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.875rem; background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    </style>
</head>
<body>
    <div class="auth-wrap">
        <div class="auth-brand">
            <h1>EuroTrip ✈</h1>
            <p>Plan your perfect European adventure</p>
        </div>
        @if($errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif
        @yield('content')
    </div>
</body>
</html>
