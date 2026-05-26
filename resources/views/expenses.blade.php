@extends('layouts.app')

@php $pageTitle = 'Expenses'; @endphp

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Expenses</div>
        <div class="page-subtitle">Track and manage school expense records.</div>
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:10px;">
        <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm">Add Expense</a>
        <a href="{{ route('export.expenses') }}" class="btn btn-outline btn-sm">Export XLSX</a>
    </div>
</div>

@if(session('success'))
    <div class="rounded-xl bg-emerald-50 border border-emerald-100 p-4 text-emerald-900 mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="rounded-xl bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-xl font-semibold text-slate-900">Expense Listing</h2>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[720px] divide-y divide-slate-200 text-left text-sm text-slate-700">
            <thead class="bg-slate-50 text-slate-900">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Amount</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody id="expense-table-body" class="divide-y divide-slate-200">
                @forelse($expenses as $expense)
                    <tr>
                        <td class="px-4 py-3">{{ $expense->expense_title }}</td>
                        <td class="px-4 py-3">{{ $expense->category }}</td>
                        <td class="px-4 py-3">₹ {{ number_format($expense->amount, 2) }}</td>
                        <td class="px-4 py-3">{{ $expense->expense_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-3" style="display:flex;gap:8px;flex-wrap:wrap;">
                            <a href="{{ route('expenses.show', ['expense' => $expense->uuid]) }}" class="btn btn-outline btn-xs">View</a>
                            <a href="{{ route('expenses.edit', ['expense' => $expense->uuid]) }}" class="btn btn-primary btn-xs">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-3 text-slate-600" colspan="5">No expenses recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $expenses->withQueryString()->links('vendor.pagination.custom') }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async function () {
        const tableBody = document.getElementById('expense-table-body');
        if (!tableBody) return;

        try {
            const response = await axios.get('/api/expenses', { params: { per_page: 100 } });
            const expenses = response.data.data;
            tableBody.innerHTML = expenses.map(expense => `
                <tr>
                    <td class="px-4 py-3">${expense.expense_title}</td>
                    <td class="px-4 py-3">${expense.category}</td>
                    <td class="px-4 py-3">₹ ${Number(expense.amount).toFixed(2)}</td>
                    <td class="px-4 py-3">${expense.expense_date}</td>
                    <td class="px-4 py-3" style="display:flex;gap:8px;flex-wrap:wrap;">
                        <a href="/expenses/${expense.uuid}" class="btn btn-outline btn-xs">View</a>
                        <a href="/expenses/${expense.uuid}/edit" class="btn btn-primary btn-xs">Edit</a>
                    </td>
                </tr>
            `).join('');
        } catch (error) {
            console.error('Failed to load expense list', error);
        }
    });
</script>
@endsection
