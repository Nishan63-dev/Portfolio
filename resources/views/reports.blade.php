@extends('layouts.app')

@php $pageTitle = 'Reports'; @endphp

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Financial Reports</div>
        <div class="page-subtitle">Generate filtered financial summaries and download printable reports.</div>
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:10px;">
        <a href="{{ route('dashboard') }}" class="btn btn-outline btn-sm">Back to Dashboard</a>
    </div>
</div>

<div class="grid gap-6 xl:grid-cols-[1.5fr_1fr]">
    <div class="rounded-xl bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Report Filters</h2>
                <p class="text-sm text-slate-500">Use the filters below to narrow your financial summary.</p>
            </div>
            <a id="download-summary-pdf" href="{{ route('pdf.summary') }}" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer" download>Download Summary PDF</a>
        </div>

        <form id="report-filter-form" action="{{ route('reports') }}" method="GET" class="mt-6 grid gap-4 sm:grid-cols-3">
            <div class="form-group">
                <label class="form-label">Start Date</label>
                <input id="start-date" type="date" name="start_date" value="{{ old('start_date', $startDate ?? '') }}" class="form-control" />
                <small class="text-slate-500">Use the calendar picker or enter date in YYYY-MM-DD / DD-MM-YYYY.</small>
            </div>
            <div class="form-group">
                <label class="form-label">End Date</label>
                <input id="end-date" type="date" name="end_date" value="{{ old('end_date', $endDate ?? '') }}" class="form-control" />
                <small class="text-slate-500">Use the calendar picker or enter date in YYYY-MM-DD / DD-MM-YYYY.</small>
            </div>
            <div class="flex items-end justify-end gap-3">
                <button type="submit" class="btn btn-primary btn-sm">Apply Filter</button>
                <a href="{{ route('reports') }}" class="btn btn-outline btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Summary Snapshot</h2>
        <p class="text-sm text-slate-500">See the current totals based on your selected date range.</p>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <div class="rounded-2xl bg-slate-50 p-5">
                <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Income</p>
                <p id="income-total-value" class="mt-3 text-2xl font-semibold text-slate-900">₹{{ number_format($incomeTotal ?? 0, 2) }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-5">
                <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Expenses</p>
                <p id="expense-total-value" class="mt-3 text-2xl font-semibold text-slate-900">₹{{ number_format($expenseTotal ?? 0, 2) }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-5">
                <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Payroll</p>
                <p id="payroll-total-value" class="mt-3 text-2xl font-semibold text-slate-900">₹{{ number_format($payrollTotal ?? 0, 2) }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-5">
                <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Net Profit</p>
                <p id="net-profit-value" class="mt-3 text-2xl font-semibold text-slate-900">₹{{ number_format($netProfit ?? 0, 2) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="rounded-xl bg-white p-6 shadow-sm mt-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-slate-900">Report Details</h3>
            <p class="text-sm text-slate-500">Summary of income, expenses, and payroll records returned by the filter.</p>
        </div>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Incomes</p>
            <p id="income-count" class="mt-3 text-2xl font-semibold text-slate-900">{{ $incomes->count() }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Expenses</p>
            <p id="expense-count" class="mt-3 text-2xl font-semibold text-slate-900">{{ $expenses->count() }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Payrolls</p>
            <p id="payroll-count" class="mt-3 text-2xl font-semibold text-slate-900">{{ $payrolls->count() }}</p>
        </div>
    </div>
</div>

<div class="rounded-xl bg-white p-6 shadow-sm mt-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-slate-900">Detailed Report</h3>
            <p class="text-sm text-slate-500">View the records returned by your current filter.</p>
        </div>
    </div>

    <div class="mt-6 space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <div class="mb-4 flex items-center justify-between">
                <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Income Records</h4>
            </div>
            <div class="overflow-x-auto rounded-xl bg-white border border-slate-200">
                <table class="w-full min-w-[720px] divide-y divide-slate-200 text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-slate-900">
                        <tr>
                            <th class="px-4 py-3">Receipt</th>
                            <th class="px-4 py-3">Student</th>
                            <th class="px-4 py-3">Fee Type</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Payment Date</th>
                        </tr>
                    </thead>
                    <tbody id="report-income-rows" class="divide-y divide-slate-200">
                        <tr><td class="px-4 py-3 text-slate-500" colspan="5">Loading incomes...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <div class="mb-4 flex items-center justify-between">
                <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Expense Records</h4>
            </div>
            <div class="overflow-x-auto rounded-xl bg-white border border-slate-200">
                <table class="w-full min-w-[720px] divide-y divide-slate-200 text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-slate-900">
                        <tr>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Expense Date</th>
                        </tr>
                    </thead>
                    <tbody id="report-expense-rows" class="divide-y divide-slate-200">
                        <tr><td class="px-4 py-3 text-slate-500" colspan="4">Loading expenses...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <div class="mb-4 flex items-center justify-between">
                <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Payroll Records</h4>
            </div>
            <div class="overflow-x-auto rounded-xl bg-white border border-slate-200">
                <table class="w-full min-w-[720px] divide-y divide-slate-200 text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-slate-900">
                        <tr>
                            <th class="px-4 py-3">Employee</th>
                            <th class="px-4 py-3">Salary</th>
                            <th class="px-4 py-3">Bonus</th>
                            <th class="px-4 py-3">Deduction</th>
                            <th class="px-4 py-3">Net Salary</th>
                            <th class="px-4 py-3">Payroll Date</th>
                        </tr>
                    </thead>
                    <tbody id="report-payroll-rows" class="divide-y divide-slate-200">
                        <tr><td class="px-4 py-3 text-slate-500" colspan="6">Loading payrolls...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function formatCurrency(value) {
        return '₹' + Number(value).toFixed(2);
    }

    function buildPdfHref(params) {
        const url = new URL("{{ route('pdf.summary') }}", window.location.origin);
        Object.entries(params).forEach(([key, value]) => {
            if (value) {
                url.searchParams.set(key, value);
            }
        });
        return url.toString();
    }

    function renderReportRows(containerId, rows, renderRow) {
        const container = document.getElementById(containerId);
        if (!container) return;

        if (!rows.length) {
            container.innerHTML = `<tr><td class="px-4 py-3 text-slate-500" colspan="${container.closest('table').querySelectorAll('th').length}">No records found.</td></tr>`;
            return;
        }

        container.innerHTML = rows.map(renderRow).join('');
    }

    async function refreshReportSummary(startDate, endDate) {
        try {
            const response = await axios.get('/api/reports/summary', {
                params: {
                    start_date: startDate,
                    end_date: endDate,
                },
            });

            const data = response.data;

            document.getElementById('income-total-value').textContent = formatCurrency(data.income_total);
            document.getElementById('expense-total-value').textContent = formatCurrency(data.expense_total);
            document.getElementById('payroll-total-value').textContent = formatCurrency(data.payroll_total);
            document.getElementById('net-profit-value').textContent = formatCurrency(data.net_profit);

            document.getElementById('income-count').textContent = data.incomes.length;
            document.getElementById('expense-count').textContent = data.expenses.length;
            document.getElementById('payroll-count').textContent = data.payrolls.length;

            document.getElementById('download-summary-pdf').href = buildPdfHref({
                start_date: startDate,
                end_date: endDate,
            });

            renderReportRows('report-income-rows', data.incomes, income => `
                <tr>
                    <td class="px-4 py-3">${income.receipt_number}</td>
                    <td class="px-4 py-3">${income.student_name}</td>
                    <td class="px-4 py-3">${income.fee_type}</td>
                    <td class="px-4 py-3">${formatCurrency(income.amount)}</td>
                    <td class="px-4 py-3">${income.payment_date}</td>
                </tr>
            `);

            renderReportRows('report-expense-rows', data.expenses, expense => `
                <tr>
                    <td class="px-4 py-3">${expense.expense_title}</td>
                    <td class="px-4 py-3">${expense.category}</td>
                    <td class="px-4 py-3">${formatCurrency(expense.amount)}</td>
                    <td class="px-4 py-3">${expense.expense_date}</td>
                </tr>
            `);

            renderReportRows('report-payroll-rows', data.payrolls, payroll => `
                <tr>
                    <td class="px-4 py-3">${payroll.employee_name}</td>
                    <td class="px-4 py-3">${formatCurrency(payroll.salary)}</td>
                    <td class="px-4 py-3">${formatCurrency(payroll.bonus)}</td>
                    <td class="px-4 py-3">${formatCurrency(payroll.deduction)}</td>
                    <td class="px-4 py-3">${formatCurrency(payroll.net_salary)}</td>
                    <td class="px-4 py-3">${payroll.payroll_date}</td>
                </tr>
            `);
        } catch (error) {
            console.error('Unable to load report summary', error);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('report-filter-form');
        const downloadLink = document.getElementById('download-summary-pdf');
        const startDateInput = document.getElementById('start-date');
        const endDateInput = document.getElementById('end-date');

        function updateDownloadHref() {
            const selectedStart = startDateInput.value;
            const selectedEnd = endDateInput.value;
            downloadLink.href = buildPdfHref({
                start_date: selectedStart,
                end_date: selectedEnd,
            });
        }

        function isValidDate(value) {
            return value === '' || /^\d{4}-\d{2}-\d{2}$/.test(value);
        }

        downloadLink.addEventListener('click', function (event) {
            const selectedStart = startDateInput.value;
            const selectedEnd = endDateInput.value;

            if (!isValidDate(selectedStart) || !isValidDate(selectedEnd)) {
                event.preventDefault();
                alert('Please enter valid dates in YYYY-MM-DD format before downloading the summary.');
                return;
            }

            updateDownloadHref();
        });

        updateDownloadHref();
        refreshReportSummary(startDateInput.value, endDateInput.value);

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const selectedStart = startDateInput.value;
            const selectedEnd = endDateInput.value;

            refreshReportSummary(selectedStart, selectedEnd);
        });
    });
</script>
@endsection
