@extends('layouts.master')
@section('content')

    <div class="card">
        <div class="card-header">
            Edit Document type
        </div>
        <form action="{{ route("admin.documenttypes.update", $documenttype) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="mb-2">
                    <label for="title">Title*</label>
                    <input type="text" id="title" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', isset($documenttype) ? $documenttype->name : '') }}" required>
                    @error('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="required">Required*</label>
                    <select id="required" name="required" class="form-control @error('required') is-invalid @enderror" required>
                            <option value="1" {{$documenttype->required == 1?'selected':''}}>Yes</option>
                            <option value="0" {{$documenttype->required == 0?'selected':''}}>No</option>
                    </select>
                    @error('required')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="document_format">Document Format*</label>
                    <select id="document_format" name="document_format" class="form-control @error('document_format') is-invalid @enderror" required>
                        <option value="1" {{ $documenttype->document_format == 1 ? 'selected' : '' }}>PDF</option>
                        <option value="2" {{ $documenttype->document_format == 2 ? 'selected' : '' }}>Image</option>
                    </select>
                    @error('document_format')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Update</button>
                <a class="btn btn-secondary" href="{{ route('admin.documenttypes.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection

