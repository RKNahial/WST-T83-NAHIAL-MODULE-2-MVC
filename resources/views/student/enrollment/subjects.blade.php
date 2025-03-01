@extends('layouts.app')

@section('title', 'Current Subjects')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Current Semester Subjects</h5>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select id="academicYearFilter" class="form-select">
                                <option value="">All Academic Years</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="semesterFilter" class="form-select">
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
                            @foreach($enrolledSubjects ?? [] as $subject)
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
                            @endforeach
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
        const datatable = new simpleDatatables.DataTable(".datatable", {
            perPageSelect: false,
            searchable: false
        });

        // Filter functions
        function filterRows() {
            const yearFilter = document.getElementById('academicYearFilter').value;
            const semesterFilter = document.getElementById('semesterFilter').value;

            // Get all rows
            const rows = document.querySelectorAll('.datatable tbody tr');
            let visibleRows = 0;

            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                const year = cells[2].textContent.trim(); // Academic Year column
                const semester = cells[3].textContent.trim(); // Semester column

                const yearMatch = !yearFilter || year === yearFilter;
                const semesterMatch = !semesterFilter || semester === semesterFilter;

                const isVisible = yearMatch && semesterMatch;
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

        // Event listeners for filters
        document.getElementById('academicYearFilter').addEventListener('change', filterRows);
        document.getElementById('semesterFilter').addEventListener('change', filterRows);

        // Add CSS for hidden rows
        const style = document.createElement('style');
        style.textContent = '.hide { display: none !important; }';
        document.head.appendChild(style);
    });
</script>
@endpush