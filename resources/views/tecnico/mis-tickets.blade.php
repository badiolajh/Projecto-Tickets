@extends('layouts.app')

@section('title', 'Historial — Técnico')
@section('page-title', 'Historial de tickets resueltos')

@section('topbar-actions')
    <a href="{{ route('tecnico.dashboard') }}" class="btn btn-ghost btn-sm">← Dashboard</a>
@endsection

@section('content')

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Total resueltos</div>
        <div class="stat-value" style="color:#1A6B3A;">{{ $tickets->total() }}</div>
        <div class="stat-delta">En toda tu trayectoria</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Este mes</div>
        <div class="stat-value">
            {{ $tickets->getCollection()->filter(fn($t) => \Carbon\Carbon::parse($t->closed_at)->isCurrentMonth())->count() }}
        </div>
        <div class="stat-delta">Cerrados en {{ now()->translatedFormat('F') }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Tickets resueltos</div>
            <div class="card-subtitle">{{ $tickets->total() }} tickets en tu historial</div>
        </div>
    </div>

    @if($tickets->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">○</div>
            <h3>Sin historial aún</h3>
            <p>Cuando resuelvas tickets aparecerán aquí.</p>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Título</th>
                        <th>Empleado</th>
                        <th>Área</th>
                        <th>Prioridad</th>
                        <th>Solución</th>
                        <th>Cerrado</th>
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
                        <td style="font-weight:500;max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $ticket->titulo }}</td>
                        <td style="font-size:13px;color:var(--muted);">{{ $ticket->empleado->usuario->nombre ?? '—' }}</td>
                        <td style="font-size:12px;color:var(--muted);">{{ $ticket->empleado->usuario->area ?? '—' }}</td>
                        <td><span class="badge-status badge-{{ strtolower($ticket->prioridad) }}">{{ ucfirst($ticket->prioridad) }}</span></td>
                        <td style="font-size:12px;color:var(--muted);max-width:200px;">
                            @if($solucionTexto)
                                <span title="{{ $solucionTexto }}" style="cursor:help;">{{ Str::limit($solucionTexto, 55) }}</span>
                            @else
                                <span style="font-style:italic;color:#C9C7BF;">Sin observaciones</span>
                            @endif
                        </td>
                        <td style="font-size:12px;color:var(--muted);white-space:nowrap;">
                            {{ $ticket->closed_at ? \Carbon\Carbon::parse($ticket->closed_at)->format('d/m/Y H:i') : '—' }}
                        </td>
                        <td>
                            <button type="button" class="btn btn-ghost btn-sm" onclick="abrirModal({
                                folio:       '{{ $ticket->folio }}',
                                titulo:      {{ json_encode($ticket->titulo) }},
                                descripcion: {{ json_encode($ticket->descripcion) }},
                                estado:      '{{ $ticket->estado }}',
                                prioridad:   '{{ $ticket->prioridad }}',
                                empleado:    {{ json_encode($ticket->empleado->usuario->nombre ?? '—') }},
                                area:        {{ json_encode($ticket->empleado->usuario->area ?? '') }},
                                tecnico:     {{ json_encode(auth()->user()->nombre) }},
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
                {{ $tickets->links() }}
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