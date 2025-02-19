@extends('layouts.app')

@section('title', 'Students Management')

@section('content')
<!-- <div class="pagetitle">
    <h1>Students</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Students</li>
        </ol>
    </nav>
</div> -->

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
                        <h5 class="card-title mb-0">Students List</h5>
                        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add New Student
                        </a>
                    </div>

                    <!-- Table with datatable -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Student ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Course</th>
                                <th scope="col">Year Level</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students ?? [] as $student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $student->student_id }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
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
                                <td>
                                    <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
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