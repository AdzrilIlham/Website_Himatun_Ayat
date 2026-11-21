const allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];

window.uploadCloudinaryImage = async (file, authorizationUrl, scope = {}) => {
    if (!file || !allowedMimeTypes.includes(file.type)) {
        throw new Error('Pilih gambar JPG, PNG, atau WebP.');
    }

    const authorization = await fetch(authorizationUrl, {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ ...scope, bytes: file.size, mime: file.type }),
    });

    if (!authorization.ok) {
        const error = await authorization.json().catch(() => ({}));
        const validationMessage = Object.values(error.errors ?? {}).flat()[0];
        throw new Error(validationMessage || (authorization.status === 403
            ? 'Izin unggah sudah berakhir. Muat ulang halaman atau mulai pembayaran baru.'
            : 'Izin unggah tidak tersedia. Coba lagi.'));
    }

    const { id, url, parameters } = await authorization.json();
    const body = new FormData();
    for (const [key, value] of Object.entries(parameters)) {
        body.append(key, value);
    }
    body.append('file', file);

    const upload = await fetch(url, { method: 'POST', body, credentials: 'omit' });
    if (upload.status === 403) {
        throw new Error('Cloudinary menolak izin unggah. Hubungi pengelola situs.');
    }
    if (!upload.ok) {
        throw new Error('Cloudinary gagal mengunggah gambar. Coba lagi.');
    }

    const asset = await upload.json();
    if (asset.public_id !== parameters.public_id || asset.resource_type !== 'image' || asset.type !== parameters.type
        || typeof asset.secure_url !== 'string' || !asset.secure_url.startsWith('https://res.cloudinary.com/')) {
        throw new Error('Cloudinary mengembalikan referensi gambar yang tidak sesuai.');
    }

    return { reference: { id, public_id: asset.public_id }, url: asset.secure_url };
};

document.addEventListener('submit', async (event) => {
    const form = event.target;
    if (event.defaultPrevented || !form.matches('form[data-image-authorization]')) return;
    if (form.dataset.uploading === 'true') {
        event.preventDefault();
        return;
    }

    const input = form.querySelector('[data-image-field]');
    const file = input?.files[0];
    if (!file) return;

    event.preventDefault();
    form.dataset.uploading = 'true';
    const submitter = event.submitter;
    const status = form.querySelector('[data-image-upload-status]');
    status.textContent = 'Mengunggah gambar…';
    if (submitter) submitter.disabled = true;
    input.disabled = true;

    try {
        const field = input.dataset.imageField;
        const { reference } = await window.uploadCloudinaryImage(file, form.dataset.imageAuthorization, {
            purpose: field,
            record_id: form.dataset.imageRecord || null,
        });
        for (const [key, value] of Object.entries(reference)) {
            const name = `${field}[${key}]`;
            const hidden = form.elements.namedItem(name) || document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = name;
            hidden.value = value;
            if (!hidden.form) form.append(hidden);
        }
        HTMLFormElement.prototype.submit.call(form);
    } catch (error) {
        status.textContent = error instanceof TypeError ? 'Koneksi unggah terputus. Coba lagi.' : error.message;
        if (submitter) submitter.disabled = false;
        input.disabled = false;
        delete form.dataset.uploading;
    }
});
