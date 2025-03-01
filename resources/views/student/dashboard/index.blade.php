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
                            <table class="table table-borderless datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">Subject Code</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col">Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($currentSubjects ?? [] as $subject)
                                    <tr>
                                        <th scope="row">{{ $subject->subject->code }}</th>
                                        <td>{{ $subject->subject->name }}</td>
                                        <td>
                                            @if($subject->grade)
                                                <span class="badge bg-success">{{ number_format($subject->grade->grade, 2) }}</span>
                                            @else
                                                <span class="badge bg-warning">No grade yet</span>
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
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            "pageLength": 10,
            "ordering": false
        });
    });
</script>
@endsection