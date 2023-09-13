@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome back {{ explode(" ", auth()->user()->name)[0] }}.</p>
{{--   Cards for  "totalTransactions", "totalTransactionsAmount", "todayTransactionsAmount", "studentsNumber"--}}
    <div class="row">
        <div class="col-md-3">
            <div class="card card-body bg-red text-center">
                {{ $totalFailedTransactions }}
                <p>Failed Transactions</p>
            </div>
        </div><div class="col-md-3 ">
            <div class="card card-body bg-warning text-center">
                {{ $todayPendingTransactions }}
                <p>Pending Transactions</p>
            </div>
        </div><div class="col-md-3">
            <div class="card card-body bg-green text-center">
                {{ $totalSuccessfulTransactions }} ({{ $totalIncome }})
                <p>Successfull Transactions</p>
            </div>
        </div><div class="col-md-3">
            <div class="card card-blue bg-blue text-center">
                {{ $studentsNumber}}
                <p>Total Student</p>
            </div>
        </div>
    </div>

@stop

@section('css')
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
