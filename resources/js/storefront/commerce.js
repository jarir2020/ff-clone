const csrfToken = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

export async function storefrontFetch(url, options = {}) {
    const headers = {
        Accept: 'application/json',
        ...(options.body ? { 'Content-Type': 'application/json' } : {}),
        ...(options.headers || {}),
    };

    if (options.method && options.method !== 'GET') {
        headers['X-CSRF-TOKEN'] = csrfToken();
    }

    const response = await fetch(url, {
        credentials: 'same-origin',
        ...options,
        headers,
    });

    const payload = await response.json().catch(() => ({}));
    if (!response.ok) {
        const message = payload.message || Object.values(payload.errors || {}).flat()[0] || `Request failed (${response.status})`;
        throw new Error(message);
    }

    return payload;
}

export const formatMoney = (value) => `৳ ${Number(value || 0).toLocaleString('en-US', { maximumFractionDigits: 0 })}`;
