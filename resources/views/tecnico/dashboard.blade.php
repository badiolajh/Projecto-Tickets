@extends('layouts.app')

@section('title', 'Mis Tickets — Técnico')
@section('page-title', 'Dashboard')

@section('content')

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Asignados</div>
        <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
        <div class="stat-delta">Total en mi lista</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">En proceso</div>
        <div class="stat-value" style="color: #B45309;">{{ $stats['en_proceso'] ?? 0 }}</div>
        <div class="stat-delta">Activos ahora</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Alta prioridad</div>
        <div class="stat-value" style="color: #C0392B;">{{ $stats['alta_prioridad'] ?? 0 }}</div>
        <div class="stat-delta">Requieren atención</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Resueltos</div>
        <div class="stat-value" style="color: #1A6B3A;">{{ $stats['cerrados'] ?? 0 }}</div>
        <div class="stat-delta">Completados</div>
    </div>
</div>

<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <div>
            <div class="card-title">Tickets activos</div>
            <div class="card-subtitle">Ordena tu prioridad de atención</div>
        </div>
        <div style="display: flex; gap: 6px;">
            <a href="?prioridad=Alta"   class="btn btn-sm {{ request('prioridad') == 'Alta'   ? 'btn-primary' : 'btn-outline' }}">Alta</a>
            <a href="?prioridad=Normal" class="btn btn-sm {{ request('prioridad') == 'Normal' ? 'btn-primary' : 'btn-outline' }}">Normal</a>
            <a href="{{ route('tecnico.dashboard') }}" class="btn btn-sm {{ !request('prioridad') ? 'btn-primary' : 'btn-outline' }}">Todos</a>
        </div>
    </div>

    @if(isset($ticketsActivos) && $ticketsActivos->isNotEmpty())
        <div style="display: flex; flex-direction: column;">
            @foreach($ticketsActivos as $ticket)
            <div style="padding: 16px 0; border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; gap: 16px;">
                <div style="width: 3px; border-radius: 2px; align-self: stretch; flex-shrink: 0; background:
                    {{ $ticket->prioridad == 'Alta' ? '#C0392B' : ($ticket->prioridad == 'Normal' ? '#C9C7BF' : '#1A6B3A') }};
                "></div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px; flex-wrap: wrap;">
                        <code style="font-family:'DM Mono',monospace;font-size:11px;background:#F7F6F3;padding:2px 7px;border-radius:4px;color:var(--muted);">{{ $ticket->folio }}</code>
                        <span class="badge-status badge-{{ strtolower($ticket->prioridad) }}">{{ ucfirst($ticket->prioridad) }}</span>
                        <span class="badge-status badge-en_proceso">En proceso</span>
@if($ticket->imagen_ruta)
    <span 
        onclick="abrirImagen('{{ asset('storage/' . $ticket->imagen_ruta) }}')" 
        style="font-size:11px; background:#EBF4FF; color:#185FA5; padding:2px 8px; border-radius:20px; display:inline-flex; align-items:center; gap:4px; cursor:pointer;"
        title="Clic para ver imagen"
    >
        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
        </svg>
        Con imagen
    </span>
