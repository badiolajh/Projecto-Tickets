<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión — SoporteTIC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #F7F6F3;
            --surface: #FFFFFF;
            --border: #E4E2DC;
            --border-md: #C9C7BF;
            --text: #1A1916;
            --muted: #6B6960;
            --accent: #1A1916;
            --danger: #C0392B;
            --radius: 8px;
            --radius-lg: 12px;
        }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
 
        /* Left panel */
        .login-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px;
        }
        .login-box {
            width: 100%;
            max-width: 380px;
        }
        .login-logo {
            margin-bottom: 40px;
        }
        .login-logo h1 {
            font-size: 20px;
            font-weight: 600;
            letter-spacing: -0.3px;
        }
        .login-logo p {
            font-size: 13px;
            color: var(--muted);
            margin-top: 4px;
        }
 
        .form-group { margin-bottom: 16px; }
        label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 6px;
        }
        input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border-md);
            border-radius: var(--radius);
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            background: var(--surface);
            color: var(--text);
            outline: none;
            transition: border-color 0.12s, box-shadow 0.12s;
        }
        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(26,25,22,0.06);
        }
        input.is-invalid { border-color: var(--danger); }
 
        .error-msg {
            font-size: 12px;
            color: var(--danger);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
 
        .alert-danger {
            background: #FEF2F2;
            color: var(--danger);
            border-left: 3px solid var(--danger);
            border-radius: var(--radius);
            padding: 11px 14px;
            font-size: 13px;
            margin-bottom: 20px;
        }
 
        .btn-submit {
            width: 100%;
            padding: 11px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: var(--radius);
            font-size: 14px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            margin-top: 24px;
            transition: opacity 0.12s;
        }
        .btn-submit:hover { opacity: 0.85; }
 
        /* Right decorative panel */
        .brand-panel {
            background: var(--text);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 48px;
            position: relative;
            overflow: hidden;
        }
        .brand-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(255,255,255,0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.04) 0%, transparent 50%);
        }
        .brand-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .brand-content { position: relative; }
        .brand-quote {
            font-size: 28px;
            font-weight: 300;
            line-height: 1.3;
            letter-spacing: -0.5px;
            margin-bottom: 24px;
            max-width: 400px;
        }
        .brand-quote strong { font-weight: 600; }
        .brand-stats {
            display: flex;
            gap: 32px;
        }
        .brand-stat-val {
            font-size: 24px;
            font-weight: 600;
            font-family: 'DM Mono', monospace;
        }
        .brand-stat-label {
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            margin-top: 2px;
        }
 
        @media (max-width: 768px) {
            body { grid-template-columns: 1fr; }
            .brand-panel { display: none; }
        }
    </style>
</head>
<body>
 
<div class="login-panel">
    <div class="login-box">
        <div class="login-logo">
            <h1>SoporteTIC</h1>
            <p>Sistema de gestión de tickets de soporte</p>
        </div>
 
        @if($errors->any())
            <div class="alert-danger">
                Credenciales incorrectas. Por favor verifica tu correo y contraseña.
            </div>
        @endif
 
        <form method="POST" action="{{ route('login') }}">
            @csrf
 
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="nombre@empresa.com"
                    autocomplete="email"
                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                    required
                >
                @error('email')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>
 
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                    required
                >
                @error('password')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>
 
            <button type="submit" class="btn-submit">Iniciar sesión</button>
        </form>
    </div>
</div>
 
<div class="brand-panel">
    <div class="brand-grid"></div>
    <div class="brand-content">
        <p class="brand-quote">Reporta problemas, <strong>recibe soluciones.</strong> Soporte técnico sin complicaciones.</p>
        <div class="brand-stats">
            <div>
                <div class="brand-stat-val">~4h</div>
                <div class="brand-stat-label">Tiempo promedio de respuesta</div>
            </div>
            <div>
                <div class="brand-stat-val">3</div>
                <div class="brand-stat-label">Roles de usuario</div>
            </div>
        </div>
    </div>
</div>
 
</body>
</html>