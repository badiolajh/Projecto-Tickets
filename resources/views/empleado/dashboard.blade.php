@extends('layouts.app')

@section('title', 'Mis Tickets — Empleado')
@section('page-title', 'Mis Tickets')

@section('topbar-actions')
    <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-sm">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
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

{{-- Lista de tickets del empleado --}}
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Mis reportes</div>
            <div class="card-subtitle">Historial de tickets que has creado</div>
        </div>
    </div>

    @if($tickets->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">○</div>
            <h3>Sin tickets aún</h3>
            <p>Crea tu primer reporte si tienes algún problema técnico.</p>
            <a href="{{ route('tickets.create') }}" class="btn btn-primary" style="margin-top: 16px; display: inline-flex;">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;"><path d="M12 5v14M5 12h14"/></svg>
                Crear ticket
            </a>
        </div>
    @else
        <div>
            @foreach($tickets as $ticket)
            <div style="padding: 16px 0; border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; gap: 16px;">

                {{-- Estado visual --}}
                <div style="margin-top: 4px; flex-shrink: 0;">
                    @if($ticket->estado == 'abierto')
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: #185FA5; box-shadow: 0 0 0 3px #EBF4FF;"></div>
                    @elseif($ticket->estado == 'en_proceso')
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: #B45309; box-shadow: 0 0 0 3px #FEF3E2;"></div>
                    @else
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: #1A6B3A; box-shadow: 0 0 0 3px #ECFDF5;"></div>
                    @endif
                </div>

                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px; flex-wrap: wrap;">
                        <code style="font-family: 'DM Mono', monospace; font-size: 11px; background: #F7F6F3; padding: 2px 7px; border-radius: 4px; color: var(--muted);">
                            {{ $ticket->folio }}
                        </code>
                        <span class="badge-status badge-{{ $ticket->estado }}">{{ ucfirst(str_replace('_', ' ', $ticket->estado)) }}</span>
                        <span class="badge-status badge-{{ strtolower($ticket->prioridad) }}">{{ ucfirst($ticket->prioridad) }}</span>
                    </div>

                    <div style="font-weight: 500; font-size: 14px; margin-bottom: 4px;">{{ $ticket->titulo }}</div>
                    <div style="font-size: 12px; color: var(--muted); max-width: 560px;">{{ Str::limit($ticket->descripcion, 100) }}</div>

                    @if($ticket->tecnico)
                        <div style="font-size: 11px; color: var(--muted); margin-top: 8px; display: flex; align-items: center; gap: 6px;">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Técnico asignado: <strong style="color: var(--text);">{{ $ticket->tecnico->usuario->nombre }}</strong>
                        </div>
                    @else
                        <div style="font-size: 11px; color: var(--muted); margin-top: 8px;">
                            Esperando asignación de técnico…
                        </div>
                    @endif
                </div>

                <div style="font-size: 11px; color: var(--muted); flex-shrink: 0; text-align: right; white-space: nowrap;">
                    {{ $ticket->created_at->format('d/m/Y') }}<br>
                    {{ $ticket->created_at->format('H:i') }}
                </div>
            </div>
            @endforeach
        </div>

        @if($tickets->hasPages())
            <div style="padding: 16px 0 4px; display: flex; justify-content: flex-end;">
                {{ $tickets->links() }}
            </div>
        @endif
    @endif
</div>

@endsection