@extends('layouts.bootstrap', ['title' => 'Kategori Buku'])
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Kategori Buku</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ Tambah Kategori</a>
</div>
<div class="card shadow-sm"><div class="card-body">
    <table id="tabel" class="table table-striped align-middle w-100">
        <thead><tr><th>Nama</th><th>Deskripsi</th><th>Jumlah Buku</th><th class="no-sort text-end">Aksi</th></tr></thead>
        <tbody>
        @foreach ($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description }}</td>
                <td>{{ $category->books_count }}</td>
                <td class="text-end text-nowrap">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">Ubah</a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div></div>
@endsection
@push('scripts')
<script>
    new DataTable('#tabel', {
        pageLength: 10,
        columnDefs: [{ targets: 'no-sort', orderable: false, searchable: false }],
        language: { search: 'Cari:', lengthMenu: 'Tampil _MENU_ data', info: '_START_–_END_ dari _TOTAL_', infoEmpty: 'Tidak ada data', zeroRecords: 'Data tidak ditemukan', paginate: { previous: '‹', next: '›' } },
    });
</script>
@endpush
