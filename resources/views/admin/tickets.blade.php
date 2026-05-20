@extends('layouts.app')

@section('title', 'Tickets — Administrador')
@section('page-title', 'Todos los Tickets')

@section('topbar-actions')
    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost btn-sm">← Dashboard</a>
@endsection

@section('content')

{{-- Filtros --}}
<div class="card" style="margin-bottom: 20px;">
    <form method="GET" action="{{ route('admin.tickets') }}" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 180px;">
            <label>Buscar</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Folio, título, empleado…">
        </div>
        <div style="min-width: 140px;">
            <label>Estado</label>
            <select name="estado">
                <option value="">Todos</option>
                <option value="abierto"    {{ request('estado') == 'abierto'    ? 'selected' : '' }}>Abierto</option>
                <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>En proceso</option>
                <option value="cerrado"    {{ request('estado') == 'cerrado'    ? 'selected' : '' }}>Cerrado</option>
            </select>
        </div>
        <div style="min-width: 140px;">
            <label>Prioridad</label>
            <select name="prioridad">
                <option value="">Todas</option>
                <option value="Alta"   {{ request('prioridad') == 'Alta'   ? 'selected' : '' }}>Alta</option>
                <option value="Normal" {{ request('prioridad') == 'Normal' ? 'selected' : '' }}>Normal</option>
                <option value="Baja"   {{ request('prioridad') == 'Baja'   ? 'selected' : '' }}>Baja</option>
            </select>
        </div>
        <div style="min-width: 160px;">
            <label>Técnico</label>
            <select name="tecnico">
                <option value="">Todos</option>
                @foreach($tecnicos as $tec)
                    <option value="{{ $tec->id_tecnico }}" {{ request('tecnico') == $tec->id_tecnico ? 'selected' : '' }}>
                        {{ $tec->usuario->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
            <a href="{{ route('admin.tickets') }}" class="btn btn-outline btn-sm">Limpiar</a>
        </div>
    </form>
</div>

{{-- Tabla --}}
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Listado de tickets</div>
            <div class="card-subtitle">{{ $tickets->total() }} resultados</div>
        </div>
    </div>

    @if($tickets->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">○</div>
            <h3>Sin resultados</h3>
            <p>No hay tickets que coincidan con los filtros aplicados.</p>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Título</th>
                        <th>Empleado</th>
                        <th>Técnico</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Creado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                    @php
                        $resolucion = $ticket->historial->where('estado_nuevo','cerrado')->last();
                        $solucionTexto = $resolucion?->comentario ?? '';
                    @endphp
                    <tr>
                        <td>
                            <code style="font-family:'DM Mono',monospace;font-size:12px;background:#F7F6F3;padding:2px 7px;border-radius:4px;">
                                {{ $ticket->folio }}
                            </code>
                        </td>
                        <td>
                            <div style="font-weight:500;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $ticket->titulo }}</div>
                        </td>
                        <td style="font-size:13px;color:var(--muted);">{{ $ticket->empleado->usuario->nombre ?? '—' }}</td>
                        <td style="font-size:13px;">
                            @if($ticket->tecnico)
                                <div style="display:flex;align-items:center;gap:7px;">
                                    <div style="width:22px;height:22px;border-radius:50%;background:#1A1916;color:#fff;display:flex;align-items:center;justify-content:center;font-size:8px;font-weight:600;flex-shrink:0;">
                                        {{ strtoupper(substr($ticket->tecnico->usuario->nombre, 0, 2)) }}
                                    </div>
                                    {{ $ticket->tecnico->usuario->nombre }}
                                </div>
                            @else
                                <span style="color:var(--muted);font-style:italic;font-size:12px;">Sin asignar</span>
                            @endif
                        </td>
                        <td><span class="badge-status badge-{{ $ticket->estado }}">{{ ucfirst(str_replace('_',' ',$ticket->estado)) }}</span></td>
                        <td><span class="badge-status badge-{{ strtolower($ticket->prioridad) }}">{{ ucfirst($ticket->prioridad) }}</span></td>
                        <td style="color:var(--muted);font-size:12px;white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y') }}
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;align-items:center;">
                                {{-- Botón Ver → abre modal --}}
                                <button type="button" class="btn btn-ghost btn-sm" onclick="abrirModal({
                                    folio:       '{{ $ticket->folio }}',
                                    titulo:      {{ json_encode($ticket->titulo) }},
                                    descripcion: {{ json_encode($ticket->descripcion) }},
                                    estado:      '{{ $ticket->estado }}',
                                    prioridad:   '{{ $ticket->prioridad }}',
                                    empleado:    {{ json_encode($ticket->empleado->usuario->nombre ?? '—') }},
                                    area:        {{ json_encode($ticket->empleado->usuario->area ?? '') }},
                                    tecnico:     {{ json_encode($ticket->tecnico->usuario->nombre ?? null) }},
                                    creado:      '{{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i') }}',
                                    actualizado: '{{ \Carbon\Carbon::parse($ticket->updated_at)->format('d/m/Y H:i') }}',
                                    cerrado:     '{{ $ticket->closed_at ? \Carbon\Carbon::parse($ticket->closed_at)->format('d/m/Y H:i') : '' }}',
                                    solucion:    {{ json_encode($solucionTexto) }},
                                    imagen:      {{ json_encode($ticket->imagen_ruta) }}
                                })">Ver</button>

                                @if(!$ticket->id_tecnico)
                                    <form action="{{ route('admin.tickets.asignar', $ticket) }}" method="POST" style="display:flex;gap:6px;align-items:center;">
                                        @csrf
                                        @method('PATCH')
                                        <select name="id_tecnico" style="font-size:11px;padding:5px 8px;" required>
                                            <option value="">Asignar…</option>
                                            @foreach($tecnicos as $tec)
                                                <option value="{{ $tec->id_tecnico }}">{{ $tec->usuario->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">→</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div style="padding:16px 0 4px;display:flex;justify-content:flex-end;">
                {{ $tickets->withQueryString()->links() }}
            </div>
        @endif
    @endif
</div>

{{-- ══ MODAL DETALLE TICKET ══ --}}
<div id="modal-overlay" onclick="cerrarModal()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);backdrop-filter:blur(2px);z-index:1000;"></div>

<div id="modal-ticket" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);width:560px;max-width:calc(100vw - 40px);max-height:88vh;background:#fff;border-radius:12px;border:1px solid #E4E2DC;box-shadow:0 20px 60px rgba(0,0,0,0.15);z-index:1001;overflow-y:auto;">

    {{-- Header --}}
    <div style="padding:20px 24px 16px;border-bottom:1px solid #E4E2DC;display:flex;align-items:flex-start;justify-content:space-between;gap:12px;position:sticky;top:0;background:#fff;z-index:10;">
        <div style="flex:1;">
            <div id="m-folio" style="font-family:'DM Mono',monospace;font-size:11px;color:#6B6960;background:#F7F6F3;padding:2px 8px;border-radius:4px;display:inline-block;margin-bottom:6px;"></div>
            <div id="m-titulo" style="font-size:16px;font-weight:600;letter-spacing:-0.2px;line-height:1.3;"></div>
        </div>
        <button onclick="cerrarModal()" style="background:none;border:none;cursor:pointer;color:#6B6960;font-size:18px;line-height:1;padding:4px;flex-shrink:0;">✕</button>
    </div>

    {{-- Badges --}}
    <div style="padding:12px 24px;border-bottom:1px solid #E4E2DC;display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        <span id="m-badge-estado"></span>
        <span id="m-badge-prioridad"></span>
        <button type="button" id="m-btn-imagen" onclick="abrirImagen()" style="display:none;align-items:center;gap:5px;background:#EBF4FF;color:#185FA5;border:1px solid #BFDBFE;border-radius:20px;padding:3px 10px;font-size:11px;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;">
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            Ver imagen adjunta
        </button>
    </div>

    <div style="padding:20px 24px;">

        {{-- Descripción --}}
        <div style="margin-bottom:20px;">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;color:#6B6960;margin-bottom:8px;">Descripción</div>
            <div id="m-descripcion" style="font-size:13.5px;line-height:1.7;color:#1A1916;white-space:pre-wrap;background:#F7F6F3;border-radius:8px;padding:12px 14px;"></div>
        </div>

        {{-- Grid datos --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
            <div>
                <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;color:#6B6960;margin-bottom:4px;">Empleado</div>
                <div id="m-empleado" style="font-size:13.5px;font-weight:500;"></div>
                <div id="m-area" style="font-size:12px;color:#6B6960;"></div>
            </div>
            <div>
                <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;color:#6B6960;margin-bottom:4px;">Técnico asignado</div>
                <div id="m-tecnico" style="font-size:13.5px;font-weight:500;"></div>
            </div>
            <div>
                <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;color:#6B6960;margin-bottom:4px;">Creado</div>
                <div id="m-creado" style="font-size:13px;"></div>
            </div>
            <div>
                <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;color:#6B6960;margin-bottom:4px;">Última actualización</div>
                <div id="m-actualizado" style="font-size:13px;"></div>
            </div>
        </div>

        {{-- Solución del técnico (solo si cerrado) --}}
        <div id="m-solucion-wrap" style="display:none;margin-bottom:16px;">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;color:#6B6960;margin-bottom:8px;">Solución registrada por el técnico</div>
            <div id="m-solucion" style="font-size:13px;line-height:1.7;white-space:pre-wrap;background:#ECFDF5;border-left:3px solid #1A6B3A;border-radius:0 8px 8px 0;padding:12px 14px;color:#1A1916;"></div>
        </div>

        {{-- Fecha cierre --}}
        <div id="m-cierre-wrap" style="display:none;padding:10px 14px;background:#ECFDF5;border-radius:8px;font-size:13px;color:#1A6B3A;">
            ✓ Cerrado el <strong id="m-cerrado"></strong>
        </div>
    </div>
</div>

{{-- Modal imagen fullscreen --}}
<div id="img-overlay" onclick="cerrarImagen()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.88);backdrop-filter:blur(4px);z-index:2000;align-items:center;justify-content:center;"></div>
<div id="img-modal" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:2001;max-width:90vw;max-height:90vh;text-align:center;">
    <img id="img-grande" src="" alt="Imagen del ticket" style="max-width:100%;max-height:80vh;border-radius:8px;box-shadow:0 20px 60px rgba(0,0,0,0.5);">
    <div style="margin-top:12px;">
        <button onclick="cerrarImagen()" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.3);border-radius:6px;padding:7px 18px;cursor:pointer;font-size:13px;font-family:'DM Sans',sans-serif;">✕ Cerrar</button>
    </div>
</div>

@endsection

@push('scripts')
<script>
let imagenActual = null;

const estadoMap = {
    'abierto':    { label:'Abierto',    bg:'#EBF4FF', color:'#185FA5' },
    'en_proceso': { label:'En proceso', bg:'#FEF3E2', color:'#B45309' },
    'cerrado':    { label:'Cerrado',    bg:'#ECFDF5', color:'#1A6B3A' },
};
const prioridadMap = {
    'Alta':   { bg:'#FEF2F2', color:'#C0392B' },
    'Normal': { bg:'#F7F6F3', color:'#6B6960' },
    'Baja':   { bg:'#ECFDF5', color:'#1A6B3A' },
};

function badge(text, bg, color) {
    return `<span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;background:${bg};color:${color};"><span style="width:5px;height:5px;border-radius:50%;background:currentColor;"></span>${text}</span>`;
}

function abrirModal(t) {
    imagenActual = t.imagen;

    document.getElementById('m-folio').textContent      = t.folio;
    document.getElementById('m-titulo').textContent     = t.titulo;
    document.getElementById('m-descripcion').textContent = t.descripcion;
    document.getElementById('m-empleado').textContent   = t.empleado;
    document.getElementById('m-area').textContent       = t.area || '';
    document.getElementById('m-creado').textContent     = t.creado;
    document.getElementById('m-actualizado').textContent = t.actualizado;

    // Técnico
    const tecEl = document.getElementById('m-tecnico');
    tecEl.textContent   = t.tecnico || '—';
    tecEl.style.color   = t.tecnico ? '#1A1916' : '#6B6960';
    tecEl.style.fontStyle = t.tecnico ? 'normal' : 'italic';

    // Badges
    const e = estadoMap[t.estado]      || { label: t.estado,    bg:'#F7F6F3', color:'#6B6960' };
    const p = prioridadMap[t.prioridad] || { bg:'#F7F6F3', color:'#6B6960' };
    document.getElementById('m-badge-estado').innerHTML    = badge(e.label, e.bg, e.color);
    document.getElementById('m-badge-prioridad').innerHTML = badge(t.prioridad, p.bg, p.color);

    // Imagen
    const btnImg = document.getElementById('m-btn-imagen');
    btnImg.style.display = t.imagen ? 'inline-flex' : 'none';

    // Solución del técnico
    const solWrap = document.getElementById('m-solucion-wrap');
    if (t.solucion) {
        document.getElementById('m-solucion').textContent = t.solucion;
        solWrap.style.display = 'block';
    } else {
        solWrap.style.display = 'none';
    }

    // Fecha cierre
    const cierreWrap = document.getElementById('m-cierre-wrap');
    if (t.cerrado) {
        document.getElementById('m-cerrado').textContent = t.cerrado;
        cierreWrap.style.display = 'block';
    } else {
        cierreWrap.style.display = 'none';
    }

    document.getElementById('modal-overlay').style.display = 'block';
    document.getElementById('modal-ticket').style.display  = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    document.getElementById('modal-overlay').style.display = 'none';
    document.getElementById('modal-ticket').style.display  = 'none';
    document.body.style.overflow = '';
}

function abrirImagen() {
    if (!imagenActual) return;
    document.getElementById('img-grande').src = '/storage/' + imagenActual;
    document.getElementById('img-overlay').style.display = 'flex';
    document.getElementById('img-modal').style.display   = 'block';
}

function cerrarImagen() {
    document.getElementById('img-overlay').style.display = 'none';
    document.getElementById('img-modal').style.display   = 'none';
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') { cerrarImagen(); cerrarModal(); } });
</script>
@endpush