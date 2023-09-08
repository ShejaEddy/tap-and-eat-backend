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
                {{ $totalTransactions }}
                <p>Total Transactions</p>
            </div>
        </div><div class="col-md-3 ">
            <div class="card card-body bg-blue text-center">
                {{ $totalTransactionsAmount }}
                <p>Rwf Total Amount Earned</p>
            </div>
        </div><div class="col-md-3">
            <div class="card card-body bg-indigo text-center">
                {{ $todayTransactionsAmount }} Rwf
                <p>Today's Transactions amount</p>
            </div>
        </div><div class="col-md-3">
            <div class="card card-body bg-green text-center">
                {{ $studentsNumber}}
                <p>Students number</p>
            </div>
        </div>
    </div>

@stop

@section('css')
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
