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
        // Filter function
        function filterRows() {
            const semesterFilter = document.getElementById('semesterFilter').value;
            const yearFilter = document.getElementById('yearFilter').value;

            // Get all rows
            const rows = document.querySelectorAll('.datatable tbody tr');
            let visibleRows = 0;

            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                const semester = cells[3].textContent.trim(); // Adjust index based on your semester column
                const year = cells[4].textContent.trim(); // Adjust index based on your academic year column

                const semesterMatch = !semesterFilter || semester.includes(semesterFilter);
                const yearMatch = !yearFilter || year === yearFilter;

                const isVisible = semesterMatch && yearMatch;
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
        document.getElementById('semesterFilter').addEventListener('change', filterRows);
        document.getElementById('yearFilter').addEventListener('change', filterRows);

        // Add CSS for hidden rows
        const style = document.createElement('style');
        style.textContent = '.hide { display: none !important; }';
        document.head.appendChild(style);

        // Auto-hide alerts
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