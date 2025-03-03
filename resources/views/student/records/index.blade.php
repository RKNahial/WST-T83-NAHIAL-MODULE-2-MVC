@extends('layouts.app')

@section('title', 'Grades')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            @forelse($groupedEnrollments as $academicYear => $semesters)
                @foreach($semesters as $semester => $enrollments)
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">
                                @switch($semester)
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
                                {{ $academicYear }}
                            </h5>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Subject Code</th>
                                        <th>Description</th>
                                        <th>Units</th>
                                        <th>Grade</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrollments as $enrollment)
                                        <tr>
                                            <td>{{ $enrollment->subject->code }}</td>
                                            <td>{{ $enrollment->subject->name }}</td>
                                            <td>{{ $enrollment->subject->units }}</td>
                                            <td>
                                                @if($enrollment->status == 'dropped')
                                                    <span class="badge bg-secondary">N/A</span>
                                                @elseif($enrollment->grade)
                                                    {{ number_format($enrollment->grade->grade, 2) }}
                                                @else
                                                    <span class="badge bg-warning">No grade yet</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($enrollment->status == 'enrolled')
                                                    <span class="badge bg-success">Enrolled</span>
                                                @elseif($enrollment->status == 'dropped')
                                                    <span class="badge bg-danger">Dropped</span>
                                                @elseif($enrollment->status == 'completed')
                                                    <span class="badge bg-info">Completed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- GWA Row -->
                                    <tr class="table-light">
                                        <td colspan="3"></td>
                                        <td class="text-end"><strong>Semester GWA:</strong></td>
                                        <td>
                                            @php
                                                $semesterGrades = $enrollments->filter(function($enrollment) {
                                                    return $enrollment->status != 'dropped' && 
                                                           $enrollment->grade && 
                                                           $enrollment->grade->grade > 0;
                                                });
                                                
                                                $gwa = $semesterGrades->count() > 0 
                                                    ? number_format($semesterGrades->avg('grade.grade'), 2) 
                                                    : 'N/A';
                                            @endphp
                                            <strong>{{ $gwa }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="empty-state">
                            
                                 <class="mb-4" style="max-width: 200px; opacity: 0.7;">
                            <h3 class="mt-4" style="color: #012970;">No Grades Available Yet</h3>
                            <p class="text-muted mb-4 px-4">
                                You don't have any academic records or grades at the moment. 
                                Grades will appear here once they are submitted by your instructors.
                            </p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            "order": [[0, 'desc'], [1, 'asc']],  // Sort by Academic Year (desc) and Semester (asc)
            "pageLength": 10,
            "ordering": true,
        });
    });
</script>
@endpush

@push('styles')
<style>
    .empty-state {
        padding: 2rem 1rem;
    }
    .alert-light-primary {
        background-color: rgba(13, 110, 253, 0.05);
        border-color: rgba(13, 110, 253, 0.1);
    }
    .empty-state-help ul {
        list-style-type: none;
        padding-left: 1.5rem;
    }
    .empty-state-help ul li::before {
        content: "•";
        color: #012970;
        font-weight: bold;
        display: inline-block;
        width: 1em;
        margin-left: -1em;
    }
</style>
@endpush