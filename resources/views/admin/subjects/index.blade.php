@extends('layouts.app')

@section('title', 'Subject')

@section('content')
<section class="section">
    <!-- Move error messages here, outside the card -->
    @if(!isset($displayedMessages))
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @php($displayedMessages = true)
    @endif

    <div class="row">
        <div class="col-lg-12">
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
                                        <button type="submit" class="btn btn-danger btn-sm delete-btn" 
                                                data-subject-name="{{ $subject->name }}" 
                                                data-subject-code="{{ $subject->code }}">
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">  
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="confirmation-question mb-3">
                    <h6 id="deleteConfirmationMessage" class="text-center"></h6>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Store the original table HTML for reset purposes
        const originalTableHTML = document.querySelector('.datatable tbody').innerHTML;
        
        // Initialize DataTable with specific configuration
        const datatable = new simpleDatatables.DataTable(".datatable", {
            perPageSelect: false,
            searchable: false,
            paging: true,
            footer: false,
            labels: {
                noRows: `<div class="alert alert-info mb-0">No records found for the selected filters.</div>`
            }
        });

        const semesterFilter = document.getElementById('semesterFilter');

        function filterTable() {
            const semesterValue = semesterFilter.value;

            // Reset table to original state first
            const tableBody = document.querySelector('.datatable tbody');
            tableBody.innerHTML = originalTableHTML;

            // Get all rows from the table
            const rows = Array.from(document.querySelectorAll('.datatable tbody tr'));
            let visibleCount = 0;

            rows.forEach(row => {
                // Skip if it's a message row
                if (row.querySelector('td[colspan]')) return;

                const semesterCell = row.cells[4]?.textContent.trim(); // Adjust index based on your semester column

                const semesterMatch = !semesterValue || semesterCell === semesterValue;

                if (semesterMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                const noResultsRow = document.createElement('tr');
                noResultsRow.innerHTML = `
                    <td colspan="6" class="text-center">
                        <div class="alert alert-info mb-0">
                            No subjects found for the selected semester.
                        </div>
                    </td>
                `;
                tableBody.innerHTML = '';
                tableBody.appendChild(noResultsRow);
            }

            // Force DataTable to update
            datatable.refresh();
        }

        // Add filter event listener
        if (semesterFilter) {
            semesterFilter.addEventListener('change', filterTable);
        }
    });

    // Delete confirmation modal handling
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        let deleteForm = null;

        // Handle delete button clicks
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                deleteForm = this.closest('form');
                
                const subjectName = this.dataset.subjectName;
                const subjectCode = this.dataset.subjectCode;
                
                // Set confirmation message
                const message = `Are you sure you want to delete ${subjectName} (${subjectCode})?`;
                document.getElementById('deleteConfirmationMessage').textContent = message;
                
                deleteModal.show();
            });
        });

        // Handle confirmation button click
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteForm) {
                deleteForm.submit();
            }
            deleteModal.hide();
        });
    });
</script>
@endpush
@endsection