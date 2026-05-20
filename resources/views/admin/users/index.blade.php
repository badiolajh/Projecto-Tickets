@extends('layouts.app')
 
@section('title', 'Usuarios — Admin')
@section('page-title', 'Gestión de Usuarios')
 
@section('topbar-actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;"><path d="M12 5v14M5 12h14"/></svg>
        Nuevo usuario
    </a>
@endsection
 
@section('content')
 
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Usuarios del sistema</div>
            <div class="card-subtitle">{{ $usuarios->count() }} usuarios registrados</div>
        </div>
    </div>
 
    @if($usuarios->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">○</div>
            <h3>Sin usuarios</h3>
            <p>Crea el primer usuario del sistema.</p>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Área / Cargo</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:30px;height:30px;border-radius:50%;background:#1A1916;color:#fff;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;flex-shrink:0;">
                                    {{ strtoupper(substr($usuario->nombre, 0, 2)) }}
                                </div>
                                <span style="font-weight:500;">{{ $usuario->nombre }}</span>
                            </div>
                        </td>
                        <td style="color:var(--muted);font-size:13px;">{{ $usuario->email }}</td>
                        <td style="font-size:13px;">
                            <div>{{ $usuario->area ?? '—' }}</div>
                            @if($usuario->cargo_u)
                                <div style="font-size:11px;color:var(--muted);">{{ $usuario->cargo_u }}</div>
                            @endif
                        </td>
                        <td>
                            @if($usuario->administrador)
                                <span style="font-size:11px;font-weight:600;background:#1A1916;color:#fff;padding:3px 9px;border-radius:20px;">Admin</span>
                            @elseif($usuario->tecnico)
                                <span style="font-size:11px;font-weight:600;background:#EBF4FF;color:#185FA5;padding:3px 9px;border-radius:20px;">Técnico</span>
                            @elseif($usuario->empleado)
                                <span style="font-size:11px;font-weight:600;background:#F7F6F3;color:#6B6960;padding:3px 9px;border-radius:20px;">Empleado</span>
                            @else
                                <span style="font-size:11px;color:var(--muted);">Sin rol</span>
                            @endif
                        </td>
                        <td>
                            @if($usuario->activo)
                                <span class="badge-status badge-cerrado">Activo</span>
                            @else
                                <span class="badge-status badge-alta">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="{{ route('admin.users.edit', $usuario) }}" class="btn btn-ghost btn-sm">Editar</a>
                             @if(!$usuario->administrador)
                                    <form action="{{ route('admin.users.destroy', $usuario) }}" method="POST"
                                     onsubmit="return confirm('¿{{ $usuario->activo ? 'Desactivar' : 'Activar' }} a {{ $usuario->nombre }}?')">
                                     @csrf
                                    @method('DELETE')
                                    @if($usuario->activo)
                                    <button type="submit" class="btn btn-danger btn-sm">Desactivar</button>
                                    @else
                                    <button type="submit" class="btn btn-sm" style="background:#ECFDF5;color:#1A6B3A;border-color:#A7F3D0;">Activar</button>
                                     @endif
                                 </form>
                                @endif                        
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
 
@endsection