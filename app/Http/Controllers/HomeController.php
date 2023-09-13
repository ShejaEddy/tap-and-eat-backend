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
        $todayPendingTransactions = \App\Transaction::where('status', 'pending')->count();
        $totalSuccessfulTransactions = \App\Transaction::where('status', 'SUCCESS')->count();
        $totalIncome = \App\Transaction::where('status', 'SUCCESS')->sum('amount');
        $studentsNumber = \App\Student::count();

        // get total validated cards, active cards, and inactive cards
        $totalValidatedCards = \App\CardValidation::count();
        $activeCards = \App\Student::where('balance', '>', 0)->count();
        $inactiveCards = \App\Student::where('balance', 0)->count();

        return view('home', compact(
            'totalFailedTransactions',
            'todayPendingTransactions',
            'totalSuccessfulTransactions',
            'totalIncome',
            'studentsNumber',
            'totalValidatedCards',
            'activeCards',
            'inactiveCards'
        ));
    }
}
