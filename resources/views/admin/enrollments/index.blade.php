@extends('layouts.app')

@section('title', 'Enrollment')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1 mt-3 mx-2">
                        <h5 class="card-title mb-0">Enrollment List</h5>
                        <div class="d-flex gap-2 align-items-center">
                            
                          <!-- Academic Year Filter -->
                          <select id="yearFilter" class="form-select" style="width: auto;">
                                <option value="">All Years</option>
                                @php
                                    $currentYear = 2024;  
                                    $endYear = $currentYear + 2;  
                                @endphp
                                @for($year = $currentYear; $year <= $endYear; $year++)
                                    <option value="{{ $year }}-{{ $year + 1 }}">{{ $year }}-{{ $year + 1 }}</option>
                                @endfor
                            </select>
                            
                        <!-- Semester Filter -->
                            <select id="semesterFilter" class="form-select" style="width: auto;">
                                <option value="">All Semesters</option>
                                <option value="First Semester">First Semester</option>
                                <option value="Second Semester">Second Semester</option>
                                <option value="Summer">Summer</option>
                            </select>

                            <!-- Enroll Student Button -->
                            <a href="{{ route('admin.enrollments.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Enroll Student
                            </a>
                        </div>
                    </div>

                    <!-- No Results Message -->
                    <div id="noResults" class="alert alert-info" style="display: none;">
                        No enrollments found for the selected filters.
                    </div>

                    <!-- Table with datatable -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Student</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Semester</th>
                                <th scope="col">Academic Year</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments as $enrollment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $enrollment->student->name }}</td>
                                <td>{{ $enrollment->subject->name }} ({{ $enrollment->subject->code }})</td>
                                <td>
                                    @switch($enrollment->semester)
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
                                            {{ $enrollment->semester }}
                                    @endswitch
                                </td>
                                <td>{{ $enrollment->academic_year }}</td>
                                <td>
                                    @if($enrollment->status == 'enrolled')
                                        <span class="badge bg-success">Enrolled</span>
                                    @elseif($enrollment->status == 'dropped')
                                        <span class="badge bg-danger">Dropped</span>
                                    @else
                                        <span class="badge bg-info">Completed</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.enrollments.edit', $enrollment->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.enrollments.destroy', $enrollment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this enrollment?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="alert alert-info mb-0" role="alert">
                                        No enrollments found for the selected filters.
                                    </div>
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

        const yearFilter = document.getElementById('yearFilter');
        const semesterFilter = document.getElementById('semesterFilter');

        function filterTable() {
            const yearValue = yearFilter.value;
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

                const yearCell = row.cells[4]?.textContent.trim(); // Academic Year column
                const semesterCell = row.cells[3]?.textContent.trim(); // Semester column

                const yearMatch = !yearValue || yearCell === yearValue;
                const semesterMatch = !semesterValue || semesterCell === semesterValue;

                if (yearMatch && semesterMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                const noResultsRow = document.createElement('tr');
                noResultsRow.innerHTML = `
                    <td colspan="7" class="text-center">
                        <div class="alert alert-info mb-0">
                            No enrollments found for the selected filters.
                        </div>
                    </td>
                `;
                tableBody.innerHTML = '';
                tableBody.appendChild(noResultsRow);
            }

            // Force DataTable to update
            datatable.refresh();
        }

        // Add filter event listeners
        if (yearFilter && semesterFilter) {
            yearFilter.addEventListener('change', filterTable);
            semesterFilter.addEventListener('change', filterTable);
        }

        // Auto-hide alerts after 2.5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll("#success-alert, #error-alert, #validation-alert");
            alerts.forEach(alert => {
                if(alert) {
                    alert.style.display = 'none';
                }
            });
        }, 2500);
    });
</script>
@endpush
@endsection