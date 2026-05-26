<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Payroll;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $this->normalizeDateFields($request, ['start_date', 'end_date']);

        $request->validate([
            'start_date' => ['nullable', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $incomeQuery = Income::query();
        $expenseQuery = Expense::query();
        $payrollQuery = Payroll::query();

        if ($startDate) {
            $incomeQuery->whereDate('payment_date', '>=', $startDate);
            $expenseQuery->whereDate('expense_date', '>=', $startDate);
            $payrollQuery->whereDate('payroll_date', '>=', $startDate);
        }

        if ($endDate) {
            $incomeQuery->whereDate('payment_date', '<=', $endDate);
            $expenseQuery->whereDate('expense_date', '<=', $endDate);
            $payrollQuery->whereDate('payroll_date', '<=', $endDate);
        }

        $incomes = $incomeQuery->orderBy('payment_date', 'desc')->get();
        $expenses = $expenseQuery->orderBy('expense_date', 'desc')->get();
        $payrolls = $payrollQuery->orderBy('payroll_date', 'desc')->get();

        $incomeTotal = $incomes->sum('amount');
        $expenseTotal = $expenses->sum('amount');
        $payrollTotal = $payrolls->sum('net_salary');
        $netProfit = $incomeTotal - ($expenseTotal + $payrollTotal);

        return view('reports', compact(
            'incomes',
            'expenses',
            'payrolls',
            'incomeTotal',
            'expenseTotal',
            'payrollTotal',
            'netProfit',
            'startDate',
            'endDate'
        ));
    }
}
