@extends('layouts.app')

@section('title', 'Enrollment')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

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
                    
                    <form action="{{ route('admin.enrollments.store') }}" method="POST" class="row g-3" id="enrollmentForm">
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

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Enrollment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="confirmation-question mb-3">
                    <h6>Would you like to enroll <strong id="studentNameConfirm"></strong>?</h6>
                </div>
                
                <div class="enrollment-details">
                    <div class="row">
                        <div class="col-4 fw-bold">Student:</div>
                        <div class="col-8" id="confirmStudent"></div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Subject:</div>
                        <div class="col-8" id="confirmSubject"></div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Academic Year:</div>
                        <div class="col-8" id="confirmAcademicYear"></div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Status:</div>
                        <div class="col-8" id="confirmStatus"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmSubmit">Enroll</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#enrollmentForm');
    if (form) {
        const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        
        // Your existing semester update code
        function updateSemester() {
            var selectedOption = $('#subject_id option:selected');
            var semester = selectedOption.data('semester');
            $('#semester_input').val(semester);
        }
        $('#subject_id').change(updateSemester);
        if ($('#subject_id').val()) {
            updateSemester();
        }

        // Add form submission handling
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get values for confirmation
            const studentName = form.student_input.value;
            const subjectSelect = form.subject_id;
            const subjectText = subjectSelect.options[subjectSelect.selectedIndex].text;
            const academicYear = form.academic_year.options[form.academic_year.selectedIndex].text;
            const status = form.status.options[form.status.selectedIndex].text;
            
            // Populate modal
            document.getElementById('studentNameConfirm').textContent = studentName;
            document.getElementById('confirmStudent').textContent = studentName;
            document.getElementById('confirmSubject').textContent = subjectText;
            document.getElementById('confirmAcademicYear').textContent = academicYear;
            document.getElementById('confirmStatus').textContent = status;
            
            // Show modal
            confirmationModal.show();
        });
        
        // Handle confirmation
        document.getElementById('confirmSubmit').addEventListener('click', function() {
            confirmationModal.hide();
            form.removeEventListener('submit', arguments.callee);
            form.submit();
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.enrollment-details {
    background-color: #f8f9fa;
    border-radius: 6px;
    padding: 1rem;
}

.enrollment-details .row {
    border-bottom: 1px solid #e9ecef;
    padding: 0.5rem 0;
}

.enrollment-details .row:last-child {
    border-bottom: none;
}

.confirmation-question {
    text-align: center;
}

.confirmation-question h6 {
    color: #012970;
    font-weight: 600;
}
</style>
@endpush