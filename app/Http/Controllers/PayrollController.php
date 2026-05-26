<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $payrolls = Payroll::orderBy('payroll_date', 'desc')->paginate($request->input('per_page', 10));
        return view('payrolls', compact('payrolls'));
    }

    public function create()
    {
        return view('payrolls.create');
    }

    public function store(Request $request)
    {
        $this->normalizeDateFields($request, ['payroll_date']);

        $data = $request->validate([
            'employee_name' => ['required', 'string', 'max:100'],
            'salary' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'bonus' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'deduction' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'payroll_date' => ['required', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
        ]);

        $data['net_salary'] = $data['salary'] + $data['bonus'] - $data['deduction'];
        Payroll::create($data);

        return redirect()->route('payrolls')->with('success', 'Payroll created successfully.');
    }

    public function update(Request $request, Payroll $payroll)
    {
        $this->normalizeDateFields($request, ['payroll_date']);

        $data = $request->validate([
            'employee_name' => ['required', 'string', 'max:100'],
            'salary' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'bonus' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'deduction' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'payroll_date' => ['required', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
        ]);

        $data['net_salary'] = $data['salary'] + $data['bonus'] - $data['deduction'];
        $payroll->update($data);

        return redirect()->route('payrolls')->with('success', 'Payroll updated successfully.');
    }

    public function show(Payroll $payroll)
    {
        return view('payrolls.show', ['payroll' => $payroll]);
    }

    public function edit(Payroll $payroll)
    {
        return view('payrolls.edit', ['payroll' => $payroll]);
    }
}
