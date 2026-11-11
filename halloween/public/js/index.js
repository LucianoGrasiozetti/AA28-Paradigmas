import { api, enviarJSON, anunciar, iniciarNav } from './api.js';

const lista = document.getElementById('lista');
const sesion = await iniciarNav();

async function votar(d, boton, pVotos) {
  if (!sesion.logueado) {
    anunciar('Iniciá sesión para votar.', true);
    return;
  }
  const r = await enviarJSON('../api/votos/votar.php', { id_disfraz: d.id });
  anunciar(r.datos.mensaje || 'Error al votar.', !r.ok);
  if (r.ok || r.status === 409) marcarVotado(d, boton);
  if (r.ok) pVotos.textContent = `Votos: ${r.datos.votos}`;
}

function marcarVotado(d, boton) {
  boton.disabled = true;
  boton.textContent = 'Ya votaste';
  boton.setAttribute('aria-label', `Ya votaste por ${d.nombre}`);
}

function tarjeta(d, votados) {
  const art = document.createElement('article');
  art.className = 'tarjeta';
  const h2 = document.createElement('h2');
  h2.textContent = d.nombre;
  const img = document.createElement('img');
  img.src = d.foto;
  img.alt = `Foto del disfraz ${d.nombre}`;
  img.width = 300;
  img.height = 300;
  img.loading = 'lazy';
  const p = document.createElement('p');
  p.textContent = d.descripcion;
  const pVotos = document.createElement('p');
  pVotos.textContent = `Votos: ${d.votos}`;
  const b = document.createElement('button');
  b.type = 'button';
  b.textContent = 'Votar';
  b.setAttribute('aria-label', `Votar por ${d.nombre}`);
  b.addEventListener('click', () => votar(d, b, pVotos));
  if (votados.includes(d.id)) marcarVotado(d, b);
  art.append(h2, img, p, pVotos, b);
  return art;
}

const r = await api('../api/disfraces/listar.php');
if (!r.ok) {
  anunciar(r.datos.mensaje || 'No se pudieron cargar los disfraces.', true);
} else if (r.datos.disfraces.length === 0) {
  anunciar('Todavía no hay disfraces cargados.');
} else {
  lista.append(...r.datos.disfraces.map(d => tarjeta(d, r.datos.votados)));
}
