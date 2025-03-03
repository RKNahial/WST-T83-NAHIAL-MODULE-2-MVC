@extends('layouts.app')

@section('title', 'Edit Enrollment')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Enrollment</h5>

                    <form action="{{ route('admin.enrollments.update', $enrollment->id) }}" method="POST" class="row g-3" id="enrollmentForm">
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
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Update Enrollment</button>
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
                <h5 class="modal-title">Confirm Enrollment Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="confirmation-question mb-3">
                    <h6>Would you like to update enrollment for <strong id="studentNameConfirm"></strong>?</h6>
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
                        <div class="col-8" id="confirmYear"></div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Status:</div>
                        <div class="col-8" id="confirmStatus"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmSubmit">Confirm Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#enrollmentForm');
    if (form) {
        const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get student info and form values
            const studentInfo = document.querySelector('input[disabled]').value;
            const subjectSelect = document.getElementById('subject_id');
            const subjectText = subjectSelect.options[subjectSelect.selectedIndex].text;
            const yearSelect = document.getElementById('academic_year');
            const yearText = yearSelect.options[yearSelect.selectedIndex].text;
            const statusSelect = document.getElementById('status');
            const statusText = statusSelect.options[statusSelect.selectedIndex].text;
            
            // Populate modal with form values
            document.getElementById('studentNameConfirm').textContent = studentInfo.split(' (')[0];
            document.getElementById('confirmStudent').textContent = studentInfo;
            document.getElementById('confirmSubject').textContent = subjectText;
            document.getElementById('confirmYear').textContent = yearText;
            document.getElementById('confirmStatus').textContent = statusText;
            
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

    // Function to update hidden semester field
    function updateSemester() {
        var selectedOption = document.querySelector('#subject_id option:selected');
        var semester = selectedOption.dataset.semester;
        document.getElementById('semester_input').value = semester;
    }

    // Update semester when subject changes
    document.getElementById('subject_id').addEventListener('change', updateSemester);

    // Initial update if a subject is already selected
    if (document.getElementById('subject_id').value) {
        updateSemester();
    }
});
</script>
@endpush

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        window.location.href = "{{ route('admin.enrollments.index') }}";
    }, 2500); 
});
</script>
@endif
@endsection