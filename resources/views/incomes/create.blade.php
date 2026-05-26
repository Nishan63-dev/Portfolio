@extends('layouts.app')

@php $pageTitle = 'Add Income'; @endphp

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Add Income</div>
        <div class="page-subtitle">Create a new income entry with student and payment details.</div>
    </div>
    <a href="{{ route('incomes') }}" class="btn btn-outline btn-sm">Back to Income List</a>
</div>

<div class="card">
    <div class="card-body">
        <form id="income-form" action="{{ route('incomes.store') }}" method="POST">
            @csrf
            <div id="income-form-errors"></div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Student Name</label>
                    <input type="text" name="student_name" required maxlength="100" placeholder="Student name" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Fee Type</label>
                    <input type="text" name="fee_type" required maxlength="80" placeholder="Fee type" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Amount</label>
                    <input type="number" name="amount" min="0" max="99999999.99" step="0.01" inputmode="decimal" required placeholder="0.00" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="form-label">Payment Date</label>
                    <input type="date" name="payment_date" required class="form-control" />
                    <small class="text-slate-500">Use the calendar picker or enter date in YYYY-MM-DD / DD-MM-YYYY.</small>
                </div>
            </div>

            <div style="margin-top:16px;">
                <button type="submit" class="btn btn-primary">Create Income</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('income-form');
        const submitButton = form.querySelector('button[type="submit"]');
        const buttonText = submitButton.textContent;
        const errorsContainer = document.getElementById('income-form-errors');
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
                await axios.post('/api/incomes', payload);
                window.location.href = '{{ route("incomes") }}';
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    showErrors(error.response.data.errors || error.response.data);
                } else {
                    showErrors({ general: ['Unable to save income. Please try again.'] });
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
