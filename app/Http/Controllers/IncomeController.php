<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $incomes = Income::orderBy('payment_date', 'desc')->paginate($request->input('per_page', 10));
        return view('incomes', compact('incomes'));
    }

    public function create()
    {
        return view('incomes.create');
    }

    public function store(Request $request)
    {
        $this->normalizeDateFields($request, ['payment_date']);

        $data = $request->validate([
            'student_name' => ['required', 'string', 'max:100'],
            'fee_type' => ['required', 'string', 'max:80'],
            'amount' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'payment_date' => ['required', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
        ]);

        $data['receipt_number'] = strtoupper('RCPT-' . substr(bin2hex(random_bytes(4)), 0, 8));
        Income::create($data);

        return redirect()->route('incomes')->with('success', 'Income created successfully.');
    }

    public function update(Request $request, Income $income)
    {
        $this->normalizeDateFields($request, ['payment_date']);

        $data = $request->validate([
            'student_name' => ['required', 'string', 'max:100'],
            'fee_type' => ['required', 'string', 'max:80'],
            'amount' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'payment_date' => ['required', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
        ]);

        $income->update($data);

        return redirect()->route('incomes')->with('success', 'Income updated successfully.');
    }

    public function show(Income $income)
    {
        return view('incomes.show', ['income' => $income]);
    }

    public function edit(Income $income)
    {
        return view('incomes.edit', ['income' => $income]);
    }
}
