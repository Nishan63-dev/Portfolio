@extends('layouts.app')

@php $pageTitle = 'Edit Payroll'; @endphp

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Edit Payroll</div>
        <div class="page-subtitle">Update salary and payroll details.</div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('payrolls.create') }}" class="btn btn-primary btn-sm">Add Payroll</a>
        <a href="{{ route('payrolls') }}" class="btn btn-outline btn-sm">Back to Payroll List</a>
        <a href="{{ route('payrolls.show', ['payroll' => $payroll->uuid]) }}" class="btn btn-secondary btn-sm">View Payroll</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form id="payroll-edit-form" action="{{ route('payrolls.update', ['payroll' => $payroll->uuid]) }}" method="POST">
            @csrf
            @method('PUT')
            <div id="payroll-edit-errors"></div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Employee Name</label>
                    <input type="text" name="employee_name" value="{{ old('employee_name', $payroll->employee_name) }}" required maxlength="100" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Salary</label>
                    <input type="number" name="salary" value="{{ old('salary', $payroll->salary) }}" min="0" max="99999999.99" step="0.01" inputmode="decimal" required class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Bonus</label>
                    <input type="number" name="bonus" value="{{ old('bonus', $payroll->bonus) }}" min="0" max="99999999.99" step="0.01" inputmode="decimal" required class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Deduction</label>
                    <input type="number" name="deduction" value="{{ old('deduction', $payroll->deduction) }}" min="0" max="99999999.99" step="0.01" inputmode="decimal" required class="form-control" />
                </div>
                <div class="form-group sm:col-span-2">
                    <label class="form-label">Payroll Date</label>
                    <input type="date" name="payroll_date" value="{{ old('payroll_date', $payroll->payroll_date->format('Y-m-d')) }}" required class="form-control" />
                    <small class="text-slate-500">Use the calendar picker or enter date in YYYY-MM-DD / DD-MM-YYYY.</small>
                </div>
            </div>

            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 mb-4">
                <p class="text-sm text-slate-500">Net Salary</p>
                <p id="edit-net-salary-display" class="mt-2 text-2xl font-semibold text-slate-900">₹{{ number_format($payroll->net_salary, 2) }}</p>
            </div>

            <div style="margin-top:16px;">
                <button type="submit" class="btn btn-primary">Update Payroll</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('payroll-edit-form');
        const errorsContainer = document.getElementById('payroll-edit-errors');
        const salaryInput = document.querySelector('input[name="salary"]');
        const bonusInput = document.querySelector('input[name="bonus"]');
        const deductionInput = document.querySelector('input[name="deduction"]');
        const netSalaryDisplay = document.getElementById('edit-net-salary-display');

        function calculateNetSalary() {
            const salary = parseFloat(salaryInput.value) || 0;
            const bonus = parseFloat(bonusInput.value) || 0;
            const deduction = parseFloat(deductionInput.value) || 0;
            netSalaryDisplay.textContent = '₹' + (salary + bonus - deduction).toFixed(2);
        }

        function showErrors(errors) {
            errorsContainer.innerHTML = '<div class="rounded-xl bg-rose-50 border border-rose-100 p-4 text-rose-900 mb-6"><ul class="list-disc pl-5">' +
                Object.values(errors).flat().map(message => '<li>' + message + '</li>').join('') +
                '</ul></div>';
        }

        [salaryInput, bonusInput, deductionInput].forEach(input => {
            input.addEventListener('input', calculateNetSalary);
        });

        calculateNetSalary();

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            errorsContainer.innerHTML = '';

            const formData = new FormData(form);
            const payload = Object.fromEntries(formData.entries());
            const url = new URL(form.action, window.location.origin);
            const apiUrl = url.pathname.replace(/^\/(payrolls)\//, '/api/$1/');

            try {
                await axios.put(apiUrl, payload);
                window.location.href = '{{ route("payrolls") }}';
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    showErrors(error.response.data.errors || error.response.data);
                } else {
                    showErrors({ general: ['Unable to update payroll. Please try again.'] });
                }
            }
        });
    });
</script>
@endsection
