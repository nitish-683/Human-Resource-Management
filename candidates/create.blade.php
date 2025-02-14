@extends('layouts.master')
@section('content')

    <div class="card border-0 shadow-sm">
        <div class="card-header">
            Create Candidate
        </div>
        <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <!-- Row 1: Name and Email -->
                    <div class="col-md-6 mb-3">
                        <label for="name">Name*</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', isset($candidate) ? $candidate->name : '') }}" required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email">Email*</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', isset($candidate) ? $candidate->email : '') }}" required>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Row 2: Phone and Password -->
                    <div class="col-md-6 mb-3">
                        <label for="phone">Phone*</label>
                        <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', isset($candidate) ? $candidate->phone : '') }}" required>
                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password">Password</label>
                        <input type="password" id="password" autocomplete="off" name="password" class="form-control @error('password') is-invalid @enderror"
                               value="{{ old('password', isset($candidate) ? $candidate->password : '') }}">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Additional Rows for Document Types -->
                    <!-- @foreach($documenttype as $value)
                        @php
                        $safeName = str_replace(' ', '_', $value->name); 
                        @endphp
                        <div class="col-md-6 mb-3">
                            <label for="{{ $safeName }}">{{ $value->name }}</label>
                            <input type="file" id="{{ $safeName }}" name="{{ $safeName }}" class="form-control @error($safeName) is-invalid @enderror">
                            @error($safeName)
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    @endforeach   -->
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Save</button>
                <a class="btn btn-secondary" href="{{ route('admin.candidates.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>

  
@endsection
@section('scripts')
<script>
$(document).ready( function() {
    $('#name').on('keyup', function() {
        $('#password').val($(this).val());
    });
});

    </script>

@endsection
