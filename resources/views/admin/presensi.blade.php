@extends('layouts.admin', ['title' => 'Presensi QR', 'headerTitle' => 'Presensi QR & Anggota Perpustakaan'])

@section('content')
    @php
        $authUser = auth()->user();
        $authPayload = $authUser->only(['id', 'name', 'username', 'role']) + ['is_staff' => $authUser->isStaff()];
    @endphp
    <script>window.__AUTH_USER__ = {{ Illuminate\Support\Js::from($authPayload) }};</script>
    @viteReactRefresh
    @vite(['resources/js/main.jsx'])
    <div id="root"></div>
    <script>
        document.addEventListener('click', (e) => {
            const el = e.target.closest('[data-presensi-nav],[data-presensi-action]');
            if (!el) return;
            e.preventDefault();
            const nav = el.dataset.presensiNav;
            window.dispatchEvent(new CustomEvent('presensi-command', { detail: nav ? { nav } : { action: el.dataset.presensiAction } }));
        });
    </script>
@endsection
