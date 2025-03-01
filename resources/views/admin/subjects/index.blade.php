@extends('layouts.app')

@section('title', 'Subject')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="validation-alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1 mt-3 mx-2">
                        <h5 class="card-title mb-0">Subject List</h5>
                        <div class="d-flex gap-2 align-items-center">
                            <!-- Semester Filter -->
                            <select id="semesterFilter" class="form-select" style="width: auto;">
                                <option value="">All Semesters</option>
                                <option value="First Semester">First Semester</option>
                                <option value="Second Semester">Second Semester</option>
                                <option value="Summer">Summer</option>
                            </select>
                            
                            <!-- Add New Subject Button -->
                            <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add New Subject
                            </a>
                        </div>
                    </div>

                    <!-- No Results Message -->
                    <div id="noResults" class="alert alert-info" style="display: none;">
                        No subjects found for the selected semester.
                    </div>

                    <!-- Table with datatable -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Subject Code</th>
                                <th scope="col">Subject Name</th>
                                <th scope="col">Units</th>
                                <th scope="col">Semester</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects ?? [] as $subject)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $subject->code }}</td>
                                <td>{{ $subject->name }}</td>
                                <td>{{ $subject->units }}</td>
                                <td>@switch($subject->semester)
                                        @case(1)
                                            First Semester
                                            @break
                                        @case(2)
                                            Second Semester
                                            @break
                                        @case(3)
                                            Summer
                                            @break
                                        @default
                                            {{ $subject->semester }}
                                    @endswitch
                                <td>
                                    <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this subject?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Filter function
        function filterRows() {
            const semesterFilter = document.getElementById('semesterFilter').value;

            // Get all rows
            const rows = document.querySelectorAll('.datatable tbody tr');
            let visibleRows = 0;

            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                const semester = cells[4].textContent.trim(); // Adjust index based on your semester column

                const semesterMatch = !semesterFilter || semester === semesterFilter;

                const isVisible = semesterMatch;
                row.classList.toggle('hide', !isVisible);
                
                if (isVisible) {
                    visibleRows++;
                }
            });

            // Show/hide no results message
            const noResultsMessage = document.getElementById('noResults');
            const tableElement = document.querySelector('.datatable');
            
            if (visibleRows === 0) {
                noResultsMessage.style.display = 'block';
                tableElement.style.display = 'none';
            } else {
                noResultsMessage.style.display = 'none';
                tableElement.style.display = 'table';
            }
        }

        // Event listener for filter
        document.getElementById('semesterFilter').addEventListener('change', filterRows);

        // Add CSS for hidden rows
        const style = document.createElement('style');
        style.textContent = '.hide { display: none !important; }';
        document.head.appendChild(style);

        // Auto-hide alerts
        setTimeout(function() {
            $("#success-alert").fadeOut('slow');
            $("#error-alert").fadeOut('slow');
            $("#validation-alert").fadeOut('slow');
        }, 2500);
    });
</script>
@endpush
@endsection