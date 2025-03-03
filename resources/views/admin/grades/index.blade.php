@extends('layouts.app')

@section('title', 'Grades')

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
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1 mt-3 mx-2">
                        <h5 class="card-title mb-0">Grades List</h5>
                        <div class="d-flex gap-2 align-items-center">
                            <form method="GET" action="{{ route('admin.grades.index') }}" class="d-flex gap-2 align-items-center">
                                <!-- Academic Year Filter -->
                                <select name="academic_year" class="form-select" style="width: auto;" onchange="this.form.submit()">
                                    <option value="">All Years</option>
                                    @php
                                        $currentYear = 2024;
                                        $endYear = $currentYear + 2;
                                    @endphp
                                    @for($year = $currentYear; $year <= $endYear; $year++)
                                        <option value="{{ $year }}-{{ $year + 1 }}" {{ request('academic_year') == "$year-".($year + 1) ? 'selected' : '' }}>
                                            {{ $year }}-{{ $year + 1 }}
                                        </option>
                                    @endfor
                                </select>

                                <!-- Semester Filter -->
                                <select name="semester" class="form-select" style="width: auto;" onchange="this.form.submit()">
                                    <option value="">All Semesters</option>
                                    <option value="First Semester" {{ request('semester') == 'First Semester' ? 'selected' : '' }}>First Semester</option>
                                    <option value="Second Semester" {{ request('semester') == 'Second Semester' ? 'selected' : '' }}>Second Semester</option>
                                    <option value="Summer" {{ request('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                                </select>
                            </form>
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
                                            <button type="submit" class="btn btn-danger btn-sm delete-grade-btn" 
                                                data-student-name="{{ $enrollment->student->name }}"
                                                data-subject-name="{{ $enrollment->subject->name }}">
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

<!-- Delete Grade Confirmation Modal -->
<div class="modal fade" id="deleteGradeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">  
                <h5 class="modal-title">Confirm Delete Grade</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="confirmation-question mb-3">
                    <h6 id="deleteGradeMessage" class="text-center"></h6>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirmDeleteGrade">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
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

        // Handle success alert auto-dismiss
        setTimeout(function() {
            const alert = document.getElementById('successAlert');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 2500);

        // Delete grade confirmation modal handling
        const deleteGradeModal = new bootstrap.Modal(document.getElementById('deleteGradeModal'));
        let deleteGradeForm = null;

        // Handle delete button clicks
        document.querySelectorAll('.delete-grade-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                deleteGradeForm = this.closest('form');
                
                const studentName = this.dataset.studentName;
                const subjectName = this.dataset.subjectName;
                
                // Set confirmation message
                const message = `Are you sure you want to delete the grade for ${studentName} in ${subjectName}?`;
                document.getElementById('deleteGradeMessage').textContent = message;
                
                deleteGradeModal.show();
            });
        });

        // Handle confirmation button click
        document.getElementById('confirmDeleteGrade').addEventListener('click', function() {
            if (deleteGradeForm) {
                deleteGradeForm.submit();
            }
            deleteGradeModal.hide();
        });
    });
</script>
@endpush
@endsection