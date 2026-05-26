@extends('layouts.app')

@php $pageTitle = 'View Payroll'; @endphp

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">View Payroll</div>
        <div class="page-subtitle">Salary details for {{ $payroll->employee_name }}.</div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('payrolls.create') }}" class="btn btn-primary btn-sm">Add Payroll</a>
        <a href="{{ route('payrolls') }}" class="btn btn-outline btn-sm">Back to Payroll List</a>
        <a href="{{ route('payrolls.edit', ['payroll' => $payroll->uuid]) }}" class="btn btn-secondary btn-sm">Edit Payroll</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
            <div class="form-group">
                <label class="form-label">Employee Name</label>
                <div class="form-control" style="background:#f8fafc;">{{ $payroll->employee_name }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Salary</label>
                <div class="form-control" style="background:#f8fafc;">₹ {{ number_format($payroll->salary, 2) }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Bonus</label>
                <div class="form-control" style="background:#f8fafc;">₹ {{ number_format($payroll->bonus, 2) }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Deduction</label>
                <div class="form-control" style="background:#f8fafc;">₹ {{ number_format($payroll->deduction, 2) }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Net Salary</label>
                <div class="form-control" style="background:#f8fafc;">₹ {{ number_format($payroll->net_salary, 2) }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Payroll Date</label>
                <div class="form-control" style="background:#f8fafc;">{{ $payroll->payroll_date->format('Y-m-d') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
