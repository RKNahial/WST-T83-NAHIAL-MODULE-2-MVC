@extends('layouts.app')

@section('title', 'Add New Subject')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add New Subject</h5>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.subjects.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="subject_name" class="form-label">Subject Name</label>
                            <input type="text" class="form-control @error('subject_name') is-invalid @enderror" 
                                id="subject_name" name="subject_name" value="{{ old('subject_name') }}" required>
                            @error('subject_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject_code" class="form-label">Subject Code</label>
                            <input type="text" class="form-control @error('subject_code') is-invalid @enderror" 
                                id="subject_code" name="subject_code" value="{{ old('subject_code') }}" required>
                            @error('subject_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="units" class="form-label">Units</label>
                            <input type="number" class="form-control @error('units') is-invalid @enderror" 
                                id="units" name="units" value="{{ old('units') }}" required min="1" max="6">
                            @error('units')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-select @error('semester') is-invalid @enderror" 
                                id="semester" name="semester" required>
                                <option value="">Select Semester</option>
                                <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>First Semester</option>
                                <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Second Semester</option>
                                <option value="3" {{ old('semester') == '3' ? 'selected' : '' }}>Summer</option>
                            </select>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Save Subject</button>
                            <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection