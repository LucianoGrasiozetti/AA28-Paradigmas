export async function api(url, opciones = {}) {
  try {
    const r = await fetch(url, { credentials: 'same-origin', ...opciones });
    const datos = await r.json().catch(() => ({}));
    return { ok: r.ok, status: r.status, datos };
  } catch {
    return { ok: false, status: 0, datos: { mensaje: 'No se pudo conectar con el servidor.' } };
  }
}
export const enviarJSON = (url, cuerpo) =>
  api(url, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(cuerpo) });

export function anunciar(texto, esError = false) {
  const e = document.getElementById('estado');
  e.textContent = texto;
  e.classList.toggle('error', esError);
}

export async function iniciarNav() {
  const { datos: s } = await api('../api/auth/sesion.php');
  const nav = document.getElementById('nav');
  nav.replaceChildren();
  const enlace = (href, texto) => {
    const a = document.createElement('a');
    a.href = href;
    a.textContent = texto;
    nav.append(a);
  };
  enlace('index.html', 'Inicio');
  if (s.logueado) {
    enlace('subir.html', 'Subir mi disfraz');
    if (s.admin) enlace('admin.html', 'Administración');
    const b = document.createElement('button');
    b.type = 'button';
    b.textContent = `Cerrar sesión (${s.nombre})`;
    b.addEventListener('click', async () => {
      await enviarJSON('../api/auth/logout.php', {});
      location.href = 'index.html';
    });
    nav.append(b);
  } else {
    enlace('login.html', 'Iniciar sesión');
    enlace('registro.html', 'Registrarse');
  }
  return s;
}
