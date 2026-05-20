{{-- Partial: modal-ticket-js
     JS compartido del modal de detalle de ticket.
     Incluir con: @include('_partials.modal-ticket-js') dentro de @push('scripts')
--}}
<script>
let imagenActual = null;

const estadoMap = {
    'abierto':    { label:'Abierto',    bg:'#EBF4FF', color:'#185FA5' },
    'en_proceso': { label:'En proceso', bg:'#FEF3E2', color:'#B45309' },
    'cerrado':    { label:'Cerrado',    bg:'#ECFDF5', color:'#1A6B3A' },
    'resuelto':   { label:'Resuelto',   bg:'#ECFDF5', color:'#1A6B3A' },
};
const prioridadMap = {
    'Alta':   { bg:'#FEF2F2', color:'#C0392B' },
    'Normal': { bg:'#F7F6F3', color:'#6B6960' },
    'Baja':   { bg:'#ECFDF5', color:'#1A6B3A' },
};

function badge(text, bg, color) {
    return `<span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;background:${bg};color:${color};">
        <span style="width:5px;height:5px;border-radius:50%;background:currentColor;"></span>${text}
    </span>`;
}

function abrirModal(t) {
    imagenActual = t.imagen;

    document.getElementById('m-folio').textContent       = t.folio;
    document.getElementById('m-titulo').textContent      = t.titulo;
    document.getElementById('m-descripcion').textContent = t.descripcion;
    document.getElementById('m-empleado').textContent    = t.empleado;
    document.getElementById('m-area').textContent        = t.area || '';
    document.getElementById('m-creado').textContent      = t.creado;
    document.getElementById('m-actualizado').textContent = t.actualizado;

    // Técnico
    const tecEl = document.getElementById('m-tecnico');
    tecEl.textContent    = t.tecnico || '—';
    tecEl.style.color    = t.tecnico ? '#1A1916' : '#6B6960';
    tecEl.style.fontStyle = t.tecnico ? 'normal' : 'italic';

    // Badges
    const e = estadoMap[t.estado]       || { label: t.estado,    bg:'#F7F6F3', color:'#6B6960' };
    const p = prioridadMap[t.prioridad] || { bg:'#F7F6F3', color:'#6B6960' };
    document.getElementById('m-badge-estado').innerHTML    = badge(e.label, e.bg, e.color);
    document.getElementById('m-badge-prioridad').innerHTML = badge(t.prioridad, p.bg, p.color);

    // Imagen
    document.getElementById('m-btn-imagen').style.display = t.imagen ? 'inline-flex' : 'none';

    // Solución del técnico
    const solWrap = document.getElementById('m-solucion-wrap');
    if (t.solucion) {
        document.getElementById('m-solucion').textContent = t.solucion;
        solWrap.style.display = 'block';
    } else {
        solWrap.style.display = 'none';
    }

    // Fecha cierre
    const cierreWrap = document.getElementById('m-cierre-wrap');
    if (t.cerrado) {
        document.getElementById('m-cerrado').textContent = t.cerrado;
        cierreWrap.style.display = 'block';
    } else {
        cierreWrap.style.display = 'none';
    }

    document.getElementById('modal-overlay').style.display = 'block';
    document.getElementById('modal-ticket').style.display  = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    document.getElementById('modal-overlay').style.display = 'none';
    document.getElementById('modal-ticket').style.display  = 'none';
    document.body.style.overflow = '';
}

function abrirImagen() {
    if (!imagenActual) return;
    document.getElementById('img-grande').src = '/storage/' + imagenActual;
    document.getElementById('img-overlay').style.display = 'flex';
    document.getElementById('img-modal').style.display   = 'block';
}

function cerrarImagen() {
    document.getElementById('img-overlay').style.display = 'none';
    document.getElementById('img-modal').style.display   = 'none';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { cerrarImagen(); cerrarModal(); }
});
</script>