@endif
                    </div>
                    <div style="font-weight:500;font-size:14px;margin-bottom:4px;">{{ $ticket->titulo }}</div>
                    <div style="font-size:12px;color:var(--muted);margin-bottom:8px;max-width:600px;">{{ Str::limit($ticket->descripcion, 120) }}</div>
                    <div style="font-size:11px;color:var(--muted);display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                        <span>Reportado por <strong style="color:var(--text);">{{ $ticket->empleado->usuario->nombre ?? '—' }}</strong></span>
                        @if($ticket->empleado->usuario->area)
                            <span style="background:#F7F6F3;border:1px solid var(--border);padding:2px 8px;border-radius:20px;font-size:11px;">{{ $ticket->empleado->usuario->area }}</span>
                        @endif
                        <span>· {{ \Carbon\Carbon::parse($ticket->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
                <div style="flex-shrink:0;display:flex;flex-direction:column;gap:6px;align-items:flex-end;">
                    <button type="button" class="btn btn-sm" style="background:#ECFDF5;color:#1A6B3A;border-color:#A7F3D0;"
                        onclick="abrirModal({{ $ticket->id_ticket }}, {{ json_encode($ticket->folio) }}, {{ json_encode($ticket->titulo) }}, {{ json_encode($ticket->imagen_ruta) }})">
                        ✓ Marcar resuelto
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">✓</div>
            <h3>Sin tickets activos</h3>
            <p>No tienes tickets pendientes en este momento.</p>
        </div>
    @endif
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Historial de tickets resueltos</div>
            <div class="card-subtitle">Todos los tickets que has atendido</div>
        </div>
    </div>
    @if(isset($ticketsCerrados) && $ticketsCerrados->isNotEmpty())
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Título</th>
                        <th>Empleado</th>
                        <th>Área</th>
                        <th>Solución registrada</th>
                        <th>Cerrado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ticketsCerrados as $ticket)
                    <tr>
                        <td><code style="font-family:'DM Mono',monospace;font-size:12px;background:#F7F6F3;padding:2px 7px;border-radius:4px;">{{ $ticket->folio }}</code></td>
                        <td style="font-weight:500;">{{ $ticket->titulo }}</td>
                        <td style="font-size:13px;color:var(--muted);">{{ $ticket->empleado->usuario->nombre ?? '—' }}</td>
                        <td style="font-size:12px;color:var(--muted);">{{ $ticket->empleado->usuario->area ?? '—' }}</td>
                        <td style="font-size:12px;color:var(--muted);max-width:220px;">
                            @php $resolucion = $ticket->historial->where('estado_nuevo','cerrado')->last(); @endphp
                            @if($resolucion && $resolucion->comentario)
                                <span title="{{ $resolucion->comentario }}" style="cursor:help;">{{ Str::limit($resolucion->comentario, 60) }}</span>
                            @else
                                <span style="font-style:italic;color:#C9C7BF;">Sin observaciones</span>
                            @endif
                        </td>
                        <td style="font-size:12px;color:var(--muted);white-space:nowrap;">
                            {{ $ticket->closed_at ? \Carbon\Carbon::parse($ticket->closed_at)->format('d/m/Y H:i') : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">○</div>
            <h3>Sin historial</h3>
            <p>Aún no has resuelto ningún ticket.</p>
        </div>
    @endif
</div>

{{-- MODAL RESOLUCIÓN --}}
<div id="modal-overlay" onclick="cerrarModal()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);backdrop-filter:blur(2px);z-index:1000;"></div>

<div id="modal-resolucion" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);width:500px;max-width:calc(100vw - 40px);background:#fff;border-radius:12px;border:1px solid #E4E2DC;box-shadow:0 20px 60px rgba(0,0,0,0.18);z-index:1001;">
    <div style="padding:20px 24px 16px;border-bottom:1px solid #E4E2DC;display:flex;align-items:flex-start;justify-content:space-between;">
        <div>
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;color:#6B6960;margin-bottom:4px;">Cerrar ticket</div>
            <div id="mr-folio" style="font-family:'DM Mono',monospace;font-size:12px;color:#6B6960;background:#F7F6F3;padding:2px 8px;border-radius:4px;display:inline-block;margin-bottom:6px;"></div>
            <div id="mr-titulo" style="font-size:15px;font-weight:600;letter-spacing:-0.2px;margin-bottom:8px;"></div>
            {{-- Botón Ver imagen — solo visible si el ticket tiene imagen --}}
            <button type="button" id="mr-btn-imagen" style="display:none;align-items:center;gap:6px;background:#EBF4FF;color:#185FA5;border:1px solid #BFDBFE;border-radius:6px;padding:5px 12px;font-size:12px;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                Ver imagen adjunta
            </button>
        </div>
        <button onclick="cerrarModal()" style="background:none;border:none;cursor:pointer;color:#6B6960;font-size:18px;padding:4px;line-height:1;">✕</button>
    </div>

    <form id="form-resolucion" method="POST" action="">
        @csrf
        @method('PATCH')
        <div style="padding:20px 24px;">
            <div style="background:#EBF4FF;border-radius:8px;padding:12px 14px;margin-bottom:20px;font-size:13px;color:#185FA5;line-height:1.5;">
                Documenta el diagnóstico y la solución — quedará en el historial del ticket.
            </div>
            <div class="form-group">
                <label for="mr-diagnostico">Diagnóstico <span style="color:var(--danger);">*</span></label>
                <input type="text" id="mr-diagnostico" placeholder="Ej. Cable de red dañado, configuración incorrecta…" required maxlength="200">
                <div style="font-size:11px;color:var(--muted);margin-top:4px;">¿Cuál fue el problema detectado?</div>
            </div>
            <div class="form-group">
                <label for="mr-solucion">Solución aplicada <span style="color:var(--danger);">*</span></label>
                <textarea id="mr-solucion" rows="4" placeholder="Describe paso a paso lo que hiciste para resolver el problema…" required minlength="10" maxlength="1000"></textarea>
                <div style="font-size:11px;color:var(--muted);margin-top:4px;">Mínimo 10 caracteres.</div>
            </div>
            <input type="hidden" name="comentario" id="mr-comentario">
        </div>
        <div style="padding:16px 24px;border-top:1px solid #E4E2DC;display:flex;gap:10px;justify-content:flex-end;background:#F7F6F3;border-radius:0 0 12px 12px;">
            <button type="button" onclick="cerrarModal()" class="btn btn-outline">Cancelar</button>
            <button type="submit" class="btn btn-primary" onclick="return prepararEnvio()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;"><path d="M20 6L9 17l-5-5"/></svg>
                Confirmar resolución
            </button>
        </div>
    </form>
</div>

{{-- MODAL IMAGEN --}}
<div id="img-overlay" onclick="cerrarImagen()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.85);backdrop-filter:blur(4px);z-index:2000;align-items:center;justify-content:center;"></div>
<div id="img-modal" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:2001;max-width:90vw;max-height:90vh;text-align:center;">
    <img id="img-grande" src="" alt="Imagen del ticket" style="max-width:100%;max-height:80vh;border-radius:8px;box-shadow:0 20px 60px rgba(0,0,0,0.5);">
    <div style="margin-top:12px;">
        <button onclick="cerrarImagen()" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.3);border-radius:6px;padding:7px 18px;cursor:pointer;font-size:13px;font-family:'DM Sans',sans-serif;">✕ Cerrar</button>
    </div>
</div>

@endsection

@push('scripts')
<script>
function abrirModal(id, folio, titulo, imagen) {
    document.getElementById('mr-folio').textContent  = folio;
    document.getElementById('mr-titulo').textContent = titulo;
    document.getElementById('mr-diagnostico').value  = '';
    document.getElementById('mr-solucion').value     = '';
    document.getElementById('mr-comentario').value   = '';
    document.getElementById('form-resolucion').action = '/tecnico/tickets/' + id + '/cerrar';

    // Mostrar u ocultar botón de imagen
    const btnImg = document.getElementById('mr-btn-imagen');
    if (imagen) {
        btnImg.style.display = 'inline-flex';
        btnImg.onclick = function() { abrirImagen('/storage/' + imagen); };
    } else {
        btnImg.style.display = 'none';
    }

    document.getElementById('modal-overlay').style.display    = 'block';
    document.getElementById('modal-resolucion').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    document.getElementById('modal-overlay').style.display    = 'none';
    document.getElementById('modal-resolucion').style.display = 'none';
    document.body.style.overflow = '';
}

function abrirImagen(src) {
    document.getElementById('img-grande').src = src;
    document.getElementById('img-overlay').style.display = 'flex';
    document.getElementById('img-modal').style.display   = 'block';
}

function cerrarImagen() {
    document.getElementById('img-overlay').style.display = 'none';
    document.getElementById('img-modal').style.display   = 'none';
    document.getElementById('img-grande').src = '';
}

function prepararEnvio() {
    const diag = document.getElementById('mr-diagnostico').value.trim();
    const sol  = document.getElementById('mr-solucion').value.trim();
    if (!diag || sol.length < 10) return false;
    document.getElementById('mr-comentario').value = 'Diagnóstico: ' + diag + '\n\nSolución aplicada: ' + sol;
    return true;
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { cerrarImagen(); cerrarModal(); }
});
</script>
@endpush