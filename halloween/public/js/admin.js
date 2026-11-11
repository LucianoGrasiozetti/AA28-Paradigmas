import { api, enviarJSON, anunciar, iniciarNav } from './api.js';

const sesion = await iniciarNav();
if (!sesion.admin) location.href = 'login.html';

const form = document.getElementById('form');
const cuerpo = document.getElementById('cuerpo');
const titulo = document.getElementById('titulo-form');
const cancelar = document.getElementById('cancelar');

function boton(texto, etiqueta, accion) {
  const b = document.createElement('button');
  b.type = 'button';
  b.textContent = texto;
  b.setAttribute('aria-label', etiqueta);
  b.addEventListener('click', accion);
  return b;
}

function modoCrear() {
  form.reset();
  form.id_disfraz.value = '';
  form.foto.required = true;
  titulo.textContent = 'Nuevo disfraz';
  cancelar.hidden = true;
}

function modoEditar(d) {
  form.id_disfraz.value = d.id;
  form.nombre.value = d.nombre;
  form.descripcion.value = d.descripcion;
  form.foto.value = '';
  form.foto.required = false;
  titulo.textContent = `Editar disfraz: ${d.nombre}`;
  cancelar.hidden = false;
  form.nombre.focus();
}

async function eliminar(d) {
  if (!confirm(`¿Eliminar el disfraz «${d.nombre}»?`)) return;
  const r = await enviarJSON('../api/disfraces/eliminar.php', { id: d.id });
  anunciar(r.datos.mensaje || 'Error al eliminar.', !r.ok);
  if (r.ok) cargar();
}

async function cargar() {
  const r = await api('../api/disfraces/listar.php');
  cuerpo.replaceChildren();
  for (const d of r.datos.disfraces || []) {
    const tr = document.createElement('tr');
    for (const v of [d.nombre, d.descripcion, d.votos]) {
      const td = document.createElement('td');
      td.textContent = v;
      tr.append(td);
    }
    const acciones = document.createElement('td');
    acciones.append(
      boton('Editar', `Editar ${d.nombre}`, () => modoEditar(d)),
      boton('Eliminar', `Eliminar ${d.nombre}`, () => eliminar(d))
    );
    tr.append(acciones);
    cuerpo.append(tr);
  }
}

form.addEventListener('submit', async e => {
  e.preventDefault();
  const editando = form.id_disfraz.value !== '';
  const r = await api(`../api/disfraces/${editando ? 'editar' : 'crear'}.php`, { method: 'POST', body: new FormData(form) });
  anunciar(r.datos.mensaje || 'Error al guardar.', !r.ok);
  if (r.ok) { modoCrear(); cargar(); }
});
cancelar.addEventListener('click', () => { modoCrear(); anunciar('Edición cancelada.'); });

modoCrear();
cargar();
