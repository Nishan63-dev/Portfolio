@extends('layouts.app')

@php $pageTitle = 'Edit Income'; @endphp

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Edit Income</div>
        <div class="page-subtitle">Update the income payment details.</div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('incomes.create') }}" class="btn btn-primary btn-sm">Add Income</a>
        <a href="{{ route('incomes') }}" class="btn btn-outline btn-sm">Back to Income List</a>
        <a href="{{ route('incomes.show', $income) }}" class="btn btn-secondary btn-sm">View Income</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form id="income-edit-form" action="{{ route('incomes.update', $income) }}" method="POST">
            @csrf
            @method('PUT')
            <div id="income-edit-errors"></div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Student Name</label>
                    <input type="text" name="student_name" value="{{ old('student_name', $income->student_name) }}" required maxlength="100" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Fee Type</label>
                    <input type="text" name="fee_type" value="{{ old('fee_type', $income->fee_type) }}" required maxlength="80" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Amount</label>
                    <input type="number" name="amount" value="{{ old('amount', $income->amount) }}" min="0" max="99999999.99" step="0.01" inputmode="decimal" required class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Payment Date</label>
                    <input type="date" name="payment_date" value="{{ old('payment_date', $income->payment_date->format('Y-m-d')) }}" required class="form-control" />
                    <small class="text-slate-500">Use the calendar picker or enter date in YYYY-MM-DD / DD-MM-YYYY.</small>
                </div>
            </div>

            <div style="margin-top:16px;">
                <button type="submit" class="btn btn-primary">Update Income</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('income-edit-form');
        const errorsContainer = document.getElementById('income-edit-errors');

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
            const apiUrl = url.pathname.replace(/^\/(incomes)\//, '/api/$1/');

            try {
                await axios.put(apiUrl, payload);
                window.location.href = '{{ route("incomes") }}';
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    showErrors(error.response.data.errors || error.response.data);
                } else {
                    showErrors({ general: ['Unable to update income. Please try again.'] });
                }
            }
        });
    });
</script>
@endsection
