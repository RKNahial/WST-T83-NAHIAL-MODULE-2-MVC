@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<section class="section profile">
    <!-- Alerts moved above the card -->
    <div class="row">
        <div class="col-xl-8">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body pt-3">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                        </li>
                        @if(Auth::user()->role !== 'student')
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                            </li>
                        @endif
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                        </li>
                    </ul>

                    <div class="tab-content pt-2">
                        <!-- Profile Overview -->
                        <div class="tab-pane fade show active profile-overview" id="profile-overview">
                            <h5 class="card-title">Profile Details</h5>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Full Name</div>
                                <div class="col-lg-9 col-md-8">{{ Auth::user()->name }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Email</div>
                                <div class="col-lg-9 col-md-8">{{ Auth::user()->email }}</div>
                            </div>

                            @php
                                function getOrdinalYear($year) {
                                    switch ($year) {
                                        case 1:
                                            return '1st Year';
                                        case 2:
                                            return '2nd Year';
                                        case 3:
                                            return '3rd Year';
                                        case 4:
                                            return '4th Year';
                                        default:
                                            return $year . 'th Year';
                                    }
                                }
                            @endphp

                            @if(Auth::user()->role === 'student' && Auth::user()->student)
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Student ID</div>
                                    <div class="col-lg-9 col-md-8">{{ Auth::user()->student->student_id }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Course</div>
                                    <div class="col-lg-9 col-md-8">{{ Auth::user()->student->course }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Year Level</div>
                                    <div class="col-lg-9 col-md-8">{{ getOrdinalYear(Auth::user()->student->year_level) }}</div>
                                </div>
                            @endif
                        </div>

                        @if(Auth::user()->role !== 'student')
                            <!-- Profile Edit Form -->
                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                <form method="post" action="{{ route('profile.update') }}">
                                    @csrf
                                    @method('patch')

                                    <div class="row mb-3">
                                        <label for="name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="name" type="text" class="form-control" id="name" 
                                                value="{{ old('name', Auth::user()->name) }}" 
                                                {{ Auth::user()->role === 'student' ? 'disabled' : '' }}>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="email" type="email" class="form-control" id="email" 
                                                value="{{ old('email', Auth::user()->email) }}" 
                                                {{ Auth::user()->role === 'student' ? 'disabled' : '' }}>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        <!-- Change Password Form -->
                        <div class="tab-pane fade pt-3" id="profile-change-password">
                            <form method="post" action="{{ route('password.update') }}">
                                @csrf
                                @method('put')

                                <div class="row mb-3">
                                    <label for="current_password" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="current_password" type="password" class="form-control" id="current_password">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="password" type="password" class="form-control" id="password">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password_confirmation" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="password_confirmation" type="password" class="form-control" id="password_confirmation">
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Change Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $("#success-alert").fadeOut('slow');
            $("#error-alert").fadeOut('slow');
        }, 2500);
    });
</script>
@endpush
@endsection