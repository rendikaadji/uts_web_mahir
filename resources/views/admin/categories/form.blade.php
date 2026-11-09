@extends('layouts.bootstrap', ['title' => $category->exists ? 'Ubah Kategori' : 'Tambah Kategori'])
@section('content')
<h1 class="h4 mb-3">{{ $category->exists ? 'Ubah' : 'Tambah' }} Kategori</h1>
<form method="POST" class="card shadow-sm" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
    @csrf @if ($category->exists) @method('PUT') @endif
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label" for="name">Nama</label>
            <input id="name" name="name" value="{{ old('name', $category->name) }}" class="form-control @error('name') is-invalid @enderror">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection
