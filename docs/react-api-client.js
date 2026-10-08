// Example for React/Vite. Prefer a same-origin /api proxy.
const API_BASE = '/api/v1';
let csrfToken = null;

export async function apiRequest(path, { method = 'GET', body } = {}) {
    const write = ['POST', 'PATCH', 'DELETE'].includes(method);
    if (write && !csrfToken) {
        const response = await fetch(`${API_BASE}/auth/csrf`, { credentials: 'include' });
        if (!response.ok) throw new Error('No se pudo iniciar la sesión.');
        csrfToken = (await response.json()).data.csrf_token;
    }
    const headers = { Accept: 'application/json' };
    if (write) headers['X-CSRF-Token'] = csrfToken;
    let payload = body;
    if (body !== undefined && !(body instanceof FormData)) {
        headers['Content-Type'] = 'application/json';
        payload = JSON.stringify(body);
    }
    const response = await fetch(`${API_BASE}/${path}`, { method, headers, body: payload, credentials: 'include' });
    if (response.status === 204) {
        if (path === 'auth/logout') csrfToken = null;
        return null;
    }
    const result = await response.json();
    if (!response.ok) {
        if (response.status === 403) csrfToken = null;
        const error = new Error(result.message || 'No se pudo completar la operación.');
        error.status = response.status;
        error.errors = result.errors || {};
        throw error;
    }
    if (result.csrf_token) csrfToken = result.csrf_token;
    return result;
}

// await apiRequest('auth/login', { method: 'POST', body: { usuario, password } });
// const { data, meta } = await apiRequest('mascotas?page=1&per_page=20');
// await apiRequest(`mascotas/${id}`, { method: 'PATCH', body: { nombre: 'Luna' } });
// const file = new FormData(); file.append('slot', '1'); file.append('archivo', selectedFile);
// await apiRequest(`consultas/${id}/adjuntos`, { method: 'POST', body: file });
