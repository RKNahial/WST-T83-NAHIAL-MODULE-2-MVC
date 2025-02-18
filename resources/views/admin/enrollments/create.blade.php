@extends('layouts.app')

@section('title', 'Enroll Student')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">New Enrollment</h5>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.enrollments.store') }}" method="POST" class="row g-3">
                        @csrf

                        <!-- Student Selection -->
                        <div class="col-12">
                            <label for="student_id" class="form-label">Student ID or Name</label>
                            <select class="form-select @error('student_id') is-invalid @enderror" 
                                id="student_id" name="student_id" required>
                                <option value="">Type Student ID or Name...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" data-student-id="{{ $student->student_id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->student_id }} - {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
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
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
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
                                <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>First Semester</option>
                                <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Second Semester</option>
                                <option value="3" {{ old('semester') == '3' ? 'selected' : '' }}>Summer</option>
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
                                    <option value="{{ $year }}" {{ old('academic_year') == $year ? 'selected' : '' }}>
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
                                <option value="enrolled" {{ old('status') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                <option value="dropped" {{ old('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Buttons -->
                        <div class="text-end">
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
    // Initialize Select2 for student selection with search
    $(document).ready(function() {
        $('#student_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Type Student ID or Name...',
            allowClear: true,
            width: '100%',
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('admin.students.search') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(student) {
                            return {
                                id: student.id,
                                text: student.student_id + ' - ' + student.name
                            };
                        })
                    };
                },
                cache: true
            }
        });

        // Initialize Select2 for subject selection
        $('#subject_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select Subject'
        });
    });
</script>
@endsection