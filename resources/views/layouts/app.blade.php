<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SoporteTIC') — Sistema de Tickets</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
 
        :root {
            --bg:        #F7F6F3;
            --surface:   #FFFFFF;
            --border:    #E4E2DC;
            --border-md: #C9C7BF;
            --text:      #1A1916;
            --muted:     #6B6960;
            --accent:    #1A1916;
            --accent-bg: #1A1916;
            --accent-fg: #FFFFFF;
            --danger:    #C0392B;
            --warning:   #B45309;
            --success:   #1A6B3A;
            --info:      #185FA5;
            --radius:    8px;
            --radius-lg: 12px;
            --sidebar-w: 240px;
            --topbar-h:  56px;
        }
 
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
        }
 
        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
        }
        .sidebar-logo {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-logo span {
            font-size: 15px;
            font-weight: 600;
            letter-spacing: -0.3px;
        }
        .sidebar-logo small {
            display: block;
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px;
            font-family: 'DM Mono', monospace;
        }
 
        .sidebar-nav {
            flex: 1;
            padding: 12px 10px;
            overflow-y: auto;
        }
        .nav-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            padding: 8px 10px 4px;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: var(--radius);
            color: var(--muted);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 400;
            transition: all 0.12s;
            margin-bottom: 1px;
        }
        .nav-link:hover { background: var(--bg); color: var(--text); }
        .nav-link.active { background: var(--accent-bg); color: var(--accent-fg); }
        .nav-link svg { width: 16px; height: 16px; flex-shrink: 0; }
        .nav-link .badge {
            margin-left: auto;
            font-size: 11px;
            background: #E8E6DF;
            color: var(--muted);
            border-radius: 20px;
            padding: 1px 7px;
            font-family: 'DM Mono', monospace;
        }
        .nav-link.active .badge { background: rgba(255,255,255,0.2); color: rgba(255,255,255,0.8); }
 
        .sidebar-footer {
            padding: 12px 10px;
            border-top: 1px solid var(--border);
        }
        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: var(--radius);
        }
        .avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: var(--accent-bg);
            color: var(--accent-fg);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px;
            font-weight: 600;
            flex-shrink: 0;
        }
        .user-info { flex: 1; overflow: hidden; }
        .user-name { font-size: 13px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 11px; color: var(--muted); }
        .btn-logout {
            display: flex; align-items: center; gap: 8px;
            padding: 7px 10px;
            border-radius: var(--radius);
            color: var(--muted);
            text-decoration: none;
            font-size: 13px;
            margin-top: 2px;
            transition: all 0.12s;
            border: none; background: none; cursor: pointer; width: 100%;
        }
        .btn-logout:hover { background: var(--bg); color: var(--danger); }
 
        /* ── Main ── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar {
            height: var(--topbar-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-title {
            font-size: 14px;
            font-weight: 500;
            flex: 1;
        }
        .topbar-actions { display: flex; align-items: center; gap: 8px; }
 
        .page-content { padding: 28px; flex: 1; }
 
        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 7px 14px;
            border-radius: var(--radius);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.12s;
            text-decoration: none;
            font-family: 'DM Sans', sans-serif;
        }
        .btn-primary { background: var(--accent-bg); color: var(--accent-fg); border-color: var(--accent-bg); }
        .btn-primary:hover { opacity: 0.85; }
        .btn-outline { background: transparent; color: var(--text); border-color: var(--border-md); }
        .btn-outline:hover { background: var(--bg); }
        .btn-ghost { background: transparent; color: var(--muted); border-color: transparent; }
        .btn-ghost:hover { background: var(--bg); color: var(--text); }
        .btn-danger { background: transparent; color: var(--danger); border-color: #F5C6C3; }
        .btn-danger:hover { background: #FEF2F2; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn svg { width: 14px; height: 14px; }
 
        /* ── Cards ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px;
        }
        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 16px;
        }
        .card-title { font-size: 14px; font-weight: 600; }
        .card-subtitle { font-size: 12px; color: var(--muted); margin-top: 2px; }
 
        /* ── Stats ── */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px; margin-bottom: 24px; }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 16px 20px;
        }
        .stat-label { font-size: 11px; font-weight: 500; color: var(--muted); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 6px; }
        .stat-value { font-size: 26px; font-weight: 600; letter-spacing: -0.5px; font-family: 'DM Mono', monospace; }
        .stat-delta { font-size: 11px; color: var(--muted); margin-top: 4px; }
 
        /* ── Badges ── */
        .badge-status {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 11px; font-weight: 500;
            padding: 3px 9px;
            border-radius: 20px;
        }
        .badge-status::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
        .badge-abierto    { background: #EBF4FF; color: #185FA5; }
        .badge-en_proceso { background: #FEF3E2; color: #B45309; }
        .badge-cerrado    { background: #ECFDF5; color: #1A6B3A; }
        .badge-alta  { background: #FEF2F2; color: #C0392B; }
        .badge-normal{ background: #F7F6F3; color: #6B6960; }
        .badge-baja  { background: #ECFDF5; color: #1A6B3A; }
 
        /* ── Table ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        th { font-size: 11px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.06em; padding: 10px 14px; border-bottom: 1px solid var(--border); text-align: left; background: var(--bg); }
        td { padding: 12px 14px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--bg); }
 
        /* ── Forms ── */
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 12px; font-weight: 500; color: var(--muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em; }
        input[type=text], input[type=email], input[type=password], select, textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid var(--border-md);
            border-radius: var(--radius);
            font-size: 13.5px;
            font-family: 'DM Sans', sans-serif;
            background: var(--surface);
            color: var(--text);
            outline: none;
            transition: border-color 0.12s;
        }
        input:focus, select:focus, textarea:focus { border-color: var(--accent); }
        textarea { resize: vertical; min-height: 90px; }
 
        /* ── Alert ── */
        .alert { padding: 12px 16px; border-radius: var(--radius); font-size: 13px; margin-bottom: 16px; }
        .alert-success { background: #ECFDF5; color: #1A6B3A; border-left: 3px solid #1A6B3A; }
        .alert-danger  { background: #FEF2F2; color: #C0392B; border-left: 3px solid #C0392B; }
        .alert-info    { background: #EBF4FF; color: #185FA5; border-left: 3px solid #185FA5; }
 
        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--muted);
        }
        .empty-icon { font-size: 32px; margin-bottom: 12px; opacity: 0.4; }
        .empty-state h3 { font-size: 14px; font-weight: 500; color: var(--text); margin-bottom: 6px; }
        .empty-state p { font-size: 13px; }
    </style>
    @stack('styles')
</head>
<body>
 
{{-- Sidebar --}}
<aside class="sidebar">
    <div class="sidebar-logo">
        <span>SoporteTIC</span>
        <small>v1.0 · Sistema de Tickets</small>
    </div>
 
    <nav class="sidebar-nav">
        @auth
            @if(auth()->user()->administrador)
                <div class="nav-label">Administrador</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.tickets') }}" class="nav-link {{ request()->routeIs('admin.tickets*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Todos los Tickets
                    @if(isset($ticketsAbiertos))
                        <span class="badge">{{ $ticketsAbiertos }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                    Usuarios
                </a>
 
            @elseif(auth()->user()->tecnico)
                <div class="nav-label">Técnico</div>
                <a href="{{ route('tecnico.dashboard') }}" class="nav-link {{ request()->routeIs('tecnico.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('tecnico.tickets') }}" class="nav-link {{ request()->routeIs('tecnico.tickets*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Mis Tickets
                </a>
 
            @else
                <div class="nav-label">Empleado</div>
                <a href="{{ route('empleado.dashboard') }}" class="nav-link {{ request()->routeIs('empleado.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('tickets.create') }}" class="nav-link {{ request()->routeIs('tickets.create') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>
                    Nuevo Ticket
                </a>
                <a href="{{ route('empleado.tickets') }}" class="nav-link {{ request()->routeIs('empleado.tickets*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Mis Tickets
                </a>
            @endif
        @endauth
    </nav>
 
    <div class="sidebar-footer">
        @auth
        <div class="user-card">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->nombre, 0, 2)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->nombre }}</div>
                <div class="user-role">
                    @if(auth()->user()->administrador) Administrador
                    @elseif(auth()->user()->tecnico) Técnico
                    @else Empleado
                    @endif
                </div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                Cerrar sesión
            </button>
        </form>
        @endauth
    </div>
</aside>
 
{{-- Main content --}}
<div class="main-wrap">
    <header class="topbar">
        <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        <div class="topbar-actions">@yield('topbar-actions')</div>
    </header>
 
    <main class="page-content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
 
        @yield('content')
    </main>
</div>
 
@stack('scripts')
</body>
</html>
 