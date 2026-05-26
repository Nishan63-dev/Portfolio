@extends('layouts.app')

@php $pageTitle = 'Add Payroll'; @endphp

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Add Payroll</div>
        <div class="page-subtitle">Create a new payroll entry for staff salary payment.</div>
    </div>
    <a href="{{ route('payrolls') }}" class="btn btn-outline btn-sm">Back to Payroll List</a>
</div>

<div class="card">
    <div class="card-body">
        <form id="payroll-form" action="{{ route('payrolls.store') }}" method="POST">
            @csrf
            <div id="payroll-form-errors"></div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Employee Name</label>
                    <input type="text" name="employee_name" required maxlength="100" placeholder="Employee name" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Salary</label>
                    <input type="number" name="salary" min="0" max="99999999.99" step="0.01" inputmode="decimal" required placeholder="0.00" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Bonus</label>
                    <input type="number" name="bonus" min="0" max="99999999.99" step="0.01" inputmode="decimal" required placeholder="0.00" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Deduction</label>
                    <input type="number" name="deduction" min="0" max="99999999.99" step="0.01" inputmode="decimal" required placeholder="0.00" class="form-control" />
                </div>
                <div class="form-group sm:col-span-2">
                    <label class="form-label">Payroll Date</label>
                    <input type="date" name="payroll_date" required class="form-control" />
                    <small class="text-slate-500">Use the calendar picker or enter date in YYYY-MM-DD / DD-MM-YYYY.</small>
                </div>
            </div>

            <div style="margin-top:16px;">
                <button type="submit" class="btn btn-primary">Create Payroll</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('payroll-form');
        const salaryInput = document.querySelector('input[name="salary"]');
        const bonusInput = document.querySelector('input[name="bonus"]');
        const deductionInput = document.querySelector('input[name="deduction"]');
        const netSalaryDisplay = document.getElementById('net-salary-display');
        const errorsContainer = document.getElementById('payroll-form-errors');

        function calculateNetSalary() {
            const salary = parseFloat(salaryInput.value) || 0;
            const bonus = parseFloat(bonusInput.value) || 0;
            const deduction = parseFloat(deductionInput.value) || 0;
            const net = salary + bonus - deduction;
            netSalaryDisplay.textContent = '₹' + net.toFixed(2);
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

        const submitButton = form.querySelector('button[type="submit"]');
        const buttonText = submitButton.textContent;
        let isSubmitting = false;

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            if (isSubmitting) {
                return;
            }
            isSubmitting = true;
            submitButton.disabled = true;
            submitButton.textContent = 'Saving...';
            errorsContainer.innerHTML = '';

            const formData = new FormData(form);
            const payload = Object.fromEntries(formData.entries());

            try {
                await axios.post('/api/payrolls', payload);
                window.location.href = '{{ route("payrolls") }}';
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    showErrors(error.response.data.errors || error.response.data);
                } else {
                    showErrors({ general: ['Unable to save payroll. Please try again.'] });
                }
            } finally {
                isSubmitting = false;
                submitButton.disabled = false;
                submitButton.textContent = buttonText;
            }
        });
    });
</script>
@endsection
