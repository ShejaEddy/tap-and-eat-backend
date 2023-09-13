<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalFailedTransactions = \App\Transaction::where('status', 'FAILED')->count();
        $todayPendingTransactions = \App\Transaction::where('status', 'PENDING')->sum();
        $totalSuccessfulTransactions = \App\Transaction::where('status', 'SUCCESS')->sum();
        $totalIncome = \App\Transaction::where('status', 'SUCCESS')->sum('amount');
        $studentsNumber = \App\Student::count();
        return view('home', compact(
            'totalFailedTransactions',
            'todayPendingTransactions',
            'totalSuccessfulTransactions',
            'totalIncome',
            'studentsNumber'
        ));
    }
}
