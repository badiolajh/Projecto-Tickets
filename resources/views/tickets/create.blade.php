extends('layouts.app')
 
@section('title', 'Nuevo Ticket')
@section('page-title', 'Crear nuevo ticket')
 
@section('topbar-actions')
    <a href="{{ route('empleado.dashboard') }}" class="btn btn-ghost btn-sm">← Volver</a>
@endsection
 
@section('content')
 
<div style="max-width: 640px;">
 
    {{-- Intro --}}
    <div style="margin-bottom: 28px;">
        <p style="font-size: 14px; color: var(--muted); line-height: 1.7;">
            Describe el problema que estás experimentando con el mayor detalle posible. El equipo de soporte lo revisará y te asignará un técnico.
        </p>
    </div>
 
    <div class="card">
        <form action="{{ route('tickets.store') }}" method="POST">
            @csrf
 
            <div class="form-group">
                <label for="titulo">Título del problema <span style="color: var(--danger);">*</span></label>
                <input
                    type="text"
                    id="titulo"
                    name="titulo"
                    value="{{ old('titulo') }}"
                    placeholder="Ej. No tengo conexión a internet, Mi equipo no enciende…"
                    class="{{ $errors->has('titulo') ? 'is-invalid' : '' }}"
                    required
                    maxlength="150"
                >
                @error('titulo')
                    <div style="font-size: 12px; color: var(--danger); margin-top: 5px;">{{ $message }}</div>
                @enderror
                <div style="font-size: 11px; color: var(--muted); margin-top: 5px;">Máximo 150 caracteres</div>
            </div>
 
            <div class="form-group">
                <label for="prioridad">Prioridad <span style="color: var(--danger);">*</span></label>
                <select id="prioridad" name="prioridad" class="{{ $errors->has('prioridad') ? 'is-invalid' : '' }}">
                    <option value="Normal" {{ old('prioridad', 'Normal') == 'Normal' ? 'selected' : '' }}>Normal — Puede esperar</option>
                    <option value="Alta"   {{ old('prioridad') == 'Alta'   ? 'selected' : '' }}>Alta — Afecta mi trabajo ahora</option>
                    <option value="Baja"   {{ old('prioridad') == 'Baja'   ? 'selected' : '' }}>Baja — Cuando se pueda</option>
                </select>
                @error('prioridad')
                    <div style="font-size: 12px; color: var(--danger); margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
 
            <div class="form-group">
                <label for="descripcion">Descripción detallada <span style="color: var(--danger);">*</span></label>
                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="6"
                    placeholder="Describe el problema con detalle:&#10;• ¿Qué intentabas hacer?&#10;• ¿Cuándo comenzó?&#10;• ¿Qué equipo o sistema está afectado?&#10;• ¿Ya intentaste alguna solución?"
                    class="{{ $errors->has('descripcion') ? 'is-invalid' : '' }}"
                    required
                >{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div style="font-size: 12px; color: var(--danger); margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
 
            {{-- Hint de prioridades --}}
            <div style="background: #F7F6F3; border-radius: var(--radius); padding: 14px 16px; margin-bottom: 20px;">
                <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: var(--muted); margin-bottom: 10px;">Guía de prioridades</div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 12px;">
                        <span class="badge-status badge-alta">Alta</span>
                        <span style="color: var(--muted);">No puedo trabajar — equipo sin acceso, falla crítica</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 12px;">
                        <span class="badge-status badge-normal">Normal</span>
                        <span style="color: var(--muted);">Problema que afecta parcialmente mi trabajo</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 12px;">
                        <span class="badge-status badge-baja">Baja</span>
                        <span style="color: var(--muted);">Inconveniencia menor, no urgente</span>
                    </div>
                </div>
            </div>
 
            <div style="display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid var(--border); padding-top: 20px; margin-top: 4px;">
                <a href="{{ route('empleado.dashboard') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                    Enviar ticket
                </button>
            </div>
        </form>
    </div>
 
</div>
 
@endsection