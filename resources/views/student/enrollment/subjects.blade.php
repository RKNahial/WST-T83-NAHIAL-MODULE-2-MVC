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
                            <form method="GET" action="{{ route('student.enrollment.subjects') }}" class="d-flex gap-2 align-items-center">
                                <!-- Academic Year Filter -->
                                <select name="academic_year" class="form-select" style="width: auto;" onchange="this.form.submit()">
                                    <option value="">All Years</option>
                                    @php
                                        $currentYear = 2024;  
                                        $endYear = 2026;     
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
                                    @if($subject->status == 'dropped')
                                        <span class="badge bg-secondary">N/A</span>
                                    @elseif($subject->grade)
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
                                    No subjects found for the selected filters.
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
    });
</script>
@endpush