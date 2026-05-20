{{-- Partial: modal-ticket
     Usado en: admin/tickets, empleado/mis-tickets, tecnico/mis-tickets
     Incluir con: @include('_partials.modal-ticket')
--}}

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

    {{-- Badges + botón imagen --}}
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

        {{-- Grid de datos --}}
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

        {{-- Solución del técnico --}}
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