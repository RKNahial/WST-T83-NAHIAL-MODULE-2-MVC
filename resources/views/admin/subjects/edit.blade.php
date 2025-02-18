@extends('layouts.app')

@section('title', 'Edit Subject')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Subject</h5>

                    <form action="{{ route('admin.subjects.update', $subject->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="subject_name" class="form-label">Subject Name</label>
                            <input type="text" class="form-control @error('subject_name') is-invalid @enderror" 
                                id="subject_name" name="subject_name" value="{{ old('subject_name', $subject->name) }}" required>
                            @error('subject_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject_code" class="form-label">Subject Code</label>
                            <input type="text" class="form-control @error('subject_code') is-invalid @enderror" 
                                id="subject_code" name="subject_code" value="{{ old('subject_code', $subject->code) }}" required>
                            @error('subject_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="units" class="form-label">Units</label>
                            <input type="number" class="form-control @error('units') is-invalid @enderror" 
                                id="units" name="units" value="{{ old('units', $subject->units) }}" required min="1" max="6">
                            @error('units')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-select @error('semester') is-invalid @enderror" 
                                id="semester" name="semester" required>
                                <option value="">Select Semester</option>
                                <option value="1" {{ (old('semester', $subject->semester) == '1') ? 'selected' : '' }}>First Semester</option>
                                <option value="2" {{ (old('semester', $subject->semester) == '2') ? 'selected' : '' }}>Second Semester</option>
                                <option value="3" {{ (old('semester', $subject->semester) == '3') ? 'selected' : '' }}>Summer</option>
                            </select>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Update Subject</button>
                            <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection