@extends('layouts.app')

@section('title', 'Edit Enrollment')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Enrollment</h5>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.enrollments.update', $enrollment->id) }}" method="POST" class="row g-3">
                        @csrf
                        @method('PUT')

                        <!-- Student Input -->
                        <div class="col-12">
                            <label for="student_input" class="form-label">Student ID or Name</label>
                            <input type="text" class="form-control @error('student_input') is-invalid @enderror" 
                                id="student_input" name="student_input" 
                                value="{{ old('student_input', $enrollment->student->student_id . ' - ' . $enrollment->student->name) }}" 
                                placeholder="Enter Student ID or Full Name" required readonly>
                            <div class="form-text">Student information cannot be changed in enrollment. Create a new enrollment if needed.</div>
                            @error('student_input')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subject Selection -->
                        <div class="col-12">
                            <label for="subject_id" class="form-label">Subject</label>
                            <select class="form-select @error('subject_id') is-invalid @enderror" 
                                id="subject_id" name="subject_id" required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" 
                                        {{ (old('subject_id', $enrollment->subject_id) == $subject->id) ? 'selected' : '' }}>
                                        {{ $subject->name }} ({{ $subject->code }}) - {{ $subject->units }} units
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Semester Selection -->
                        <div class="col-md-4">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-select @error('semester') is-invalid @enderror" 
                                id="semester" name="semester" required>
                                <option value="">Select Semester</option>
                                <option value="1" {{ (old('semester', $enrollment->semester) == '1') ? 'selected' : '' }}>First Semester</option>
                                <option value="2" {{ (old('semester', $enrollment->semester) == '2') ? 'selected' : '' }}>Second Semester</option>
                                <option value="3" {{ (old('semester', $enrollment->semester) == '3') ? 'selected' : '' }}>Summer</option>
                            </select>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Academic Year Selection -->
                        <div class="col-md-4">
                            <label for="academic_year" class="form-label">Academic Year</label>
                            <select class="form-select @error('academic_year') is-invalid @enderror" 
                                id="academic_year" name="academic_year" required>
                                <option value="">Select Academic Year</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year }}" 
                                        {{ (old('academic_year', $enrollment->academic_year) == $year) ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Selection -->
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                                <option value="enrolled" {{ (old('status', $enrollment->status) == 'enrolled') ? 'selected' : '' }}>Enrolled</option>
                                <option value="dropped" {{ (old('status', $enrollment->status) == 'dropped') ? 'selected' : '' }}>Dropped</option>
                                <option value="completed" {{ (old('status', $enrollment->status) == 'completed') ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Buttons -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update Enrollment</button>
                            <a href="{{ route('admin.enrollments.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection