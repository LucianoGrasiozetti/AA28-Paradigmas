import { api, anunciar, iniciarNav } from './api.js';

const sesion = await iniciarNav();
if (!sesion.logueado) location.href = 'login.html';

const form = document.getElementById('form');
form.addEventListener('submit', async e => {
  e.preventDefault();
  const r = await api('../api/disfraces/crear.php', { method: 'POST', body: new FormData(form) });
  anunciar(r.datos.mensaje || 'Error al subir el disfraz.', !r.ok);
  if (r.ok) form.reset();
});
