@extends('layouts.app')

@section('title', 'Subjects')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <h5 class="card-title">All Subjects</h5>
                        <div class="d-flex gap-2 align-items-center">
                            <!-- Academic Year Filter -->
                            <select id="academicYearFilter" class="form-select" style="width: auto;">
                                <option value="">All Years</option>
                                @php
                                    $currentYear = 2024;  
                                    $endYear = 2026;     
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
                        </div>
                    </div>

                    <!-- No Results Message -->
                    <div id="noResults" class="alert alert-info" style="display: none;">
                        No subjects found for the selected filters.
                    </div>

                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">Subject Code</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Academic Year</th>
                                <th scope="col">Semester</th>
                                <th scope="col">Grade</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrolledSubjects ?? [] as $subject)
                            <tr>
                                <td>{{ $subject->subject->code }}</td>
                                <td>{{ $subject->subject->name }}</td>
                                <td>{{ $subject->academic_year }}</td>
                                <td>
                                    @switch($subject->semester)
                                        @case(1)
                                            First Semester
                                            @break
                                        @case(2)
                                            Second Semester
                                            @break
                                        @case(3)
                                            Summer
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    @if($subject->grade)
                                        {{ number_format($subject->grade->grade, 2) }}
                                    @else
                                        <span class="badge bg-warning">No grade yet</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subject->status == 'enrolled')
                                        <span class="badge bg-success">Enrolled</span>
                                    @elseif($subject->status == 'dropped')
                                        <span class="badge bg-danger">Dropped</span>
                                    @elseif($subject->status == 'completed')
                                        <span class="badge bg-info">Completed</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="alert alert-info mb-0" role="alert">
                                        No subjects found for the selected filters.
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
@endsection

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

        const yearFilter = document.getElementById('academicYearFilter');
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

                const yearCell = row.cells[2]?.textContent.trim();
                const semesterCell = row.cells[3]?.textContent.trim();

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
                    <td colspan="6" class="text-center">
                        <div class="alert alert-info mb-0">
                            No records found for the selected filters.
                        </div>
                    </td>
                `;
                tableBody.innerHTML = '';
                tableBody.appendChild(noResultsRow);
            }

            // Force DataTable to update
            datatable.refresh();
        }

        // Style for hidden rows
        const style = document.createElement('style');
        style.textContent = `
            .hide {
                display: none !important;
            }
            .datatable-empty {
                text-align: center;
                padding: 1rem;
            }
        `;
        document.head.appendChild(style);

        // Add filter event listeners
        if (yearFilter && semesterFilter) {
            yearFilter.addEventListener('change', filterTable);
            semesterFilter.addEventListener('change', filterTable);
        }
    });
</script>
@endpush