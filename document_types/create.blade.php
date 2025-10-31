@extends('layouts.master')
@section('content')

    <div class="card border-0 shadow-sm">
        <div class="card-header">
            Create Document Type
        </div>
        <form action="{{ route("admin.documenttypes.store") }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="mb-2">
                    <label for="title">Name*</label>
                    <input type="text" id="title" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', isset($documenttype) ? $documenttype->name : '') }}" required>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <input type="hidden" name="required" value="0">
                </div>
                <div class="mb-2">
                    <label for="required">Required*</label>
                    <select id="required" name="required" class="form-control @error('required') is-invalid @enderror" required>
                            <option value="1" selected>Yes</option>
                            <option value="0">No</option>
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
                    <option value="" disabled {{ old('document_format') === null ? 'selected' : '' }}>Select an option</option>    
                    <option value="1" {{ old('document_format') == 1 ? 'selected' : '' }}>PDF</option>
                    <option value="2" {{ old('document_format') == 2 ? 'selected' : '' }}>Image</option>
                </select>
            @error('document_format')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
            </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Save</button>
                <a class="btn btn-secondary" href="{{ route('admin.documenttypes.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection

