@extends('layouts.app')

@php $pageTitle = 'Edit Expense'; @endphp

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Edit Expense</div>
        <div class="page-subtitle">Update the expense record.</div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm">Add Expense</a>
        <a href="{{ route('expenses') }}" class="btn btn-outline btn-sm">Back to Expense List</a>
        <a href="{{ route('expenses.show', ['expense' => $expense->uuid]) }}" class="btn btn-secondary btn-sm">View Expense</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form id="expense-edit-form" action="{{ route('expenses.update', ['expense' => $expense->uuid]) }}" method="POST">
            @csrf
            @method('PUT')
            <div id="expense-edit-errors"></div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Expense Title</label>
                    <input type="text" name="expense_title" value="{{ old('expense_title', $expense->expense_title) }}" required maxlength="120" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" value="{{ old('category', $expense->category) }}" required maxlength="80" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Amount</label>
                    <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}" min="0" max="99999999.99" step="0.01" inputmode="decimal" required class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Expense Date</label>
                    <input type="date" name="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required class="form-control" />
                    <small class="text-slate-500">Use the calendar picker or enter date in YYYY-MM-DD / DD-MM-YYYY.</small>
                </div>
            </div>

            <div style="margin-top:16px;">
                <button type="submit" class="btn btn-primary">Update Expense</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('expense-edit-form');
        const errorsContainer = document.getElementById('expense-edit-errors');

        function showErrors(errors) {
            errorsContainer.innerHTML = '<div class="rounded-xl bg-rose-50 border border-rose-100 p-4 text-rose-900 mb-6"><ul class="list-disc pl-5">' +
                Object.values(errors).flat().map(message => '<li>' + message + '</li>').join('') +
                '</ul></div>';
        }

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            errorsContainer.innerHTML = '';

            const formData = new FormData(form);
            const payload = Object.fromEntries(formData.entries());
            const url = new URL(form.action, window.location.origin);
            const apiUrl = url.pathname.replace(/^\/(expenses)\//, '/api/$1/');

            try {
                await axios.put(apiUrl, payload);
                window.location.href = '{{ route("expenses") }}';
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    showErrors(error.response.data.errors || error.response.data);
                } else {
                    showErrors({ general: ['Unable to update expense. Please try again.'] });
                }
            }
        });
    });
</script>
@endsection
