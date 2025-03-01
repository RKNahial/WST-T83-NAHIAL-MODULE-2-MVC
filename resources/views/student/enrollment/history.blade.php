@extends('layouts.app')

@section('title', 'Enrollment History')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Enrollment History</h5>

                    @forelse($enrollmentHistory as $enrollment)
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>{{ $enrollment->semester }} Semester</h5>
                                    <span class="badge bg-success">{{ $enrollment->status }}</span>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Date Enrolled:</strong> 
                                        {{ $enrollment->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Total Units:</strong> 
                                        {{ $enrollment->total_units }}
                                    </div>
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
                                        @foreach($enrollment->subjects as $subject)
                                            <tr>
                                                <td>{{ $subject->code }}</td>
                                                <td>{{ $subject->name }}</td>
                                                <td>{{ $subject->units }}</td>
                                                <td>{{ $subject->schedule }}</td>
                                                <td>{{ $subject->room }}</td>
                                                <td>{{ $subject->instructor }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            No enrollment history found.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection