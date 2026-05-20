@extends('layouts.app')

@section('title', 'Editar Usuario — Admin')
@section('page-title', 'Editar usuario')

@section('topbar-actions')
    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">← Volver</a>
@endsection

@section('content')

<div style="max-width: 560px;">

    {{-- Info actual del usuario --}}
    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px; padding: 16px 20px; background: #fff; border: 1px solid var(--border); border-radius: var(--radius-lg);">
        <div style="width: 42px; height: 42px; border-radius: 50%; background: #1A1916; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 600; flex-shrink: 0;">
            {{ strtoupper(substr($user->nombre, 0, 2)) }}
        </div>
        <div>
            <div style="font-weight: 600; font-size: 15px;">{{ $user->nombre }}</div>
            <div style="font-size: 12px; color: var(--muted);">{{ $user->email }}</div>
        </div>
        <div style="margin-left: auto; display: flex; gap: 8px; align-items: center;">
            @if($user->administrador)
                <span style="font-size:11px;font-weight:600;background:#1A1916;color:#fff;padding:3px 9px;border-radius:20px;">Admin</span>
            @elseif($user->tecnico)
                <span style="font-size:11px;font-weight:600;background:#EBF4FF;color:#185FA5;padding:3px 9px;border-radius:20px;">Técnico</span>
            @elseif($user->empleado)
                <span style="font-size:11px;font-weight:600;background:#F7F6F3;color:#6B6960;padding:3px 9px;border-radius:20px;">Empleado</span>
            @endif
            @if($user->activo)
                <span class="badge-status badge-cerrado">Activo</span>
            @else
                <span class="badge-status badge-alta">Inactivo</span>
            @endif
        </div>
    </div>

    <div class="card">
        {{-- 
            IMPORTANTE: usa route('admin.users.update', $user) con PATCH
            Esto actualiza el usuario existente, NO crea uno nuevo.
            El ID del usuario va en la URL: /admin/users/{id}
        --}}
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label>Nombre completo <span style="color:var(--danger);">*</span></label>
                <input type="text" name="nombre"
                    value="{{ old('nombre', $user->nombre) }}"
                    required maxlength="100"
                    class="{{ $errors->has('nombre') ? 'is-invalid' : '' }}">
                @error('nombre') <div style="font-size:12px;color:var(--danger);margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Correo electrónico <span style="color:var(--danger);">*</span></label>
                <input type="email" name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                @error('email') <div style="font-size:12px;color:var(--danger);margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>
                    Nueva contraseña
                    <span style="font-weight:400; text-transform:none; letter-spacing:0; color:#C9C7BF;">(dejar vacío para no cambiar)</span>
                </label>
                <input type="password" name="password" placeholder="Mínimo 6 caracteres"
                    class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                @error('password') <div style="font-size:12px;color:var(--danger);margin-top:4px;">{{ $message }}</div> @enderror
                {{-- No se muestra la contraseña actual por seguridad — es un hash --}}
                <div style="font-size:11px;color:var(--muted);margin-top:4px;">
                    Por seguridad la contraseña no se muestra. Si no la cambias, se mantiene la actual.
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div class="form-group">
                    <label>Área</label>
                    <input type="text" name="area"
                        value="{{ old('area', $user->area) }}"
                        placeholder="Ej. Sistemas, TI, Contabilidad">
                </div>
                <div class="form-group">
                    <label>Cargo</label>
                    <input type="text" name="cargo_u"
                        value="{{ old('cargo_u', $user->cargo_u) }}"
                        placeholder="Ej. Gerente, Técnico" maxlength="25">
                </div>
            </div>

            <div class="form-group">
                <label>Estado de la cuenta</label>
                <select name="activo">
                    <option value="1" {{ (old('activo', $user->activo ? '1' : '0') == '1') ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ (old('activo', $user->activo ? '1' : '0') == '0') ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div style="display:flex; gap:10px; justify-content:flex-end; border-top:1px solid var(--border); padding-top:20px; margin-top:4px;">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

@endsection