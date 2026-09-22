/**
 * API client module for Library Attendance & QR Gate
 * Uses native fetch (Ponytail minimal dependency principle)
 */

const TOKEN_KEY = 'mtsn32_auth_token';
const USER_KEY = 'mtsn32_auth_user';

export function getAuthToken() {
    return localStorage.getItem(TOKEN_KEY);
}

export function setAuthToken(token, user = null) {
    if (token) {
        localStorage.setItem(TOKEN_KEY, token);
    } else {
        localStorage.removeItem(TOKEN_KEY);
    }

    if (user) {
        localStorage.setItem(USER_KEY, JSON.stringify(user));
    } else if (!token) {
        localStorage.removeItem(USER_KEY);
    }

    if (typeof window !== 'undefined') {
        window.dispatchEvent(new Event('auth-changed'));
    }
}

export function getStoredUser() {
    try {
        const raw = localStorage.getItem(USER_KEY);
        return raw ? JSON.parse(raw) : null;
    } catch {
        return null;
    }
}

export function clearAuth() {
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem(USER_KEY);
    if (typeof window !== 'undefined') {
        window.dispatchEvent(new Event('auth-changed'));
    }
}

/**
 * Base fetch wrapper with auth header and error handling
 */
async function request(endpoint, options = {}) {
    const token = getAuthToken();
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
        ...(options.headers || {}),
    };

    const response = await fetch(endpoint, {
        ...options,
        headers,
    });

    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
        if (response.status === 401 && endpoint !== '/api/v1/auth/login') {
            clearAuth();
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
    const token = getAuthToken();
    const formData = new FormData();
    formData.append('file', file);
    formData.append('default_role', defaultRole);
    formData.append('update_existing', updateExisting ? '1' : '0');

    return fetch('/api/v1/members/import', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
        },
        body: formData,
    }).then(async (response) => {
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            if (response.status === 401) {
                clearAuth();
            }
            const error = new Error(data.message || `Import gagal (Status: ${response.status})`);
            error.status = response.status;
            error.data = data;
            throw error;
        }
        return data;
    });
}

// 4h. Get Member Import Template URL
export function getMemberTemplateUrl() {
    return '/api/v1/members/template';
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
        gradient.addColorStop(0, '#1E3A2B');
        gradient.addColorStop(0.5, '#244A35');
        gradient.addColorStop(1, '#14281D');
        ctx.fillStyle = gradient;
        if (ctx.roundRect) {
            ctx.roundRect(0, 0, cardWidth, cardHeight, 32);
        } else {
            ctx.rect(0, 0, cardWidth, cardHeight);
        }
        ctx.fill();

        // Border
        ctx.strokeStyle = '#3E6F52';
        ctx.lineWidth = 4;
        ctx.stroke();

        // Header Text: Madrasah Branding
        ctx.fillStyle = '#FAF8F5';
        ctx.font = 'bold 36px sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('MTsN 32 JAKARTA', cardWidth / 2, 80);

        ctx.fillStyle = '#8FE2A9';
        ctx.font = 'bold 16px sans-serif';
        ctx.fillText('KARTU ANGGOTA PERPUSTAKAAN DIGITAL', cardWidth / 2, 115);

        // Divider
        ctx.strokeStyle = '#2D583F';
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
        ctx.fillStyle = '#A8CEA9';
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
        ctx.fillStyle = '#D2E8D7';
        ctx.font = '16px sans-serif';
        ctx.fillText('Arahkan QR Code ini ke kamera Kiosk Pintu Gerbang', cardWidth / 2, 880);

        ctx.fillStyle = '#7FD197';
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
    const token = getAuthToken();
    const params = new URLSearchParams();
    Object.entries(filters).forEach(([key, val]) => {
        if (val !== undefined && val !== null && val !== '') {
            params.append(key, val);
        }
    });
    return `/api/v1/visits/export?${params.toString()}`;
}

// 9. Auth helper
export function login(credentials) {
    return request('/api/v1/auth/login', {
        method: 'POST',
        body: JSON.stringify(credentials),
    }).then(res => {
        if (res.token) {
            setAuthToken(res.token, res.user);
        }
        return res;
    });
}

export function logout() {
    return request('/api/v1/auth/logout', {
        method: 'POST',
    }).finally(() => {
        clearAuth();
    });
}

