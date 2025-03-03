@extends('layouts.app')

@section('title', 'Add New Student')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add New Student</h5>

                    @if (session('success') && session('temp_password'))
                        <div class="alert alert-success alert-dismissible alert-important fade show" id="successAlert" role="alert">
                            <strong>{{ session('success') }}</strong>
                            <hr>
                            <p class="mb-0">Temporary Password: <strong id="tempPassword">{{ session('temp_password') }}</strong></p>
                            <p class="mb-0 text-danger">Please copy this password now! This message will disappear in <span id="countdown">10</span> seconds.</p>
                            <div class="mt-3">
                                <button type="button" class="btn btn-primary" onclick="copyPassword()">Copy Password</button>
                                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Go to Students List</a>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.students.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input type="text" class="form-control @error('student_id') is-invalid @enderror" 
                                id="student_id" name="student_id" value="{{ old('student_id') }}" required>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="course" class="form-label">Course</label>
                            <input type="text" class="form-control @error('course') is-invalid @enderror" 
                                id="course" name="course" value="{{ old('course') }}" required>
                            @error('course')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="year_level" class="form-label">Year Level</label>
                            <select class="form-select @error('year_level') is-invalid @enderror" 
                                id="year_level" name="year_level" required>
                                <option value="">Select Year Level</option>
                                <option value="1" {{ old('year_level') == '1' ? 'selected' : '' }}>1st Year</option>
                                <option value="2" {{ old('year_level') == '2' ? 'selected' : '' }}>2nd Year</option>
                                <option value="3" {{ old('year_level') == '3' ? 'selected' : '' }}>3rd Year</option>
                                <option value="4" {{ old('year_level') == '4' ? 'selected' : '' }}>4th Year</option>
                            </select>
                            @error('year_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Save Student</button>
                            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

@if(session('success') && session('temp_password'))
<div class="alert alert-success alert-dismissible alert-important fade show" id="successAlert" role="alert">
    <strong>{{ session('success') }}</strong>
    <hr>
    <p class="mb-0">Temporary Password: <strong id="tempPassword">{{ session('temp_password') }}</strong></p>
    <p class="mb-0 text-danger">Please copy this password now! This message will disappear in <span id="countdown">10</span> seconds.</p>
    <div class="mt-3">
        <button type="button" class="btn btn-primary" onclick="copyPassword()">Copy Password</button>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Go to Students List</a>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<script>
// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get the elements
    const countdownElement = document.getElementById('countdown');
    const successAlert = document.getElementById('successAlert');
    
    // Only proceed if the elements exist
    if (countdownElement && successAlert) {
        let timeLeft = 10;
        
        // Start the countdown
        const countdownTimer = setInterval(function() {
            timeLeft--;
            countdownElement.textContent = timeLeft;
            
            // When countdown reaches zero
            if (timeLeft <= 0) {
                clearInterval(countdownTimer);
                // Redirect to index page
                window.location.href = "{{ route('admin.students.index') }}";
            }
        }, 1000);
        
        // Prevent the alert from being automatically dismissed
        const closeButton = successAlert.querySelector('.btn-close');
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                clearInterval(countdownTimer);
            });
        }
    }
});

// Function to copy password to clipboard
function copyPassword() {
    const tempPassword = document.getElementById('tempPassword');
    if (tempPassword) {
        // Create a temporary input element
        const tempInput = document.createElement('input');
        tempInput.value = tempPassword.textContent;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        alert('Password copied to clipboard!');
    }
}
</script>

<style>
#tempPassword {
    cursor: pointer;
    background-color: #f8f9fa;
    padding: 4px 8px;
    border-radius: 4px;
}

#tempPassword:hover {
    background-color: #e9ecef;
}
</style>
@endif

@endsection