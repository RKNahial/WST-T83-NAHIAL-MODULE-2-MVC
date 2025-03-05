@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-6"> 
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Student</h5>

                    <form action="{{ route('admin.students.update', $student->id) }}" method="POST" id="studentForm">
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
                                        {{ $i }}{{ $i === 1 ? 'st' : ($i === 2 ? 'nd' : ($i === 3 ? 'rd' : 'th')) }} Year
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

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Student Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="confirmation-question mb-3">
                    <h6>Would you like to update <strong id="studentNameConfirm"></strong>'s information?</h6>
                </div>
                
                <div class="student-details">
                    <div class="row">
                        <div class="col-4 fw-bold">Full Name:</div>
                        <div class="col-8" id="confirmName"></div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Student ID:</div>
                        <div class="col-8" id="confirmStudentId"></div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Email:</div>
                        <div class="col-8" id="confirmEmail"></div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Course:</div>
                        <div class="col-8" id="confirmCourse"></div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold">Year Level:</div>
                        <div class="col-8" id="confirmYearLevel"></div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#studentForm');
    if (form) {
        const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get student name for the question
            const studentName = form.name.value;
            
            // Populate modal with form values
            document.getElementById('studentNameConfirm').textContent = studentName;
            document.getElementById('confirmName').textContent = studentName;
            document.getElementById('confirmStudentId').textContent = form.student_id.value;
            document.getElementById('confirmEmail').textContent = form.email.value;
            document.getElementById('confirmCourse').textContent = form.course.value;
            document.getElementById('confirmYearLevel').textContent = 
                form.year_level.options[form.year_level.selectedIndex].text;
            
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
.student-details {
    background-color: #f8f9fa;
    border-radius: 6px;
    padding: 1rem;
}

.student-details .row {
    border-bottom: 1px solid #e9ecef;
    padding: 0.5rem 0;
}

.student-details .row:last-child {
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