import { enviarJSON, anunciar, iniciarNav } from './api.js';
iniciarNav();
document.getElementById('form').addEventListener('submit', async e => {
  e.preventDefault();
  const f = e.target;
  const r = await enviarJSON('../api/auth/login.php', { nombre: f.nombre.value, clave: f.clave.value });
  anunciar(r.datos.mensaje || 'Error al iniciar sesión.', !r.ok);
  if (r.ok) location.href = r.datos.admin ? 'admin.html' : 'index.html';
});
