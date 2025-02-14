@extends('layouts.master')
@section('content')

    <div class="card">
        <div class="card-header">
            Edit Employee
        </div>
        <form action="{{ route("admin.users.update", [$user->id]) }}" method="POST"  enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="name">Name*</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email">Email*</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', isset($user) ? $user->email : '') }}"
                               {{ auth()->user()->hasRole('Employee') ? 'readonly' : '' }}  required>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="phone">Phone*</label>
                        <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', isset($user) ? $user->phone : '') }}" required>
                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               value="{{ old('password', '') }}">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    @if (!auth()->user()->hasRole('Employee'))
                    <div class="col-md-6 mb-3">
                        <label for="role">Role*</label>
                        <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                            <option value="" disabled selected>Select a role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role', $user->roles->first()->id ?? '') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    @endif
                    @foreach($documenttype as $value)
                    @php
                        $safeName = str_replace(' ', '_', $value->name); 
                        $existingDocument = !empty($user->candidate)?$user->candidate->documents()->where('document_type_id', $value->id)->first():null;
                        $documentFile = $existingDocument ? basename($existingDocument->document_path) : null;
                        $documentUrl = $existingDocument ? Storage::disk('s3')->url($existingDocument->document_path) : null;
                    @endphp
                        <div class="col-md-6 mb-3">
                            <label for="{{ $safeName }}">{{ $value->name }}</label>
                            @if($documentFile)
                            <div class="mb-2">
                                <strong>Uploaded File:</strong> 
                                <a href="{{ $documentUrl }}" target="_blank" class="btn btn-link">{{ $documentFile }}</a>
                            </div>
                            @endif
                            <input type="file" id="{{ $safeName }}" name="{{ $safeName }}" class="form-control @error($safeName) is-invalid @enderror">
                            @error($safeName)
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    @endforeach

                    @foreach($questions as $question)
                    @php
                    $answerexist = $question->answers()->where('user_id', $user->id)->first();
                    @endphp
                        <div class="col-md-6 mb-3">
                            <label for="question-{{ $question->id }}" class="block font-bold">
                                {{ $question->question }}
                            </label>
                            <input 
                                type="text" 
                                name="answers[{{ $question->id }}]" 
                                id="question-{{ $question->id }}" 
                                class="form-control @error('answers.' . $question->id) is-invalid @enderror"
                                value="{{ old('answers.' . $question->id, $answerexist->answer ?? '') }}"
                            >
                            @error('answers.' . $question->id)
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Update</button>
                <a class="btn btn-secondary" href="{{ route('admin.users.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection

