@extends('layouts.app')

@php $pageTitle = 'Payroll'; @endphp

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Payroll</div>
        <div class="page-subtitle">Manage staff salary payments and payroll details.</div>
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:10px;">
        <a href="{{ route('payrolls.create') }}" class="btn btn-primary btn-sm">Add Payroll</a>
        <a href="{{ route('export.payrolls') }}" class="btn btn-outline btn-sm">Export XLSX</a>
    </div>
</div>

@if(session('success'))
    <div class="rounded-xl bg-emerald-50 border border-emerald-100 p-4 text-emerald-900 mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="rounded-xl bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-xl font-semibold text-slate-900">Payroll Listing</h2>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[720px] divide-y divide-slate-200 text-left text-sm text-slate-700">
            <thead class="bg-slate-50 text-slate-900">
                <tr>
                    <th class="px-4 py-3">Employee</th>
                    <th class="px-4 py-3">Salary</th>
                    <th class="px-4 py-3">Bonus</th>
                    <th class="px-4 py-3">Deduction</th>
                    <th class="px-4 py-3">Net Salary</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody id="payroll-table-body" class="divide-y divide-slate-200">
                @forelse($payrolls as $payroll)
                    <tr>
                        <td class="px-4 py-3">{{ $payroll->employee_name }}</td>
                        <td class="px-4 py-3">₹ {{ number_format($payroll->salary, 2) }}</td>
                        <td class="px-4 py-3">₹ {{ number_format($payroll->bonus, 2) }}</td>
                        <td class="px-4 py-3">₹ {{ number_format($payroll->deduction, 2) }}</td>
                        <td class="px-4 py-3">₹ {{ number_format($payroll->net_salary, 2) }}</td>
                        <td class="px-4 py-3">{{ $payroll->payroll_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-3" style="display:flex;gap:8px;flex-wrap:wrap;">
                            <a href="{{ route('payrolls.show', ['payroll' => $payroll->uuid]) }}" class="btn btn-outline btn-xs">View</a>
                            <a href="{{ route('payrolls.edit', ['payroll' => $payroll->uuid]) }}" class="btn btn-primary btn-xs">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-3 text-slate-600" colspan="7">No payroll records yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $payrolls->withQueryString()->links('vendor.pagination.custom') }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async function () {
        const tableBody = document.getElementById('payroll-table-body');
        if (!tableBody) return;

        try {
            const response = await axios.get('/api/payrolls', { params: { per_page: 100 } });
            const payrolls = response.data.data;
            tableBody.innerHTML = payrolls.map(payroll => `
                <tr>
                    <td class="px-4 py-3">${payroll.employee_name}</td>
                    <td class="px-4 py-3">₹ ${Number(payroll.salary).toFixed(2)}</td>
                    <td class="px-4 py-3">₹ ${Number(payroll.bonus).toFixed(2)}</td>
                    <td class="px-4 py-3">₹ ${Number(payroll.deduction).toFixed(2)}</td>
                    <td class="px-4 py-3">₹ ${Number(payroll.net_salary).toFixed(2)}</td>
                    <td class="px-4 py-3">${payroll.payroll_date}</td>
                    <td class="px-4 py-3" style="display:flex;gap:8px;flex-wrap:wrap;">
                        <a href="/payrolls/${payroll.uuid}" class="btn btn-outline btn-xs">View</a>
                        <a href="/payrolls/${payroll.uuid}/edit" class="btn btn-primary btn-xs">Edit</a>
                    </td>
                </tr>
            `).join('');
        } catch (error) {
            console.error('Failed to load payroll list', error);
        }
    });
</script>
@endsection
