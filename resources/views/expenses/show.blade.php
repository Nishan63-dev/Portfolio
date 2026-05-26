@extends('layouts.app')

@php $pageTitle = 'View Expense'; @endphp

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">View Expense</div>
        <div class="page-subtitle">Expense details for {{ $expense->expense_title }}.</div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm">Add Expense</a>
        <a href="{{ route('expenses') }}" class="btn btn-outline btn-sm">Back to Expense List</a>
        <a href="{{ route('expenses.edit', ['expense' => $expense->uuid]) }}" class="btn btn-secondary btn-sm">Edit Expense</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
            <div class="form-group">
                <label class="form-label">Expense Title</label>
                <div class="form-control" style="background:#f8fafc;">{{ $expense->expense_title }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Category</label>
                <div class="form-control" style="background:#f8fafc;">{{ $expense->category }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Amount</label>
                <div class="form-control" style="background:#f8fafc;">₹ {{ number_format($expense->amount, 2) }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Expense Date</label>
                <div class="form-control" style="background:#f8fafc;">{{ $expense->expense_date->format('Y-m-d') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
