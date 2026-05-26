@extends('layouts.app')

@php $pageTitle = 'Dashboard'; @endphp

@section('content')
<div class="space-y-6">
    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Total Income</p>
            <p id="income-total" class="mt-4 text-3xl font-semibold text-slate-900">₹0.00</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Total Expenses</p>
            <p id="expense-total" class="mt-4 text-3xl font-semibold text-slate-900">₹0.00</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Total Payroll</p>
            <p id="payroll-total" class="mt-4 text-3xl font-semibold text-slate-900">₹0.00</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Net Profit</p>
            <p id="net-profit" class="mt-4 text-3xl font-semibold text-slate-900">₹0.00</p>
        </div>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-xl font-semibold text-slate-900">Financial Summary</h2>
        <p class="text-sm text-slate-600">This dashboard shows current totals for income, expenses, payroll, and net profit.</p>
    </div>
</div>

<script>
    async function loadDashboard() {
        if (typeof axios === 'undefined') {
            console.error('Axios is not loaded yet.');
            return;
        }

        try {
            const response = await axios.get('/api/dashboard');
            const data = response.data;
            const incomeTotal = parseFloat(data.income_total) || 0;
            const expenseTotal = parseFloat(data.expense_total) || 0;
            const payrollTotal = parseFloat(data.payroll_total) || 0;
            const netProfit = parseFloat(data.net_profit) || 0;

            document.getElementById('income-total').textContent = '₹' + incomeTotal.toFixed(2);
            document.getElementById('expense-total').textContent = '₹' + expenseTotal.toFixed(2);
            document.getElementById('payroll-total').textContent = '₹' + payrollTotal.toFixed(2);
            document.getElementById('net-profit').textContent = '₹' + netProfit.toFixed(2);
        } catch (error) {
            console.error('Failed to load dashboard data:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', loadDashboard);
</script>
@endsection
