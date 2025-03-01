@extends('layouts.app')

@section('title', 'Academic Records')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Academic Records</h5>

                    <!-- GWA Summary Card -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <h5 class="card-title">Overall GWA</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-graph-up"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ number_format($overallGwa ?? 0, 2) }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grades per Semester -->
                    @forelse($semesterGrades ?? [] as $semester => $grades)
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">{{ $semester }} Semester</h5>
                                <div class="mb-3">
                                    <strong>Semester GWA: </strong>
                                    {{ number_format($semesterGwa[$semester] ?? 0, 2) }}
                                </div>
                                
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Description</th>
                                            <th>Units</th>
                                            <th>Grade</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($grades as $grade)
                                            <tr>
                                                <td>{{ $grade->subject->code }}</td>
                                                <td>{{ $grade->subject->name }}</td>
                                                <td>{{ $grade->subject->units }}</td>
                                                <td>{{ $grade->grade }}</td>
                                                <td>
                                                    @if($grade->grade >= 75)
                                                        <span class="badge bg-success">PASSED</span>
                                                    @else
                                                        <span class="badge bg-danger">FAILED</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            No academic records found.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection