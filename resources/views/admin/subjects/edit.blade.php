@extends('layouts.app')
@section('content')

<div class="pagetitle">
  <h1>Edit Student</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></li>
      <li class="breadcrumb-item active">Edit Student</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Edit Student Information</h5>

          <form class="row g-3" method="POST" action="{{ route('students.update', $student->id) }}">
            @csrf
            @method('PUT')

            <div class="col-md-6">
              <label for="student_id" class="form-label">Student ID</label>
              <input type="text" class="form-control @error('student_id') is-invalid @enderror" 
                     id="student_id" name="student_id" value="{{ old('student_id', $student->student_id) }}">
              @error('student_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="name" class="form-label">Full Name</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" 
                     id="name" name="name" value="{{ old('name', $student->name) }}">
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" 
                     id="email" name="email" value="{{ old('email', $student->email) }}">
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-3">
              <label for="course" class="form-label">Course</label>
              <input type="text" class="form-control @error('course') is-invalid @enderror" 
                     id="course" name="course" value="{{ old('course', $student->course) }}">
              @error('course')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-3">
              <label for="year_level" class="form-label">Year Level</label>
              <select class="form-select @error('year_level') is-invalid @enderror" 
                      id="year_level" name="year_level">
                @for ($i = 1; $i <= 4; $i++)
                  <option value="{{ $i }}" {{ (old('year_level', $student->year_level) == $i) ? 'selected' : '' }}>
                    {{ $i }}
                  </option>
                @endfor
              </select>
              @error('year_level')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-primary">Update Student</button>
              <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</section>

@endsection