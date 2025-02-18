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

                        <!-- Student Information (Read-only) -->
                        <div class="col-12">
                            <label class="form-label">Student Information</label>
                            <input type="text" class="form-control" 
                                value="{{ $enrollment->student->name }} ({{ $enrollment->student->student_id }})" 
                                readonly disabled>
                            <div class="form-text">Student information cannot be changed in enrollment.</div>
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
                                        {{ (old('subject_id', $enrollment->subject_id) == $subject->id) ? 'selected' : '' }}>
                                        {{ $subject->name }} ({{ $subject->code }}) - {{ $subject->units }} units - 
                                        {{ $subject->semester == 1 ? 'First Semester' : ($subject->semester == 2 ? 'Second Semester' : 'Summer') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <!-- Add hidden semester field -->
                            <input type="hidden" name="semester" id="semester_input" value="{{ old('semester', $enrollment->semester) }}">
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
@endsection