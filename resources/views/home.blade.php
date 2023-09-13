@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome back {{ explode(" ", auth()->user()->name)[0] }}.</p>
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
                {{ $totalSuccessfulTransactions }}
                <p>Successful Transactions</p>
            </div>
        </div><div class="col-md-3">
            <div class="card card-body bg-blue text-center">
                {{ $studentsNumber}}
                <p>Total Student</p>
            </div>
        </div>
    </div>
    {{--   Cards for  "totalValidatedCards", "activeCards", "inactiveCards", "totalIncome"--}}
    <div class="row mt-3">
        <div class="col-md-3">
            <div class="card card-body bg-info text-center">
                {{ $totalValidatedCards }}
                <p>Validated Cards</p>
            </div>
        </div><div class="col-md-3 ">
            <div class="card card-body bg-success text-center">
                {{ $activeCards }}
                <p>Active Cards</p>
            </div>
        </div><div class="col-md-3">
            <div class="card card-body bg-danger text-center">
                {{ $inactiveCards }}
                <p>Inactive Cards</p>
            </div>
        </div><div class="col-md-3">
            <div class="card card-body bg-indigo text-center">
                {{ $totalIncome }}
                <p>Total Income</p>
            </div>
        </div>


@stop

@section('css')
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
