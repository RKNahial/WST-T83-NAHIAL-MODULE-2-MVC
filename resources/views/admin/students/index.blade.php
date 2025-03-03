@extends('layouts.app')

@section('title', 'Student')    

@section('content')

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Special case for temp_password - keep this part -->
            @if(session('temp_password'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="mt-2 p-2 bg-light border rounded">
                        <strong>Important!</strong>
                        <div class="text-monospace">{{ session('temp_password') }}</div>
                        <small class="text-muted">Please save this password or share it with the student securely.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1 mt-3 mx-2">
                        <h5 class="card-title mb-0">
                            {{ $currentStatus === 'archived' ? 'Archived' : 'Active' }} Students List
                        </h5>
                        <div class="d-flex gap-2">
                            <form method="GET" action="{{ route('admin.students.index') }}" id="filter-form">
                                <select name="status" class="form-select" style="width: auto;" onchange="this.form.submit()">
                                    <option value="active" {{ $currentStatus === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="archived" {{ $currentStatus === 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </form>
                            <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add New Student
                            </a>
                        </div>
                    </div>

                    <!-- Table with datatable -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Student ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Course</th>
                                <th scope="col">Year Level</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $student->student_id }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->course }}</td>
                                <td>
                                    @switch($student->year_level)
                                        @case(1)
                                            First Year
                                            @break
                                        @case(2)
                                            Second Year
                                            @break
                                        @case(3)
                                            Third Year
                                            @break
                                        @case(4)
                                            Fourth Year
                                            @break
                                        @default
                                            {{ $student->year_level }}
                                    @endswitch
                                </td>
                                <td>
                                    @if($student->is_archived)
                                        <span class="badge bg-secondary">Archived</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.students.archive', $student->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm archive-btn" 
                                                data-student-name="{{ $student->name }}" 
                                                data-is-archived="{{ $student->is_archived }}">
                                            <i class="bi {{ $student->is_archived ? 'bi-arrow-counterclockwise' : 'bi-archive' }}"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    No {{ $currentStatus === 'archived' ? 'archived' : 'active' }} students found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Archive Confirmation Modal -->
<div class="modal fade" id="actionConfirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="confirmation-question mb-3">
                    <h6 id="confirmationMessage" class="text-center"></h6>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmAction">Confirm</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize modal
    const modal = new bootstrap.Modal(document.getElementById('actionConfirmationModal'));
    let currentForm = null;

    // Handle archive/unarchive button clicks
    document.querySelectorAll('.archive-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            currentForm = this.closest('form');
            
            // Get student name and archive status
            const studentName = this.dataset.studentName;
            const isArchived = this.dataset.isArchived === '1';
            
            // Set confirmation message
            const message = isArchived 
                ? `Are you sure you want to unarchive ${studentName}?`
                : `Are you sure you want to archive ${studentName}?`;
            document.getElementById('confirmationMessage').textContent = message;
            
            modal.show();
        });
    });

    // Handle confirmation button click
    document.getElementById('confirmAction').addEventListener('click', function() {
        if (currentForm) {
            currentForm.submit();
        }
        modal.hide();
    });

    // Auto-dismiss success alert
    setTimeout(function() {
        const alert = document.getElementById('successAlert');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 2500);
});
</script>
@endpush

@endsection