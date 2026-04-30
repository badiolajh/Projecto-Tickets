@extends('layouts.app')
 
@section('title', 'Dashboard — Administrador')
@section('page-title', 'Dashboard')
 
@section('topbar-actions')
    <a href="{{ route('admin.tickets') }}" class="btn btn-outline btn-sm">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
        Ver todos los tickets
    </a>
@endsection
 
@section('content')
 
{{-- Stats --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Tickets abiertos</div>
        <div class="stat-value" style="color: #185FA5;">{{ $stats['abiertos'] ?? 0 }}</div>
        <div class="stat-delta">Sin asignar técnico</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">En proceso</div>
        <div class="stat-value" style="color: #B45309;">{{ $stats['en_proceso'] ?? 0 }}</div>
        <div class="stat-delta">Siendo atendidos</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Cerrados hoy</div>
        <div class="stat-value" style="color: #1A6B3A;">{{ $stats['cerrados_hoy'] ?? 0 }}</div>
        <div class="stat-delta">Resueltos</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total técnicos</div>
        <div class="stat-value">{{ $stats['tecnicos'] ?? 0 }}</div>
        <div class="stat-delta">Activos en sistema</div>
    </div>
</div>
 
{{-- Tickets pendientes de asignación --}}
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <div>
            <div class="card-title">Tickets sin asignar</div>
            <div class="card-subtitle">Asigna un técnico para dar inicio a la atención</div>
        </div>
        <span style="font-size: 12px; background: #EBF4FF; color: #185FA5; padding: 4px 10px; border-radius: 20px; font-family: 'DM Mono', monospace; font-weight: 500;">
            {{ $ticketsSinAsignar->count() }} pendientes
        </span>
    </div>
 
    @if($ticketsSinAsignar->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">✓</div>
            <h3>Todo asignado</h3>
            <p>No hay tickets pendientes de asignación.</p>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Título</th>
                        <th>Empleado</th>
                        <th>Prioridad</th>
                        <th>Fecha</th>
                        <th>Asignar técnico</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ticketsSinAsignar as $ticket)
                    <tr>
                        <td>
                            <code style="font-family: 'DM Mono', monospace; font-size: 12px; background: #F7F6F3; padding: 2px 7px; border-radius: 4px;">
                                {{ $ticket->folio }}
                            </code>
                        </td>
                        <td>
                            <div style="font-weight: 500;">{{ $ticket->titulo }}</div>
                            <div style="font-size: 12px; color: var(--muted); margin-top: 2px; max-width: 280px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $ticket->descripcion }}
                            </div>
                        </td>
                        <td style="color: var(--muted); font-size: 13px;">{{ $ticket->empleado->usuario->nombre ?? '—' }}</td>
                        <td>
                            <span class="badge-status badge-{{ strtolower($ticket->prioridad) }}">
                                {{ ucfirst($ticket->prioridad) }}
                            </span>
                        </td>
                        <td style="color: var(--muted); font-size: 12px; white-space: nowrap;">
                            {{ $ticket->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <form action="{{ route('admin.tickets.asignar', $ticket) }}" method="POST" style="display: flex; gap: 8px; align-items: center;">
                                @csrf
                                @method('PATCH')
                                <select name="id_tecnico" style="font-size: 12px; padding: 6px 10px; min-width: 150px;" required>
                                    <option value="">Seleccionar…</option>
                                    @foreach($tecnicos as $tecnico)
                                        <option value="{{ $tecnico->id_tecnico }}">{{ $tecnico->usuario->nombre }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Asignar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
 
{{-- Tickets en proceso --}}
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">En proceso</div>
            <div class="card-subtitle">Tickets actualmente asignados a un técnico</div>
        </div>
    </div>
 
    @if($ticketsEnProceso->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">○</div>
            <h3>Sin tickets en proceso</h3>
            <p>Cuando se asignen técnicos aparecerán aquí.</p>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Título</th>
                        <th>Técnico</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Actualizado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ticketsEnProceso as $ticket)
                    <tr>
                        <td>
                            <code style="font-family: 'DM Mono', monospace; font-size: 12px; background: #F7F6F3; padding: 2px 7px; border-radius: 4px;">
                                {{ $ticket->folio }}
                            </code>
                        </td>
                        <td style="font-weight: 500;">{{ $ticket->titulo }}</td>
                        <td style="font-size: 13px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 24px; height: 24px; border-radius: 50%; background: #1A1916; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 600; flex-shrink: 0;">
                                    {{ strtoupper(substr($ticket->tecnico->usuario->nombre ?? 'T', 0, 2)) }}
                                </div>
                                {{ $ticket->tecnico->usuario->nombre ?? '—' }}
                            </div>
                        </td>
                        <td><span class="badge-status badge-en_proceso">En proceso</span></td>
                        <td><span class="badge-status badge-{{ strtolower($ticket->prioridad) }}">{{ ucfirst($ticket->prioridad) }}</span></td>
                        <td style="color: var(--muted); font-size: 12px;">{{ $ticket->updated_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-ghost btn-sm">Ver →</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
 
@endsection