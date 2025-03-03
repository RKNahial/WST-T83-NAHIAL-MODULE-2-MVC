@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<section class="section dashboard">
    <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-8">
            <div class="row">
                <!-- Students Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Students</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $totalStudents }}</h6>
                                    <span class="text-muted small pt-2">Currently active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subjects Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Subjects</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-book"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $totalSubjects }}</h6>
                                    <span class="text-muted small pt-2">Active subjects</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enrollments Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Current Enrollments</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-check"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $currentEnrollments }}</h6>
                                    <span class="text-muted small pt-2">Currently Enrolled</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Students -->
                <div class="col-12">
                    <div class="card recent-sales overflow-auto">

                        <div class="card-body">
                            <h5 class="card-title">Recent Students <span>| Today</span></h5>
                            <table class="table table-borderless datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Student</th>
                                        <th scope="col">Course</th>
                                        <th scope="col">Year Level</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentStudents as $student)
                                    <tr>
                                        <th scope="row"><a href="#">#{{ $student->id }}</a></th>
                                        <td>{{ $student->name }}</td> 
                                        <td>{{ $student->course }}</td>
                                        <td>
                                            @switch($student->year_level)
                                                @case(1)
                                                    First Year
                                                    @break
                                                @case(2)
                                                    Second Year
                                                    @break
                                                @case(3)
                                                    Third Year
                                                    @break
                                                @case(4)
                                                    Fourth Year
                                                    @break
                                                @default
                                                    {{ $student->year_level }}
                                            @endswitch
                                        </td>
                                        <td><span class="badge bg-success">Active</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side columns -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card">

                <div class="card-body pb-0">
                    <h5 class="card-title">Quick Actions</h5>
                    <div class="news">
                        <div class="post-item clearfix">
                            <a href="{{ route('admin.students.create') }}" class="btn btn-primary mb-3 w-100">
                                <i class="bi bi-person-plus"></i> Add New Student
                            </a>
                        </div>
                        <div class="post-item clearfix">
                            <a href="{{ route('admin.subjects.create') }}" class="btn btn-success mb-3 w-100">
                                <i class="bi bi-book"></i> Add New Subject
                            </a>
                        </div>
                        <div class="post-item clearfix">
                            <a href="{{ route('admin.enrollments.create') }}" class="btn btn-info mb-3 w-100">
                                <i class="bi bi-person-check"></i> New Enrollment
                            </a>
                        </div>
                        <div class="post-item clearfix">
                            <a href="{{ route('admin.grades.index') }}" class="btn btn-warning mb-3 w-100">
                                <i class="bi bi-card-checklist"></i> Manage Grades
                            </a>
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
            "pageLength": 5,
            "ordering": false
        });
    });
</script>
@endsection