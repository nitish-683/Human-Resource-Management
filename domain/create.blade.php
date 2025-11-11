@extends('layouts.master')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            Create Domain
        </div>
        <form action="{{ route('admin.domain.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="mb-2">
                    <label for="name">Domain Name*</label>
                    <input type="text" id="name" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="status">Status*</label>
                    <select id="status" name="status"
                            class="form-control @error('status') is-invalid @enderror" required>
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Save</button>
                <a class="btn btn-secondary" href="{{ route('admin.domain.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection
