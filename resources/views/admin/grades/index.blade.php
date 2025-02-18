@extends('layouts.app')

@section('title', 'Grades')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Grades List</h5>
                    
                    <!-- Add Button -->
                    <div class="d-flex justify-content-end mb-4">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGradeModal">
                            <i class="bi bi-plus-circle"></i> Add New Grade
                        </button>
                    </div>

                    <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Student</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Grade</th>
                                <th scope="col">School Year</th>
                                <th scope="col">Semester</th>
                                <th scope="col">Remarks</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grades ?? [] as $grade)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $grade->student->name }}</td>
                                <td>{{ $grade->subject->name }}</td>
                                <td>{{ $grade->grade }}</td>
                                <td>{{ $grade->school_year }}</td>
                                <td>{{ $grade->semester }}</td>
                                <td>
                                    <span class="badge bg-{{ $grade->remarks == 'Passed' ? 'success' : 'danger' }}">
                                        {{ $grade->remarks }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editGradeModal{{ $grade->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteGradeModal{{ $grade->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
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