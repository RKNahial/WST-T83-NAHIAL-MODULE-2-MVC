@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <!-- Enrolled Subjects Card -->
                <div class="col-xxl-6 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Enrolled Subjects</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-book"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $totalEnrolledSubjects ?? 0 }}</h6>
                                    <span class="text-muted small pt-2">Current semester</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GWA Card -->
                <div class="col-xxl-6 col-md-6">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">GWA</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $GWA ?? '0.00' }}</h6>
                                    <span class="text-muted small pt-2">Current GWA</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Subjects Table -->
                <div class="col-12">
                    <div class="card recent-sales overflow-auto">
                        <div class="card-body">
                            <h5 class="card-title">Current Subjects <span>| {{ $currentSemester }}</span></h5>
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
                                    @forelse($currentSubjects as $subject)
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
                                                @elseif($subject->status == 'completed')
                                                    <span class="badge bg-info">Completed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                No subjects enrolled for {{ $currentSemester }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
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
    });
</script>
@endpush