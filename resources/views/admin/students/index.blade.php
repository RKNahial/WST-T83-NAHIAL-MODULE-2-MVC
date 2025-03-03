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
                                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to {{ $student->is_archived ? 'restore' : 'archive' }} this student?')">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss success alert
    setTimeout(function() {
        const alert = document.getElementById('successAlert');
        if (alert) {
            // Add fade out effect
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            
            // Remove the element after fade out
            setTimeout(function() {
                alert.remove();
            }, 500);
        }
    }, 2500);

    // Add status filter functionality
    const statusFilter = document.getElementById('status-filter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            const baseUrl = '{{ route('admin.students.index') }}';
            const status = this.value;
            
            if (status === 'active') {
                window.location.href = baseUrl;
            } else {
                window.location.href = `${baseUrl}?status=${status}`;
            }
        });
    }
});
</script>
@endpush

@endsection