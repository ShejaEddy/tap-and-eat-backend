@extends('adminlte::page')

@section('title', 'Card validations')

@section('content_header')
    <h1>Card validations</h1>
@stop
@section('plugins.Datatables', true)
@section('content')
    <div class="row justify-content-center">
        <!-- left column -->
        <div class="col-md-11">
            <!-- general form elements -->
            <div class="card">
                <div class="card-header">

                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>NO</th>
                                <th>Student Name</th>
                                <th>Student Phone number</th>
                                <th>Employee Name</th>
                                <th>Amount</th>
                                <th>Created At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($validations as $validation)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $validation->student->name }}
                                    </td>
                                    <td>
                                        {{ $validation->student->phoneNumber }}
                                    </td>
                                    <td>
                                        {{ $validation->employee->name }}
                                    </td>
                                    <td>
                                        {{ number_format($validation->amount) }} RWF
                                    </td>
                                    <td>
                                        {{ $validation->created_at->toDateString() }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @stop
        @section("js")
            <script>
                $(".table").DataTable()
            </script>
@endsection
