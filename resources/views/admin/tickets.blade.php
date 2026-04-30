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
                <option value="abierto"     {{ request('estado') == 'abierto'     ? 'selected' : '' }}>Abierto</option>
                <option value="en_proceso"  {{ request('estado') == 'en_proceso'  ? 'selected' : '' }}>En proceso</option>
                <option value="cerrado"     {{ request('estado') == 'cerrado'     ? 'selected' : '' }}>Cerrado</option>
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
                    <tr>
                        <td>
                            <code style="font-family: 'DM Mono', monospace; font-size: 12px; background: #F7F6F3; padding: 2px 7px; border-radius: 4px;">
                                {{ $ticket->folio }}
                            </code>
                        </td>
                        <td>
                            <div style="font-weight: 500; max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $ticket->titulo }}
                            </div>
                        </td>
                        <td style="font-size: 13px; color: var(--muted);">{{ $ticket->empleado->usuario->nombre ?? '—' }}</td>
                        <td style="font-size: 13px;">
                            @if($ticket->tecnico)
                                <div style="display: flex; align-items: center; gap: 7px;">
                                    <div style="width: 22px; height: 22px; border-radius: 50%; background: #1A1916; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 8px; font-weight: 600; flex-shrink: 0;">
                                        {{ strtoupper(substr($ticket->tecnico->usuario->nombre, 0, 2)) }}
                                    </div>
                                    {{ $ticket->tecnico->usuario->nombre }}
                                </div>
                            @else
                                <span style="color: var(--muted); font-style: italic; font-size: 12px;">Sin asignar</span>
                            @endif
                        </td>
                        <td><span class="badge-status badge-{{ $ticket->estado }}">{{ ucfirst(str_replace('_', ' ', $ticket->estado)) }}</span></td>
                        <td><span class="badge-status badge-{{ strtolower($ticket->prioridad) }}">{{ ucfirst($ticket->prioridad) }}</span></td>
                        <td style="color: var(--muted); font-size: 12px; white-space: nowrap;">
                            {{ $ticket->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-ghost btn-sm">Ver</a>
                                @if(!$ticket->id_tecnico)
                                    <form action="{{ route('admin.tickets.asignar', $ticket) }}" method="POST" style="display: flex; gap: 6px; align-items: center;">
                                        @csrf
                                        @method('PATCH')
                                        <select name="id_tecnico" style="font-size: 11px; padding: 5px 8px;" required>
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
 
        {{-- Paginación --}}
        @if($tickets->hasPages())
            <div style="padding: 16px 0 4px; display: flex; justify-content: flex-end;">
                {{ $tickets->withQueryString()->links() }}
            </div>
        @endif
    @endif
</div>
 
@endsection