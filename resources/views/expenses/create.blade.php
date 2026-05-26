@extends('layouts.app')

@php $pageTitle = 'Add Expense'; @endphp

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Add Expense</div>
        <div class="page-subtitle">Record a new expense entry for school operations.</div>
    </div>
    <a href="{{ route('expenses') }}" class="btn btn-outline btn-sm">Back to Expense List</a>
</div>

<div class="card">
    <div class="card-body">
        <form id="expense-form" action="{{ route('expenses.store') }}" method="POST">
            @csrf
            <div id="expense-form-errors"></div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Expense Title</label>
                    <input type="text" name="expense_title" required maxlength="120" placeholder="Expense title" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" required maxlength="80" placeholder="Stationery, Utilities, etc." class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Amount</label>
                    <input type="number" name="amount" min="0" max="99999999.99" step="0.01" inputmode="decimal" required placeholder="0.00" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Expense Date</label>
                    <input type="date" name="expense_date" required class="form-control" />
                    <small class="text-slate-500">Use the calendar picker or enter date in YYYY-MM-DD / DD-MM-YYYY.</small>
                </div>
            </div>

            <div style="margin-top:16px;">
                <button type="submit" class="btn btn-primary">Create Expense</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('expense-form');
        const submitButton = form.querySelector('button[type="submit"]');
        const buttonText = submitButton.textContent;
        const errorsContainer = document.getElementById('expense-form-errors');
        let isSubmitting = false;

        function showErrors(errors) {
            errorsContainer.innerHTML = '<div class="rounded-xl bg-rose-50 border border-rose-100 p-4 text-rose-900 mb-6"><ul class="list-disc pl-5">' +
                Object.values(errors).flat().map(message => '<li>' + message + '</li>').join('') +
                '</ul></div>';
        }

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
                await axios.post('/api/expenses', payload);
                window.location.href = '{{ route("expenses") }}';
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    showErrors(error.response.data.errors || error.response.data);
                } else {
                    showErrors({ general: ['Unable to save expense. Please try again.'] });
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
