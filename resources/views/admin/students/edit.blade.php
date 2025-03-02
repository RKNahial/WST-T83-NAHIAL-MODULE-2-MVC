@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-6"> 
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Student</h5>

                    <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $student->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input type="text" class="form-control @error('student_id') is-invalid @enderror" 
                                id="student_id" name="student_id" value="{{ old('student_id', $student->student_id) }}" required>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email', $student->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="course" class="form-label">Course</label>
                            <input type="text" class="form-control @error('course') is-invalid @enderror" 
                                id="course" name="course" value="{{ old('course', $student->course) }}" required>
                            @error('course')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="year_level" class="form-label">Year Level</label>
                            <select class="form-select @error('year_level') is-invalid @enderror" 
                                id="year_level" name="year_level" required>
                                <option value="">Select Year Level</option>
                                @for($i = 1; $i <= 4; $i++)
                                    <option value="{{ $i }}" {{ (old('year_level', $student->year_level) == $i) ? 'selected' : '' }}>
                                        {{ $i }}th Year
                                    </option>
                                @endfor
                            </select>
                            @error('year_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Update Student</button>
                            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        window.location.href = "{{ route('admin.students.index') }}";
    }, 2500); 
});
</script>
@endif
@endsection