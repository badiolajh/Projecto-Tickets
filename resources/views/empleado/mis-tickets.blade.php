@extends('layouts.app')

@section('title', 'Mis Tickets — Empleado')
@section('page-title', 'Mis Tickets')

@section('topbar-actions')
    <a href="{{ route('empleado.tickets.crear') }}" class="btn btn-primary btn-sm">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;"><path d="M12 5v14M5 12h14"/></svg>
        Nuevo ticket
    </a>
@endsection

@section('content')

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Abiertos</div>
        <div class="stat-value" style="color:#185FA5;">{{ $stats['abiertos'] ?? 0 }}</div>
        <div class="stat-delta">En espera</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">En proceso</div>
        <div class="stat-value" style="color:#B45309;">{{ $stats['en_proceso'] ?? 0 }}</div>
        <div class="stat-delta">Siendo atendidos</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Resueltos</div>
        <div class="stat-value" style="color:#1A6B3A;">{{ $stats['cerrados'] ?? 0 }}</div>
        <div class="stat-delta">Completados</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Historial completo</div>
            <div class="card-subtitle">{{ $tickets->total() }} solicitudes en total</div>
        </div>
        <div style="display:flex;gap:6px;">
            <a href="?estado="          class="btn btn-sm {{ !request('estado')                  ? 'btn-primary' : 'btn-outline' }}">Todos</a>
            <a href="?estado=abierto"   class="btn btn-sm {{ request('estado') == 'abierto'      ? 'btn-primary' : 'btn-outline' }}">Abiertos</a>
            <a href="?estado=en_proceso"class="btn btn-sm {{ request('estado') == 'en_proceso'   ? 'btn-primary' : 'btn-outline' }}">En proceso</a>
            <a href="?estado=cerrado"   class="btn btn-sm {{ request('estado') == 'cerrado'      ? 'btn-primary' : 'btn-outline' }}">Resueltos</a>
        </div>
    </div>

    @if($tickets->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">○</div>
            <h3>Sin tickets aún</h3>
            <p>Crea tu primer reporte si tienes algún problema técnico.</p>
            <a href="{{ route('empleado.tickets.crear') }}" class="btn btn-primary" style="margin-top:16px;display:inline-flex;gap:6px;">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;"><path d="M12 5v14M5 12h14"/></svg>
                Crear ticket
            </a>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Título</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Técnico</th>
                        <th>Fecha</th>
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
                            <code style="font-family:'DM Mono',monospace;font-size:12px;background:#F7F6F3;padding:2px 7px;border-radius:4px;">{{ $ticket->folio }}</code>
                        </td>
                        <td>
                            <div style="font-weight:500;max-width:200px;">{{ $ticket->titulo }}</div>
                            <div style="font-size:11px;color:var(--muted);margin-top:2px;">{{ Str::limit($ticket->descripcion, 50) }}</div>
                        </td>
                        <td><span class="badge-status badge-{{ $ticket->estado }}">{{ ucfirst(str_replace('_',' ',$ticket->estado)) }}</span></td>
                        <td><span class="badge-status badge-{{ strtolower($ticket->prioridad) }}">{{ ucfirst($ticket->prioridad) }}</span></td>
                        <td style="font-size:13px;">
                            @if($ticket->tecnico)
                                <div style="display:flex;align-items:center;gap:7px;">
                                    <div style="width:22px;height:22px;border-radius:50%;background:#1A1916;color:#fff;display:flex;align-items:center;justify-content:center;font-size:8px;font-weight:600;flex-shrink:0;">
                                        {{ strtoupper(substr($ticket->tecnico->usuario->nombre, 0, 2)) }}
                                    </div>
                                    {{ $ticket->tecnico->usuario->nombre }}
                                </div>
                            @else
                                <span style="color:var(--muted);font-size:12px;font-style:italic;">Pendiente</span>
                            @endif
                        </td>
                        <td style="font-size:11px;color:var(--muted);white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y') }}<br>
                            <span style="color:#C9C7BF;">{{ \Carbon\Carbon::parse($ticket->created_at)->format('H:i') }}</span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-ghost btn-sm" onclick="abrirModal({
                                folio:       '{{ $ticket->folio }}',
                                titulo:      {{ json_encode($ticket->titulo) }},
                                descripcion: {{ json_encode($ticket->descripcion) }},
                                estado:      '{{ $ticket->estado }}',
                                prioridad:   '{{ $ticket->prioridad }}',
                                empleado:    {{ json_encode(auth()->user()->nombre) }},
                                area:        {{ json_encode(auth()->user()->area ?? '') }},
                                tecnico:     {{ json_encode($ticket->tecnico->usuario->nombre ?? null) }},
                                creado:      '{{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i') }}',
                                actualizado: '{{ \Carbon\Carbon::parse($ticket->updated_at)->format('d/m/Y H:i') }}',
                                cerrado:     '{{ $ticket->closed_at ? \Carbon\Carbon::parse($ticket->closed_at)->format('d/m/Y H:i') : '' }}',
                                solucion:    {{ json_encode($solucionTexto) }},
                                imagen:      {{ json_encode($ticket->imagen_ruta) }}
                            })">Ver</button>
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

{{-- ══ MODAL DETALLE (igual al admin) ══ --}}
@include('partials.modal_ticket')

@endsection

@push('scripts')
@include('partials.modal_ticket_js')
@endpush