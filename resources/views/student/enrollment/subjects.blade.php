@extends('layouts.app')

@section('title', 'Current Subjects')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Current Semester Subjects</h5>

                    <table class="table table-borderless datatable">
                        <thead>
                            <tr>
                                <th scope="col">Subject Code</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrolledSubjects ?? [] as $subject)
                            <tr>
                                <th scope="row">{{ $subject->subject->code }}</th>
                                <td>{{ $subject->subject->name }}</td>
                                <td>
                                    <span class="badge bg-success">
                                        {{ $subject->grade ? $subject->grade->grade : 'Ongoing' }}
                                    </span>
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
            "ordering": false
        });
    });
</script>
@endpush