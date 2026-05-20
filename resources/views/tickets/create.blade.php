@extends('layouts.app')

@section('title', 'Nuevo Ticket')
@section('page-title', 'Crear nuevo ticket')

@section('topbar-actions')
    <a href="{{ route('empleado.dashboard') }}" class="btn btn-ghost btn-sm">← Volver</a>
@endsection

@section('content')

<div style="max-width: 640px;">

    <div style="margin-bottom: 28px;">
        <p style="font-size: 14px; color: var(--muted); line-height: 1.7;">
            Describe el problema con el mayor detalle posible. Puedes adjuntar una imagen si te ayuda a explicarlo mejor.
        </p>
    </div>

    <div class="card">
        {{-- IMPORTANTE: agregar enctype para que funcione la subida de imagen --}}
        <form action="{{ route('empleado.tickets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="titulo">Título del problema <span style="color: var(--danger);">*</span></label>
                <input type="text" id="titulo" name="titulo"
                    value="{{ old('titulo') }}"
                    placeholder="Ej. No tengo conexión a internet, Mi equipo no enciende…"
                    class="{{ $errors->has('titulo') ? 'is-invalid' : '' }}"
                    required maxlength="150">
                @error('titulo')
                    <div style="font-size:12px;color:var(--danger);margin-top:5px;">{{ $message }}</div>
                @enderror
                <div style="font-size:11px;color:var(--muted);margin-top:5px;">Máximo 150 caracteres</div>
            </div>

            <div class="form-group">
                <label for="prioridad">Prioridad <span style="color: var(--danger);">*</span></label>
                <select id="prioridad" name="prioridad" class="{{ $errors->has('prioridad') ? 'is-invalid' : '' }}">
                    <option value="Normal" {{ old('prioridad', 'Normal') == 'Normal' ? 'selected' : '' }}>Normal — Puede esperar</option>
                    <option value="Alta"   {{ old('prioridad') == 'Alta'   ? 'selected' : '' }}>Alta — Afecta mi trabajo ahora</option>
                    <option value="Baja"   {{ old('prioridad') == 'Baja'   ? 'selected' : '' }}>Baja — Cuando se pueda</option>
                </select>
                @error('prioridad')
                    <div style="font-size:12px;color:var(--danger);margin-top:5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción detallada <span style="color: var(--danger);">*</span></label>
                <textarea id="descripcion" name="descripcion" rows="6"
                    placeholder="Describe el problema:&#10;• ¿Qué intentabas hacer?&#10;• ¿Cuándo comenzó?&#10;• ¿Qué equipo o sistema está afectado?&#10;• ¿Ya intentaste alguna solución?"
                    class="{{ $errors->has('descripcion') ? 'is-invalid' : '' }}"
                    required>{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div style="font-size:12px;color:var(--danger);margin-top:5px;">{{ $message }}</div>
                @enderror
            </div>

            {{-- ══ Campo imagen (opcional) ══ --}}
            <div class="form-group">
                <label for="imagen">
                    Imagen adjunta
                    <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#C9C7BF;">(opcional)</span>
                </label>

                {{-- Zona de drop / selector --}}
                <div id="drop-zone" onclick="document.getElementById('imagen').click()" style="
                    border: 2px dashed var(--border-md);
                    border-radius: var(--radius);
                    padding: 24px;
                    text-align: center;
                    cursor: pointer;
                    transition: border-color 0.12s, background 0.12s;
                    background: var(--bg);
                ">
                    <div id="drop-placeholder">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color:var(--muted);margin-bottom:8px;">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                        </svg>
                        <div style="font-size:13px;color:var(--muted);">Haz clic o arrastra una imagen aquí</div>
                        <div style="font-size:11px;color:#C9C7BF;margin-top:4px;">JPG, PNG o WEBP · Máx. 5 MB</div>
                    </div>

                    {{-- Preview de la imagen seleccionada --}}
                    <div id="img-preview-wrap" style="display:none;">
                        <img id="img-preview" src="" alt="Preview" style="
                            max-width: 100%; max-height: 200px;
                            border-radius: 6px;
                            object-fit: contain;
                        ">
                        <div style="margin-top:8px; display:flex; align-items:center; justify-content:center; gap:8px;">
                            <span id="img-name" style="font-size:12px;color:var(--muted);"></span>
                            <button type="button" onclick="quitarImagen(event)" style="
                                font-size:11px; color:var(--danger);
                                background:none; border:none; cursor:pointer;
                                padding:2px 6px;
                            ">✕ Quitar</button>
                        </div>
                    </div>
                </div>

                {{-- Input oculto --}}
                <input type="file" id="imagen" name="imagen"
                    accept="image/jpeg,image/png,image/webp"
                    style="display:none;"
                    onchange="previsualizarImagen(this)">

                @error('imagen')
                    <div style="font-size:12px;color:var(--danger);margin-top:5px;">{{ $message }}</div>
                @enderror
            </div>

            {{-- Guía de prioridades --}}
            <div style="background:#F7F6F3;border-radius:var(--radius);padding:14px 16px;margin-bottom:20px;">
                <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;color:var(--muted);margin-bottom:10px;">Guía de prioridades</div>
                <div style="display:flex;flex-direction:column;gap:6px;">
                    <div style="display:flex;align-items:center;gap:10px;font-size:12px;">
                        <span class="badge-status badge-alta">Alta</span>
                        <span style="color:var(--muted);">No puedo trabajar — equipo sin acceso, falla crítica</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;font-size:12px;">
                        <span class="badge-status badge-normal">Normal</span>
                        <span style="color:var(--muted);">Problema que afecta parcialmente mi trabajo</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;font-size:12px;">
                        <span class="badge-status badge-baja">Baja</span>
                        <span style="color:var(--muted);">Inconveniencia menor, no urgente</span>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;border-top:1px solid var(--border);padding-top:20px;margin-top:4px;">
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

@push('scripts')
<script>
// Previsualizar imagen seleccionada
function previsualizarImagen(input) {
    const file = input.files[0];
    if (!file) return;

    // Validar tamaño (5MB)
    if (file.size > 5 * 1024 * 1024) {
        alert('La imagen no puede superar 5 MB.');
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('img-preview').src = e.target.result;
        document.getElementById('img-name').textContent = file.name;
        document.getElementById('drop-placeholder').style.display = 'none';
        document.getElementById('img-preview-wrap').style.display  = 'block';
    };
    reader.readAsDataURL(file);
}

// Quitar imagen seleccionada
function quitarImagen(e) {
    e.stopPropagation();
    document.getElementById('imagen').value = '';
    document.getElementById('img-preview').src = '';
    document.getElementById('img-name').textContent = '';
    document.getElementById('drop-placeholder').style.display = 'block';
    document.getElementById('img-preview-wrap').style.display  = 'none';
}

// Drag & drop
const zone = document.getElementById('drop-zone');
zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor = '#1A1916'; zone.style.background = '#F0EFEC'; });
zone.addEventListener('dragleave', e => { zone.style.borderColor = ''; zone.style.background = ''; });
zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.style.borderColor = ''; zone.style.background = '';
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const input = document.getElementById('imagen');
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        previsualizarImagen(input);
    }
});
</script>
@endpush