<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Expense;
use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function incomeReceipt(Income $income)
    {
        $amountInWords = $this->numberToWords((int) round((float) $income->amount));
        $receiptTime = optional($income->created_at)->format('h:i A') ?? now()->format('h:i A');
        $processedBy = auth()->user()?->name ?? 'Finance Office';

        $pdf = Pdf::loadView('pdfs.receipt', [
            'income' => $income,
            'amountInWords' => $amountInWords,
            'receiptTime' => $receiptTime,
            'processedBy' => $processedBy,
        ]);

        return $pdf->stream('receipt-' . $income->receipt_number . '.pdf');
    }

    private function numberToWords(int $number): string
    {
        if ($number === 0) {
            return 'Zero';
        }

        $map = [
            0 => 'Zero',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety',
        ];

        if ($number < 21) {
            return $map[$number];
        }

        if ($number < 100) {
            $tens = (int) floor($number / 10) * 10;
            $remainder = $number % 10;
            return $map[$tens] . ($remainder ? ' ' . $map[$remainder] : '');
        }

        if ($number < 1000) {
            $hundreds = (int) floor($number / 100);
            $remainder = $number % 100;
            return $map[$hundreds] . ' Hundred' . ($remainder ? ' ' . $this->numberToWords($remainder) : '');
        }

        $units = [
            1000000 => 'Million',
            1000 => 'Thousand',
        ];

        foreach ($units as $value => $name) {
            if ($number >= $value) {
                $count = (int) floor($number / $value);
                $remainder = $number % $value;
                return $this->numberToWords($count) . ' ' . $name . ($remainder ? ' ' . $this->numberToWords($remainder) : '');
            }
        }

        return '';
    }

    public function financialSummary(Request $request)
    {
        $this->normalizeDateFields($request, ['start_date', 'end_date']);

        $request->validate([
            'start_date' => ['nullable', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $incomeQuery = Income::query();
        $expenseQuery = Expense::query();
        $payrollQuery = Payroll::query();

        if ($startDate) {
            $incomeQuery->whereDate('payment_date', '>=', $startDate);
            $expenseQuery->whereDate('expense_date', '>=', $startDate);
            $payrollQuery->whereDate('payroll_date', '>=', $startDate);
        }

        if ($endDate) {
            $incomeQuery->whereDate('payment_date', '<=', $endDate);
            $expenseQuery->whereDate('expense_date', '<=', $endDate);
            $payrollQuery->whereDate('payroll_date', '<=', $endDate);
        }

        $incomes = $incomeQuery->orderBy('payment_date', 'desc')->get();
        $expenses = $expenseQuery->orderBy('expense_date', 'desc')->get();
        $payrolls = $payrollQuery->orderBy('payroll_date', 'desc')->get();

        $incomeTotal = $incomes->sum('amount');
        $expenseTotal = $expenses->sum('amount');
        $payrollTotal = $payrolls->sum('net_salary');
        $netProfit = $incomeTotal - ($expenseTotal + $payrollTotal);

        $pdf = Pdf::loadView('pdfs.summary', [
            'incomes' => $incomes,
            'expenses' => $expenses,
            'payrolls' => $payrolls,
            'incomeTotal' => $incomeTotal,
            'expenseTotal' => $expenseTotal,
            'payrollTotal' => $payrollTotal,
            'netProfit' => $netProfit,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        return $pdf->download('financial-summary.pdf');
    }
}
