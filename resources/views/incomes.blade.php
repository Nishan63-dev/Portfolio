@extends('layouts.app')

@php $pageTitle = 'Income'; @endphp

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Income</div>
        <div class="page-subtitle">Review payments and receipts for school income.</div>
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:10px;">
        <a href="{{ route('incomes.create') }}" class="btn btn-primary btn-sm">Add Income</a>
        <a href="{{ route('export.incomes') }}" class="btn btn-outline btn-sm">Export XLSX</a>
    </div>
</div>

@if(session('success'))
    <div class="rounded-xl bg-emerald-50 border border-emerald-100 p-4 text-emerald-900 mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="rounded-xl bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-xl font-semibold text-slate-900">Income Listing</h2>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[720px] divide-y divide-slate-200 text-left text-sm text-slate-700">
            <thead class="bg-slate-50 text-slate-900">
                <tr>
                    <th class="px-4 py-3">Student</th>
                    <th class="px-4 py-3">Fee Type</th>
                    <th class="px-4 py-3">Amount</th>
                    <th class="px-4 py-3">Payment Date</th>
                    <th class="px-4 py-3">Receipt</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody id="income-table-body" class="divide-y divide-slate-200">
                @forelse($incomes as $income)
                    <tr>
                        <td class="px-4 py-3">{{ $income->student_name }}</td>
                        <td class="px-4 py-3">{{ $income->fee_type }}</td>
                        <td class="px-4 py-3">₹ {{ number_format($income->amount, 2) }}</td>
                        <td class="px-4 py-3">{{ $income->payment_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-3"><a href="{{ route('pdf.receipt', $income) }}" target="_blank" rel="noopener noreferrer" class="text-slate-900 underline">Receipt</a></td>
                        <td class="px-4 py-3" style="display:flex;gap:8px;flex-wrap:wrap;">
                            <a href="{{ route('incomes.show', $income) }}" class="btn btn-outline btn-xs">View</a>
                            <a href="{{ route('incomes.edit', $income) }}" class="btn btn-primary btn-xs">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-3 text-slate-600" colspan="6">No incomes recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $incomes->withQueryString()->links('vendor.pagination.custom') }}
    </div>
</div>

<script>
    function formatDateValue(value) {
        if (!value) {
            return '';
        }

        let dateString = String(value);

        if (dateString.includes('T')) {
            dateString = dateString.split('T')[0];
        }

        if (dateString.includes(' ')) {
            dateString = dateString.split(' ')[0];
        }

        return dateString;
    }

    document.addEventListener('DOMContentLoaded', async function () {
        const tableBody = document.getElementById('income-table-body');
        if (!tableBody) return;

        try {
            const response = await axios.get('/api/incomes', { params: { per_page: 100 } });
            const incomes = response.data.data;
            tableBody.innerHTML = incomes.map(income => `
                <tr>
                    <td class="px-4 py-3">${income.student_name}</td>
                    <td class="px-4 py-3">${income.fee_type}</td>
                    <td class="px-4 py-3">₹ ${Number(income.amount).toFixed(2)}</td>
                    <td class="px-4 py-3">${formatDateValue(income.payment_date)}</td>
                    <td class="px-4 py-3"><a href="/pdf/receipt/${income.receipt_number}" target="_blank" rel="noopener noreferrer" class="text-slate-900 underline">Receipt</a></td>
                    <td class="px-4 py-3" style="display:flex;gap:8px;flex-wrap:wrap;">
                        <a href="/incomes/${income.receipt_number}" class="btn btn-outline btn-xs">View</a>
                        <a href="/incomes/${income.receipt_number}/edit" class="btn btn-primary btn-xs">Edit</a>
                    </td>
                </tr>
            `).join('');
        } catch (error) {
            console.error('Failed to load income list', error);
        }
    });
</script>
@endsection
