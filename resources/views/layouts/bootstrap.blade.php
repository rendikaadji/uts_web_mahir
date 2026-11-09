<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Data Master' }} - MTsN 32 Jakarta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@2.1.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark" style="background:#0B1B3D">
    <div class="container">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">MTsN 32 Jakarta</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                @if (auth()->user()->role === 'pustakawan')
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">Kategori Buku</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.books.index') }}">Buku</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.librarians.index') }}">Pengguna</a></li>
                @endif
            </ul>
            <span class="navbar-text me-3">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-sm btn-outline-light">Keluar</button></form>
        </div>
    </div>
</nav>
<main class="container py-4">
    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
        @if (session($key))
            <div class="alert alert-{{ $type }} alert-dismissible fade show">{{ session($key) }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
    @endforeach
    @yield('content')
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net@2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@2.1.8/js/dataTables.bootstrap5.min.js"></script>
@stack('scripts')
</body>
</html>
