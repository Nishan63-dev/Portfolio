@extends('layouts.app')

@php $pageTitle = 'View Income'; @endphp

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">View Income</div>
        <div class="page-subtitle">Details for income receipt {{ $income->receipt_number }}.</div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('incomes.create') }}" class="btn btn-primary btn-sm">Add Income</a>
        <a href="{{ route('incomes') }}" class="btn btn-outline btn-sm">Back to Income List</a>
        <a href="{{ route('incomes.edit', $income) }}" class="btn btn-secondary btn-sm">Edit Income</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
            <div class="form-group">
                <label class="form-label">Student Name</label>
                <div class="form-control" style="background:#f8fafc;">{{ $income->student_name }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Fee Type</label>
                <div class="form-control" style="background:#f8fafc;">{{ $income->fee_type }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Amount</label>
                <div class="form-control" style="background:#f8fafc;">₹ {{ number_format($income->amount, 2) }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Payment Date</label>
                <div class="form-control" style="background:#f8fafc;">{{ $income->payment_date->format('Y-m-d') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
