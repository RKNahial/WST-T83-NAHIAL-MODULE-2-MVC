@extends('layouts.app')

@section('title', 'Curriculum Evaluation')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Curriculum Evaluation</h5>
                        <div class="alert alert-info mb-0 px-3 py-2">
                            Overall GWA: {{ $gwa ?? '0.00' }}
                        </div>
                    </div>

                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Subject Code</th>
                                <th scope="col">Subject Name</th>
                                <th scope="col">Units</th>
                                <th scope="col">Pre-requisites</th>
                                <th scope="col">Grade</th>
                                <th scope="col">Status</th>
                                <th scope="col">Semester Taken</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $currentYear = null; $currentSem = null; @endphp
                            
                            @foreach($enrollments as $enrollment)
                                @if($currentYear != $enrollment->academic_year || $currentSem != $enrollment->semester)
                                    <tr class="table-primary">
                                        <td colspan="8" class="fw-bold">
                                            @switch($enrollment->semester)
                                                @case(1)
                                                    First Semester
                                                    @break
                                                @case(2)
                                                    Second Semester
                                                    @break
                                                @case(3)
                                                    Summer
                                                    @break
                                            @endswitch
                                            - {{ $enrollment->academic_year }}
                                        </td>
                                    </tr>
                                    @php 
                                        $currentYear = $enrollment->academic_year;
                                        $currentSem = $enrollment->semester;
                                    @endphp
                                @endif
                                
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $enrollment->subject->code }}</td>
                                    <td>{{ $enrollment->subject->name }}</td>
                                    <td>{{ $enrollment->subject->units }}</td>
                                    <td>
                                        @if($enrollment->subject->prerequisites)
                                            {{ $enrollment->subject->prerequisites }}
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($enrollment->grade)
                                            @if($enrollment->grade->grade >= 75)
                                                <span class="badge bg-success">{{ $enrollment->grade->grade }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $enrollment->grade->grade }}</span>
                                            @endif
                                        @else
                                            <span class="badge bg-warning text-dark">Ongoing</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($enrollment->status == 'enrolled')
                                            <span class="badge bg-primary">Enrolled</span>
                                        @elseif($enrollment->status == 'dropped')
                                            <span class="badge bg-danger">Dropped</span>
                                        @else
                                            <span class="badge bg-success">Completed</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $enrollment->academic_year }} - 
                                        @switch($enrollment->semester)
                                            @case(1)
                                                1st
                                                @break
                                            @case(2)
                                                2nd
                                                @break
                                            @case(3)
                                                Summer
                                                @break
                                        @endswitch
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [[7, 'asc']], // Sort by semester taken
            "drawCallback": function(settings) {
                // Maintain the semester headers when sorting
                var api = this.api();
                var rows = api.rows({page:'current'}).nodes();
                var last = null;
                
                api.column(7, {page:'current'}).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="table-primary"><td colspan="8" class="fw-bold">' + group + '</td></tr>'
                        );
                        last = group;
                    }
                });
            }
        });
    });
</script>
@endpush