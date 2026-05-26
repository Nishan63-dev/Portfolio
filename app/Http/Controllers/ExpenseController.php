<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expenses = Expense::orderBy('expense_date', 'desc')->paginate($request->input('per_page', 10));
        return view('expenses', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $this->normalizeDateFields($request, ['expense_date']);

        $data = $request->validate([
            'expense_title' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:80'],
            'amount' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'expense_date' => ['required', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
        ]);

        Expense::create($data);

        return redirect()->route('expenses')->with('success', 'Expense created successfully.');
    }

    public function update(Request $request, Expense $expense)
    {
        $this->normalizeDateFields($request, ['expense_date']);

        $data = $request->validate([
            'expense_title' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:80'],
            'amount' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'expense_date' => ['required', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
        ]);

        $expense->update($data);

        return redirect()->route('expenses')->with('success', 'Expense updated successfully.');
    }

    public function show(Expense $expense)
    {
        return view('expenses.show', ['expense' => $expense]);
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', ['expense' => $expense]);
    }
}
