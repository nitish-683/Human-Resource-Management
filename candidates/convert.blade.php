@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">
            Convert Candidate to Employee
        </div>
        <div class="card-body"> 
            <form method="POST" action="{{ route('admin.candidates.convert.submit', $candidate->id) }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Employee Email</label>
                    <input type="email" name="email" id="email" class="form-control" 
                        value="{{ old('email') }}" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="employee_code" class="form-label">Employee Code</label>
                    <input type="text" name="employee_code" id="employee_code" class="form-control" 
                        value="{{ old('employee_code') }}" required>
                    @error('employee_code')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="joining_date" class="form-label">Joining Date</label>
                    <input type="date" name="joining_date" id="joining_date" class="form-control" 
                        value="{{ old('joining_date') }}" required>
                    @error('joining_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Convert to Employee</button>
            </form>
        </div>
    </div>
@endsection
