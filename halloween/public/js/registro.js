import { enviarJSON, anunciar, iniciarNav } from './api.js';
iniciarNav();
document.getElementById('form').addEventListener('submit', async e => {
  e.preventDefault();
  const f = e.target;
  const r = await enviarJSON('../api/auth/registro.php', { nombre: f.nombre.value, clave: f.clave.value });
  anunciar(r.datos.mensaje || 'Error al registrarse.', !r.ok);
  if (r.ok) setTimeout(() => (location.href = 'login.html'), 1500);
});
