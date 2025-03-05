@extends('layouts.app')

@section('title', 'Edit Subject')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Subject</h5>

                    <form action="{{ route('admin.subjects.update', $subject->id) }}" method="POST" id="subjectForm">
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

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Editing Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="confirmation-question mb-3">
                    <h6>Would you like to update <strong id="subjectNameConfirm"></strong>?</h6>
                </div>
                
                <div class="subject-details">
                    <div class="row">
                        <div class="col-4 fw-bold">Subject Name:</div>
                        <div class="col-8" id="confirmName"></div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Subject Code:</div>
                        <div class="col-8" id="confirmCode"></div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Units:</div>
                        <div class="col-8" id="confirmUnits"></div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Semester:</div>
                        <div class="col-8" id="confirmSemester"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmSubmit">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#subjectForm');
    if (form) {
        const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get subject name for the question
            const subjectName = form.subject_name.value;
            
            // Populate modal with form values
            document.getElementById('subjectNameConfirm').textContent = subjectName;
            document.getElementById('confirmName').textContent = subjectName;
            document.getElementById('confirmCode').textContent = form.subject_code.value;
            document.getElementById('confirmUnits').textContent = form.units.value;
            document.getElementById('confirmSemester').textContent = 
                form.semester.options[form.semester.selectedIndex].text;
            
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
.subject-details {
    background-color: #f8f9fa;
    border-radius: 6px;
    padding: 1rem;
}

.subject-details .row {
    border-bottom: 1px solid #e9ecef;
    padding: 0.5rem 0;
}

.subject-details .row:last-child {
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
@endsection