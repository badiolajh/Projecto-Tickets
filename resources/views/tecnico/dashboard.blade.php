@extends('layouts.app')
 
@section('title', 'Mis Tickets — Técnico')
@section('page-title', 'Mis Tickets')
 
@section('content')
 
{{-- Stats del técnico --}}
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
 
{{-- Tickets activos --}}
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <div>
            <div class="card-title">Tickets activos</div>
            <div class="card-subtitle">Ordena tu prioridad de atención</div>
        </div>
        {{-- Filtro rápido --}}
        <div style="display: flex; gap: 6px;">
            <a href="?prioridad=Alta" class="btn btn-sm {{ request('prioridad') == 'Alta' ? 'btn-primary' : 'btn-outline' }}">Alta</a>
            <a href="?prioridad=Normal" class="btn btn-sm {{ request('prioridad') == 'Normal' ? 'btn-primary' : 'btn-outline' }}">Normal</a>
            <a href="{{ route('tecnico.tickets') }}" class="btn btn-sm {{ !request('prioridad') ? 'btn-primary' : 'btn-outline' }}">Todos</a>
        </div>
    </div>
 
    @if($ticketsActivos->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">✓</div>
            <h3>Sin tickets activos</h3>
            <p>No tienes tickets pendientes en este momento.</p>
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 0;">
            @foreach($ticketsActivos as $ticket)
            <div style="padding: 16px 0; border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; gap: 16px;">
 
                {{-- Indicador prioridad --}}
                <div style="width: 3px; border-radius: 2px; align-self: stretch; flex-shrink: 0; background:
                    {{ $ticket->prioridad == 'Alta' ? '#C0392B' : ($ticket->prioridad == 'Normal' ? '#C9C7BF' : '#1A6B3A') }};
                "></div>
 
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px; flex-wrap: wrap;">
                        <code style="font-family: 'DM Mono', monospace; font-size: 11px; background: #F7F6F3; padding: 2px 7px; border-radius: 4px; color: var(--muted);">
                            {{ $ticket->folio }}
                        </code>
                        <span class="badge-status badge-{{ strtolower($ticket->prioridad) }}">{{ ucfirst($ticket->prioridad) }}</span>
                        <span class="badge-status badge-en_proceso">En proceso</span>
                    </div>
                    <div style="font-weight: 500; font-size: 14px; margin-bottom: 4px;">{{ $ticket->titulo }}</div>
                    <div style="font-size: 12px; color: var(--muted); margin-bottom: 8px; max-width: 600px;">{{ Str::limit($ticket->descripcion, 120) }}</div>
                    <div style="font-size: 11px; color: var(--muted);">
                        Reportado por <strong style="color: var(--text);">{{ $ticket->empleado->usuario->nombre ?? '—' }}</strong>
                        · {{ $ticket->created_at->diffForHumans() }}
                    </div>
                </div>
 
                <div style="display: flex; gap: 8px; flex-shrink: 0; align-items: center;">
                    <a href="{{ route('tecnico.tickets.show', $ticket) }}" class="btn btn-outline btn-sm">Ver detalle</a>
                    <form action="{{ route('tecnico.tickets.cerrar', $ticket) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm" style="background: #ECFDF5; color: #1A6B3A; border-color: #A7F3D0;"
                            onclick="return confirm('¿Marcar este ticket como resuelto?')">
                            Marcar resuelto
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
 
{{-- Tickets cerrados recientes --}}
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Resueltos recientemente</div>
            <div class="card-subtitle">Últimos 10 tickets cerrados</div>
        </div>
    </div>
 
    @if($ticketsCerrados->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">○</div>
            <h3>Sin historial</h3>
            <p>Aún no has resuelto ningún ticket.</p>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Título</th>
                        <th>Empleado</th>
                        <th>Cerrado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ticketsCerrados as $ticket)
                    <tr>
                        <td>
                            <code style="font-family: 'DM Mono', monospace; font-size: 12px; background: #F7F6F3; padding: 2px 7px; border-radius: 4px;">
                                {{ $ticket->folio }}
                            </code>
                        </td>
                        <td style="color: var(--muted);">{{ $ticket->titulo }}</td>
                        <td style="font-size: 13px; color: var(--muted);">{{ $ticket->empleado->usuario->nombre ?? '—' }}</td>
                        <td style="font-size: 12px; color: var(--muted);">
                            {{ $ticket->closed_at ? $ticket->closed_at->format('d/m/Y H:i') : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
 
@endsection