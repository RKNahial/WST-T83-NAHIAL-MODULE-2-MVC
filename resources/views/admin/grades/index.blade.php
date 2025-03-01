@extends('layouts.app')

@section('title', 'Grades')

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
                        <h5 class="card-title mb-0">Grades List</h5>
                        <div class="d-flex gap-2 align-items-center">
                            <!-- Semester Filter -->
                            <select id="semesterFilter" class="form-select" style="width: auto;">
                                <option value="">All Semesters</option>
                                <option value="First Semester">First Semester</option>
                                <option value="Second Semester">Second Semester</option>
                                <option value="Summer">Summer</option>
                            </select>

                            <!-- Academic Year Filter -->
                            <select id="yearFilter" class="form-select" style="width: auto;">
                                <option value="">All Years</option>
                                @php
                                    $currentYear = 2024;  // Starting from 2024
                                    $endYear = $currentYear + 2;  // Will show up to 2026
                                @endphp
                                @for($year = $currentYear; $year <= $endYear; $year++)
                                    <option value="{{ $year }}-{{ $year + 1 }}">{{ $year }}-{{ $year + 1 }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- No Results Message -->
                    <div id="noResults" class="alert alert-info" style="display: none;">
                        No grades found for the selected filters.
                    </div>

                    <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Student ID</th>
                                <th scope="col">Student Name</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Semester</th>
                                <th scope="col">Academic Year</th>
                                <th scope="col">Grade</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $enrollment->student->student_id }}</td>
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
                                    @if($enrollment->grade)
                                        {{ number_format($enrollment->grade->grade, 2) }}
                                    @else
                                        <span class="badge bg-warning">No grade yet</span>
                                    @endif
                                </td>
                                <td>
                                    @if($enrollment->grade)
                                        <!-- Edit Grade Button -->
                                        <button type="button" class="btn btn-primary btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editGradeModal{{ $enrollment->grade->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        
                                        <!-- Delete Grade Button -->
                                        <form action="{{ route('admin.grades.destroy', $enrollment->grade->id) }}" 
                                            method="POST" 
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this grade?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Add Grade Button -->
                                        <button type="button" class="btn btn-success btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#addGradeModal{{ $enrollment->id }}">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            <!-- Add Grade Modal -->
                            <div class="modal fade" id="addGradeModal{{ $enrollment->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add Grade for {{ $enrollment->student->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.grades.store') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <input type="hidden" name="enrollment_id" value="{{ $enrollment->id }}">
                                                
                                                <!-- Student Information (Read-only) -->
                                                <div class="mb-3">
                                                    <label class="form-label">Student</label>
                                                    <input type="text" class="form-control" 
                                                        value="{{ $enrollment->student->name }} ({{ $enrollment->student->student_id }})" 
                                                        readonly disabled>
                                                </div>

                                                <!-- Subject Information (Read-only) -->
                                                <div class="mb-3">
                                                    <label class="form-label">Subject</label>
                                                    <input type="text" class="form-control" 
                                                        value="{{ $enrollment->subject->name }} ({{ $enrollment->subject->code }})" 
                                                        readonly disabled>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="grade" class="form-label">Grade</label>
                                                    <select class="form-select" name="grade" required>
                                                        <option value="">Select Grade</option>
                                                        <option value="1.00">1.00</option>
                                                        <option value="1.25">1.25</option>
                                                        <option value="1.50">1.50</option>
                                                        <option value="1.75">1.75</option>
                                                        <option value="2.00">2.00</option>
                                                        <option value="2.25">2.25</option>
                                                        <option value="2.50">2.50</option>
                                                        <option value="2.75">2.75</option>
                                                        <option value="3.00">3.00</option>
                                                        <option value="5.00">5.00</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save Grade</button>   
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Grade Modal -->
                            @if($enrollment->grade)
                            <div class="modal fade" id="editGradeModal{{ $enrollment->grade->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Grade for {{ $enrollment->student->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.grades.update', $enrollment->grade->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <!-- Student Information (Read-only) -->
                                                <div class="mb-3">
                                                    <label class="form-label">Student</label>
                                                    <input type="text" class="form-control" 
                                                        value="{{ $enrollment->student->name }} ({{ $enrollment->student->student_id }})" 
                                                        readonly disabled>
                                                </div>

                                                <!-- Subject Information (Read-only) -->
                                                <div class="mb-3">
                                                    <label class="form-label">Subject</label>
                                                    <input type="text" class="form-control" 
                                                        value="{{ $enrollment->subject->name }} ({{ $enrollment->subject->code }})" 
                                                        readonly disabled>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="grade" class="form-label">Grade</label>
                                                    <select class="form-select" name="grade" required>
                                                        <option value="">Select Grade</option>
                                                        <option value="1.00" {{ $enrollment->grade->grade == 1.00 ? 'selected' : '' }}>1.00</option>
                                                        <option value="1.25" {{ $enrollment->grade->grade == 1.25 ? 'selected' : '' }}>1.25</option>
                                                        <option value="1.50" {{ $enrollment->grade->grade == 1.50 ? 'selected' : '' }}>1.50</option>
                                                        <option value="1.75" {{ $enrollment->grade->grade == 1.75 ? 'selected' : '' }}>1.75</option>
                                                        <option value="2.00" {{ $enrollment->grade->grade == 2.00 ? 'selected' : '' }}>2.00</option>
                                                        <option value="2.25" {{ $enrollment->grade->grade == 2.25 ? 'selected' : '' }}>2.25</option>
                                                        <option value="2.50" {{ $enrollment->grade->grade == 2.50 ? 'selected' : '' }}>2.50 </option>
                                                        <option value="2.75" {{ $enrollment->grade->grade == 2.75 ? 'selected' : '' }}>2.75</option>
                                                        <option value="3.00" {{ $enrollment->grade->grade == 3.00 ? 'selected' : '' }}>3.00</option>
                                                        <option value="5.00" {{ $enrollment->grade->grade == 5.00 ? 'selected' : '' }}>5.00</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Update Grade</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
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
            const yearFilter = document.getElementById('yearFilter').value;

            // Get all rows
            const rows = document.querySelectorAll('.datatable tbody tr');
            let visibleRows = 0;

            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                const semester = cells[4].textContent.trim(); // Adjust index based on your semester column
                const year = cells[5].textContent.trim(); // Adjust index based on your academic year column

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