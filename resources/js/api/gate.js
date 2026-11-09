/**
 * API client module for Library Attendance & QR Gate
 * Uses native fetch (Ponytail minimal dependency principle)
 */

/**
 * Sesi login dikelola Laravel (Blade). Pengguna diberikan lewat window.__AUTH_USER__
 * oleh resources/views/app.blade.php; SPA ini tidak memiliki login sendiri.
 */
export function getStoredUser() {
    return window.__AUTH_USER__ || null;
}

export function getAuthToken() {
    return getStoredUser() ? 'session' : null;
}

export function clearAuth() {
    window.__AUTH_USER__ = null;
    window.dispatchEvent(new Event('auth-changed'));
}

function getCookie(name) {
    if (typeof document === 'undefined') return null;
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) {
        return decodeURIComponent(parts.pop().split(';').shift());
    }
    return null;
}

function csrfHeaders() {
    const token = document.querySelector('meta[name="csrf-token"]')?.content || (typeof window !== 'undefined' ? window.__CSRF_TOKEN__ : null);
    if (token) {
        return { 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' };
    }
    const xsrfCookie = getCookie('XSRF-TOKEN');
    if (xsrfCookie) {
        return { 'X-XSRF-TOKEN': xsrfCookie, 'X-Requested-With': 'XMLHttpRequest' };
    }
    return { 'X-Requested-With': 'XMLHttpRequest' };
}

/**
 * Base fetch wrapper with auth header and error handling
 */
async function request(endpoint, options = {}) {
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        ...csrfHeaders(),
        ...(options.headers || {}),
    };

    const response = await fetch(endpoint, {
        ...options,
        credentials: 'same-origin',
        headers,
    });

    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
        if (response.status === 401) {
            clearAuth();
        }
        if (response.status === 419) {
            throw new Error('Sesi keamanan Anda telah kedaluwarsa (CSRF token mismatch). Silakan muat ulang (refresh) halaman lalu coba lagi.');
        }
        const error = new Error(data.message || `Request failed with status ${response.status}`);
        error.status = response.status;
        error.data = data;
        throw error;
    }

    return data;
}

// 1. Gate Scan
export function scanQr(token, deviceNote = 'Kiosk Web') {
    return request('/api/v1/gate/scan', {
        method: 'POST',
        body: JSON.stringify({ token, device_note: deviceNote }),
    });
}

// 2. Guest Check-in
export function submitGuest(guestData) {
    return request('/api/v1/gate/guest', {
        method: 'POST',
        body: JSON.stringify(guestData),
    });
}

// 3. Manual Check-in (Staff only)
export function manualCheckin(manualData) {
    return request('/api/v1/gate/manual', {
        method: 'POST',
        body: JSON.stringify(manualData),
    });
}

// 4. Digital QR for logged-in member
export function fetchMyQr() {
    return request('/api/v1/qr/me');
}

// 4b. Digital QR for specific user (staff only)
export function fetchUserQr(userId) {
    return request(`/api/v1/qr/user/${userId}`);
}

// 4c. Registered members list (staff only)
export function fetchMembers(filters = {}) {
    const params = new URLSearchParams();
    Object.entries(filters).forEach(([key, val]) => {
        if (val !== undefined && val !== null && val !== '') {
            params.append(key, val);
        }
    });
    const queryString = params.toString();
    return request(`/api/v1/members${queryString ? '?' + queryString : ''}`);
}

// 4d. Create new student or teacher member (staff only)
export function createMember(memberData) {
    return request('/api/v1/members', {
        method: 'POST',
        body: JSON.stringify(memberData),
    });
}

// 4e. Update existing member (staff only)
export function updateMember(userId, memberData) {
    return request(`/api/v1/members/${userId}`, {
        method: 'PUT',
        body: JSON.stringify(memberData),
    });
}

// 4f. Delete existing member (staff only)
export function deleteMember(userId) {
    return request(`/api/v1/members/${userId}`, {
        method: 'DELETE',
    });
}

// 4g. Import members from Excel / CSV (staff only)
export function importMembers(file, defaultRole = 'siswa', updateExisting = false) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('default_role', defaultRole);
    formData.append('update_existing', updateExisting ? '1' : '0');

    const metaToken = document.querySelector('meta[name="csrf-token"]')?.content || (typeof window !== 'undefined' ? window.__CSRF_TOKEN__ : null);
    if (metaToken) {
        formData.append('_token', metaToken);
    }

    return fetch('/api/v1/members/import', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            ...csrfHeaders(),
        },
        body: formData,
    }).then(async (response) => {
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            if (response.status === 401) {
                clearAuth();
            }
            if (response.status === 419) {
                throw new Error('Sesi keamanan Anda telah kedaluwarsa (CSRF token mismatch). Silakan muat ulang (refresh) halaman lalu coba unggah kembali.');
            }
            const error = new Error(data.message || `Import gagal (Status: ${response.status})`);
            error.status = response.status;
            error.data = data;
            throw error;
        }
        return data;
    });
}

// 4h. Download authenticated file (agar galat unduhan bisa ditampilkan)
export async function downloadAuthFile(endpoint, filename) {
    const response = await fetch(endpoint, { credentials: 'same-origin' });

    if (!response.ok) {
        if (response.status === 401) {
            clearAuth();
        }
        throw new Error(`Gagal mengunduh file (Status: ${response.status})`);
    }

    const url = URL.createObjectURL(await response.blob());
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    link.click();
    URL.revokeObjectURL(url);
}

export function downloadMemberTemplate() {
    return downloadAuthFile('/api/v1/members/template', 'template_import_anggota_mtsn32.xlsx');
}

