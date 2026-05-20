@extends('layouts.app')

@section('title', 'Ticket ' . $ticket->folio)
@section('page-title', 'Detalle del Ticket')

@section('topbar-actions')
    <a href="{{ route('admin.tickets') }}" class="btn btn-ghost btn-sm">← Volver a tickets</a>
@endsection

@section('content')

<div style="max-width: 720px;">

    {{-- Header del ticket --}}
    <div class="card" style="margin-bottom: 20px;">
        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 16px;">
            <div>
                <code style="font-family:'DM Mono',monospace; font-size:12px; background:#F7F6F3; padding:3px 10px; border-radius:4px; color:var(--muted);">
                    {{ $ticket->folio }}
                </code>
                <h2 style="font-size: 18px; font-weight: 600; letter-spacing: -0.3px; margin-top: 8px; line-height: 1.3;">
                    {{ $ticket->titulo }}
                </h2>
            </div>
            <div style="display: flex; gap: 8px; flex-shrink: 0; flex-wrap: wrap;">
                <span class="badge-status badge-{{ $ticket->estado }}">{{ ucfirst(str_replace('_',' ',$ticket->estado)) }}</span>
                <span class="badge-status badge-{{ strtolower($ticket->prioridad) }}">{{ ucfirst($ticket->prioridad) }}</span>
            </div>
        </div>

        {{-- Descripción --}}
        <div style="margin-bottom: 20px;">
            <div style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.07em; color:var(--muted); margin-bottom:8px;">Descripción</div>
            <div style="font-size:13.5px; line-height:1.7; background:#F7F6F3; border-radius:8px; padding:14px 16px; white-space:pre-wrap;">{{ $ticket->descripcion }}</div>
        </div>

        {{-- Datos del ticket --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div>
                <div style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.07em; color:var(--muted); margin-bottom:4px;">Empleado</div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width:26px;height:26px;border-radius:50%;background:#1A1916;color:#fff;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:600;flex-shrink:0;">
                        {{ strtoupper(substr($ticket->empleado->usuario->nombre ?? 'E', 0, 2)) }}
                    </div>
                    <div>
                        <div style="font-size:13px; font-weight:500;">{{ $ticket->empleado->usuario->nombre ?? '—' }}</div>
                        @if($ticket->empleado->usuario->area)
                            <div style="font-size:11px; color:var(--muted);">{{ $ticket->empleado->usuario->area }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <div style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.07em; color:var(--muted); margin-bottom:4px;">Técnico asignado</div>
                @if($ticket->tecnico)
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width:26px;height:26px;border-radius:50%;background:#185FA5;color:#fff;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:600;flex-shrink:0;">
                            {{ strtoupper(substr($ticket->tecnico->usuario->nombre, 0, 2)) }}
                        </div>
                        <div style="font-size:13px; font-weight:500;">{{ $ticket->tecnico->usuario->nombre }}</div>
                    </div>
                @else
                    <span style="font-size:13px; color:var(--muted); font-style:italic;">Sin asignar</span>
                @endif
            </div>

            <div>
                <div style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.07em; color:var(--muted); margin-bottom:4px;">Creado</div>
                <div style="font-size:13px;">{{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i') }}</div>
            </div>

            <div>
                <div style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.07em; color:var(--muted); margin-bottom:4px;">
                    {{ $ticket->estado == 'cerrado' ? 'Cerrado' : 'Última actualización' }}
                </div>
                <div style="font-size:13px;">
                    {{ $ticket->closed_at
                        ? \Carbon\Carbon::parse($ticket->closed_at)->format('d/m/Y H:i')
                        : \Carbon\Carbon::parse($ticket->updated_at)->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Asignar / reasignar técnico (si no está cerrado) --}}
    @if($ticket->estado !== 'cerrado')
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <div>
                <div class="card-title">{{ $ticket->id_tecnico ? 'Reasignar técnico' : 'Asignar técnico' }}</div>
                <div class="card-subtitle">{{ $ticket->id_tecnico ? 'Cambia el técnico responsable' : 'Selecciona un técnico para atender este ticket' }}</div>
            </div>
        </div>
        <form action="{{ route('admin.tickets.asignar', $ticket) }}" method="POST" style="display: flex; gap: 10px; align-items: flex-end;">
            @csrf
            @method('PATCH')
            <div style="flex: 1;">
                <label>Técnico</label>
                <select name="id_tecnico" required>
                    <option value="">Seleccionar técnico…</option>
                    @foreach($tecnicos as $tec)
                        <option value="{{ $tec->id_tecnico }}"
                            {{ $ticket->id_tecnico == $tec->id_tecnico ? 'selected' : '' }}>
                            {{ $tec->usuario->nombre }}
                            @if($tec->usuario->area) — {{ $tec->usuario->area }} @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                {{ $ticket->id_tecnico ? 'Reasignar' : 'Asignar' }}
            </button>
        </form>
    </div>
    @endif

    {{-- Historial del ticket --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Historial de cambios</div>
                <div class="card-subtitle">{{ $ticket->historial->count() }} registros</div>
            </div>
        </div>

        @if($ticket->historial->isEmpty())
            <div class="empty-state" style="padding: 32px;">
                <div class="empty-icon">○</div>
                <h3>Sin historial</h3>
                <p>No hay cambios registrados aún.</p>
            </div>
        @else
            <div>
                @foreach($ticket->historial->sortByDesc('cambiado_at') as $h)
                <div style="padding: 14px 0; border-bottom: 1px solid var(--border); display: flex; gap: 14px;">
                    <div style="flex-shrink: 0; margin-top: 3px;">
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--border-md); margin-top: 4px;"></div>
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; flex-wrap: wrap;">
                            @if($h->estado_ant && $h->estado_ant !== $h->estado_nuevo)
                                <span class="badge-status badge-{{ $h->estado_ant }}" style="font-size:10px;">{{ ucfirst(str_replace('_',' ',$h->estado_ant)) }}</span>
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--muted);"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                <span class="badge-status badge-{{ $h->estado_nuevo }}" style="font-size:10px;">{{ ucfirst(str_replace('_',' ',$h->estado_nuevo)) }}</span>
                            @else
                                <span style="font-size:11px; color:var(--muted);">Comentario</span>
                            @endif
                        </div>

                        @if($h->comentario)
                            <div style="font-size:13px; color:var(--text); background:#F7F6F3; border-radius:6px; padding:10px 12px; white-space:pre-wrap; line-height:1.6;">{{ $h->comentario }}</div>
                        @endif

                        <div style="font-size:11px; color:var(--muted); margin-top:6px;">
                            {{ \Carbon\Carbon::parse($h->cambiado_at)->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection