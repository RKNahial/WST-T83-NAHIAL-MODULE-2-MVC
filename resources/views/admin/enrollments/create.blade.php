@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Add New Enrollment</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.enrollments.index') }}">Enrollments</a></li>
            <li class="breadcrumb-item active">Add New</li>
        </ol>
    </nav>
</div>

<!-- Display error messages outside the card -->
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Enrollment Details</h5>
                    
                    <form action="{{ route('admin.enrollments.store') }}" method="POST" class="row g-3">
                        @csrf

                        <!-- Student Input -->
                        <div class="col-12">
                            <label for="student_input" class="form-label">Student ID or Name</label>
                            <input type="text" class="form-control @error('student_input') is-invalid @enderror" 
                                id="student_input" name="student_input" value="{{ old('student_input') }}" 
                                placeholder="Enter Student ID or Full Name" required>
                            <div class="form-text">Enter either student ID or full name. Student must be registered in the system first.</div>
                            @error('student_input')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subject Selection -->
                        <div class="col-md-4">
                            <label for="subject_id" class="form-label">Subject</label>
                            <select class="form-select @error('subject_id') is-invalid @enderror" 
                                id="subject_id" name="subject_id" required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" 
                                        data-semester="{{ $subject->semester }}"
                                        {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }} ({{ $subject->code }}) - {{ $subject->units }} units - 
                                        {{ $subject->semester == 1 ? 'First Semester' : ($subject->semester == 2 ? 'Second Semester' : 'Summer') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <!-- Add hidden semester field -->
                            <input type="hidden" name="semester" id="semester_input" value="{{ old('semester') }}">
                        </div>

                        <div class="col-md-4">
                            <label for="academic_year" class="form-label">Academic Year</label>
                            <select class="form-select @error('academic_year') is-invalid @enderror" 
                                id="academic_year" name="academic_year" required>
                                <option value="">Select Academic Year</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year }}" {{ old('academic_year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                                <option value="enrolled" {{ old('status') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                <option value="dropped" {{ old('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Enroll Student</button>
                            <a href="{{ route('admin.enrollments.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Function to update hidden semester field
    function updateSemester() {
        var selectedOption = $('#subject_id option:selected');
        var semester = selectedOption.data('semester');
        $('#semester_input').val(semester);
    }

    // Update semester when subject changes
    $('#subject_id').change(updateSemester);

    // Initial update if a subject is already selected
    if ($('#subject_id').val()) {
        updateSemester();
    }
});
</script>
@endsection