export function downloadMembersQrZip() {
    return downloadAuthFile('/api/v1/members/export-qr-zip', 'data_anggota.zip');
}

export function downloadStudentsQrZip() {
    return downloadMembersQrZip();
}


// 5. Regenerate QR token
export function regenerateQr(userId = null) {
    return request('/api/v1/qr/generate', {
        method: 'POST',
        body: JSON.stringify(userId ? { user_id: userId } : {}),
    });
}

// 5b. Download QR as SVG file
export function downloadQrSvg(svgString, filename = 'kartu-qr') {
    const blob = new Blob([svgString], { type: 'image/svg+xml;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `${filename}.svg`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

// 5c. Download QR as high-resolution PNG image with official card layout
export function downloadQrPng(svgString, filename = 'kartu-qr', memberName = '', memberId = '', role = 'Anggota') {
    const img = new Image();
    const svgBlob = new Blob([svgString], { type: 'image/svg+xml;charset=utf-8' });
    const url = URL.createObjectURL(svgBlob);

    img.onload = () => {
        const canvas = document.createElement('canvas');
        const cardWidth = 800;
        const cardHeight = 1050;
        canvas.width = cardWidth;
        canvas.height = cardHeight;
        const ctx = canvas.getContext('2d');

        // Background Luxury Botanical Emerald
        const gradient = ctx.createLinearGradient(0, 0, cardWidth, cardHeight);
        gradient.addColorStop(0, '#0B1B3D');
        gradient.addColorStop(0.5, '#11295D');
        gradient.addColorStop(1, '#07132B');
        ctx.fillStyle = gradient;
        if (ctx.roundRect) {
            ctx.roundRect(0, 0, cardWidth, cardHeight, 32);
        } else {
            ctx.rect(0, 0, cardWidth, cardHeight);
        }
        ctx.fill();

        // Border
        ctx.strokeStyle = '#2A6283';
        ctx.lineWidth = 4;
        ctx.stroke();

        // Header Text: Madrasah Branding
        ctx.fillStyle = '#FAF8F5';
        ctx.font = 'bold 36px sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('MTsN 32 JAKARTA', cardWidth / 2, 80);

        ctx.fillStyle = '#38BDF8';
        ctx.font = 'bold 16px sans-serif';
        ctx.fillText('KARTU ANGGOTA PERPUSTAKAAN DIGITAL', cardWidth / 2, 115);

        // Divider
        ctx.strokeStyle = '#18336E';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(80, 140);
        ctx.lineTo(cardWidth - 80, 140);
        ctx.stroke();

        // Member Name
        ctx.fillStyle = '#FFFFFF';
        ctx.font = 'bold 32px sans-serif';
        ctx.fillText(memberName || 'Nama Anggota', cardWidth / 2, 195);

        // Member ID & Role Badge
        ctx.fillStyle = '#99C4DD';
        ctx.font = '20px monospace';
        ctx.fillText(`ID / NIS: ${memberId || '-'}  •  ${role}`, cardWidth / 2, 230);

        // White Box Container for QR (high contrast for optical scanning)
        const qrBoxSize = 560;
        const qrBoxX = (cardWidth - qrBoxSize) / 2;
        const qrBoxY = 270;
        ctx.fillStyle = '#FFFFFF';
        if (ctx.roundRect) {
            ctx.roundRect(qrBoxX, qrBoxY, qrBoxSize, qrBoxSize, 24);
        } else {
            ctx.fillRect(qrBoxX, qrBoxY, qrBoxSize, qrBoxSize);
        }
        ctx.fill();

        // Draw the QR Code image inside the white plaque
        const qrPadding = 30;
        ctx.drawImage(img, qrBoxX + qrPadding, qrBoxY + qrPadding, qrBoxSize - (qrPadding * 2), qrBoxSize - (qrPadding * 2));

        // Footer Guidance
        ctx.fillStyle = '#C9E2F1';
        ctx.font = '16px sans-serif';
        ctx.fillText('Arahkan QR Code ini ke kamera Kiosk Pintu Gerbang', cardWidth / 2, 880);

        ctx.fillStyle = '#01ABE8';
        ctx.font = 'bold 14px sans-serif';
        ctx.fillText('✓ QR CODE PERMANEN & RESMI TERVERIFIKASI', cardWidth / 2, 915);

        // Trigger download
        const pngUrl = canvas.toDataURL('image/png');
        const a = document.createElement('a');
        a.href = pngUrl;
        a.download = `${filename}.png`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    };

    img.src = url;
}

// 6. Visits Log (Staff only)
export function fetchVisits(filters = {}) {
    const params = new URLSearchParams();
    Object.entries(filters).forEach(([key, val]) => {
        if (val !== undefined && val !== null && val !== '') {
            params.append(key, val);
        }
    });
    const queryString = params.toString();
    return request(`/api/v1/visits${queryString ? '?' + queryString : ''}`);
}

// 7. Visits Stats (Staff only)
export function fetchVisitStats() {
    return request('/api/v1/visits/stats');
}

// 8. Export CSV URL
export function getExportCsvUrl(filters = {}) {
    const params = new URLSearchParams();
    Object.entries(filters).forEach(([key, val]) => {
        if (val !== undefined && val !== null && val !== '') {
            params.append(key, val);
        }
    });
    return `/api/v1/visits/export?${params.toString()}`;
}

// 9. Logout lewat rute Blade (sesi web)
export function logout() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/logout';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = '_token';
    input.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
    return new Promise(() => {});
}
