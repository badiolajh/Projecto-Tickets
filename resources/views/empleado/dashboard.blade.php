@extends('layouts.app')

@section('title', 'Dashboard — Empleado')
@section('page-title', 'Dashboard')

@section('topbar-actions')
    <a href="{{ route('empleado.tickets.crear') }}" class="btn btn-primary btn-sm">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;"><path d="M12 5v14M5 12h14"/></svg>
        Nuevo ticket
    </a>
@endsection

@section('content')

{{-- Stats --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Abiertos</div>
        <div class="stat-value" style="color: #185FA5;">{{ $stats['abiertos'] ?? 0 }}</div>
        <div class="stat-delta">En espera de técnico</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">En proceso</div>
        <div class="stat-value" style="color: #B45309;">{{ $stats['en_proceso'] ?? 0 }}</div>
        <div class="stat-delta">Siendo atendidos</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Resueltos</div>
        <div class="stat-value" style="color: #1A6B3A;">{{ $stats['cerrados'] ?? 0 }}</div>
        <div class="stat-delta">Completados</div>
    </div>
</div>

{{-- Tickets activos (abiertos + en proceso) --}}
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <div>
            <div class="card-title">Tickets activos</div>
            <div class="card-subtitle">Tus solicitudes pendientes de resolución</div>
        </div>
        <a href="{{ route('empleado.tickets') }}" class="btn btn-ghost btn-sm">Ver historial completo →</a>
    </div>

    @php
        $activos = $tickets->getCollection()->whereIn('estado', ['abierto', 'en_proceso']);
    @endphp

    @if($activos->isEmpty())
        <div class="empty-state" style="padding: 32px 24px;">
            <div class="empty-icon">✓</div>
            <h3>Sin tickets activos</h3>
            <p>No tienes solicitudes pendientes. ¿Todo funciona bien?</p>
        </div>
    @else
        <div>
            @foreach($activos as $ticket)
            <div style="padding: 14px 0; border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; gap: 14px;">

                {{-- Indicador de estado --}}
                <div style="margin-top: 5px; flex-shrink: 0;">
                    @if($ticket->estado == 'abierto')
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: #185FA5; box-shadow: 0 0 0 3px #EBF4FF;"></div>
                    @else
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: #B45309; box-shadow: 0 0 0 3px #FEF3E2;"></div>
                    @endif
                </div>

                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; flex-wrap: wrap;">
                        <code style="font-family:'DM Mono',monospace; font-size:11px; background:#F7F6F3; padding:2px 7px; border-radius:4px; color:var(--muted);">
                            {{ $ticket->folio }}
                        </code>
                        <span class="badge-status badge-{{ $ticket->estado }}">{{ ucfirst(str_replace('_',' ',$ticket->estado)) }}</span>
                        <span class="badge-status badge-{{ strtolower($ticket->prioridad) }}">{{ ucfirst($ticket->prioridad) }}</span>
                    </div>
                    <div style="font-weight: 500; font-size: 14px; margin-bottom: 3px;">{{ $ticket->titulo }}</div>
                    @if($ticket->tecnico)
                        <div style="font-size: 11px; color: var(--muted);">
                            Técnico: <strong style="color: var(--text);">{{ $ticket->tecnico->usuario->nombre }}</strong>
                        </div>
                    @else
                        <div style="font-size: 11px; color: var(--muted); font-style: italic;">Esperando asignación de técnico…</div>
                    @endif
                </div>

                <div style="font-size: 11px; color: var(--muted); flex-shrink: 0; text-align: right; white-space: nowrap;">
                    {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y') }}<br>
                    <span style="color: #C9C7BF;">{{ \Carbon\Carbon::parse($ticket->created_at)->format('H:i') }}</span>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Últimos resueltos (máx 3) --}}
@php
    $recientesResueltos = $tickets->getCollection()->whereIn('estado', ['cerrado','resuelto'])->take(3);
@endphp

@if($recientesResueltos->isNotEmpty())
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Resueltos recientemente</div>
            <div class="card-subtitle">Últimas solicitudes atendidas</div>
        </div>
        <a href="{{ route('empleado.tickets') }}" class="btn btn-ghost btn-sm">Ver todos →</a>
    </div>
    <div>
        @foreach($recientesResueltos as $ticket)
        <div style="padding: 12px 0; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
            <div style="width: 8px; height: 8px; border-radius: 50%; background: #1A6B3A; box-shadow: 0 0 0 3px #ECFDF5; flex-shrink: 0;"></div>
            <div style="flex: 1;">
                <div style="font-weight: 500; font-size: 13px;">{{ $ticket->titulo }}</div>
                <div style="font-size: 11px; color: var(--muted);">
                    <code style="font-family:'DM Mono',monospace; font-size:10px; background:#F7F6F3; padding:1px 5px; border-radius:3px;">{{ $ticket->folio }}</code>
                    · Resuelto por {{ $ticket->tecnico->usuario->nombre ?? '—' }}
                </div>
            </div>
            <div style="font-size: 11px; color: var(--muted); white-space: nowrap;">
                {{ $ticket->closed_at ? \Carbon\Carbon::parse($ticket->closed_at)->format('d/m/Y') : '—' }}
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection