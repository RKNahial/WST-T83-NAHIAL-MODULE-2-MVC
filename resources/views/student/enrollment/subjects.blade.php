@extends('layouts.app')

@section('title', 'Current Subjects')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Current Semester Subjects</h5>

                    @if($currentEnrollment)
                        <div class="alert alert-info">
                            <strong>Current Semester:</strong> {{ $currentEnrollment->semester }} 
                            <br>
                            <strong>Total Units:</strong> {{ $totalUnits }}
                        </div>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Description</th>
                                    <th>Units</th>
                                    <th>Schedule</th>
                                    <th>Room</th>
                                    <th>Instructor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($enrolledSubjects as $subject)
                                    <tr>
                                        <td>{{ $subject->code }}</td>
                                        <td>{{ $subject->name }}</td>
                                        <td>{{ $subject->units }}</td>
                                        <td>{{ $subject->schedule }}</td>
                                        <td>{{ $subject->room }}</td>
                                        <td>{{ $subject->instructor }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No subjects enrolled for this semester.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning">
                            You are not enrolled in the current semester.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection