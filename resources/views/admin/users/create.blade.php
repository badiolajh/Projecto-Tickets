@extends('layouts.app')
 
@section('title', 'Nuevo Usuario — Admin')
@section('page-title', 'Crear nuevo usuario')
 
@section('topbar-actions')
    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">← Volver</a>
@endsection
 
@section('content')
 
<div style="max-width: 560px;">
    <div class="card">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
 
            <div class="form-group">
                <label>Nombre completo <span style="color:var(--danger);">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre') }}"
                    placeholder="Ej. Juan García" required maxlength="100"
                    class="{{ $errors->has('nombre') ? 'is-invalid' : '' }}">
                @error('nombre') <div style="font-size:12px;color:var(--danger);margin-top:4px;">{{ $message }}</div> @enderror
            </div>
 
            <div class="form-group">
                <label>Correo electrónico <span style="color:var(--danger);">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="correo@empresa.com" required
                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                @error('email') <div style="font-size:12px;color:var(--danger);margin-top:4px;">{{ $message }}</div> @enderror
            </div>
 
            <div class="form-group">
                <label>Contraseña <span style="color:var(--danger);">*</span></label>
                <input type="password" name="password" placeholder="Mínimo 6 caracteres" required
                    class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                @error('password') <div style="font-size:12px;color:var(--danger);margin-top:4px;">{{ $message }}</div> @enderror
            </div>
 
            <div class="form-group">
                <label>Rol <span style="color:var(--danger);">*</span></label>
                <select name="rol" required class="{{ $errors->has('rol') ? 'is-invalid' : '' }}">
                    <option value="">Seleccionar rol…</option>
                    <option value="empleado"      {{ old('rol') == 'empleado'      ? 'selected' : '' }}>Empleado</option>
                    <option value="tecnico"       {{ old('rol') == 'tecnico'       ? 'selected' : '' }}>Técnico</option>
                    <option value="administrador" {{ old('rol') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                </select>
                @error('rol') <div style="font-size:12px;color:var(--danger);margin-top:4px;">{{ $message }}</div> @enderror
            </div>
 
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label>Área</label>
                    <input type="text" name="area" value="{{ old('area') }}" placeholder="Ej. Administración">
                </div>
                <div class="form-group">
                    <label>Cargo</label>
                    <input type="text" name="cargo_u" value="{{ old('cargo_u') }}"
                        placeholder="Ej. Contador" maxlength="25">
                </div>
            </div>
 
            <div style="display:flex;gap:10px;justify-content:flex-end;border-top:1px solid var(--border);padding-top:20px;margin-top:4px;">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">Crear usuario</button>
            </div>
        </form>
    </div>
</div>
 
@endsection