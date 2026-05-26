<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Income::query();

        if ($request->filled('start_date')) {
            $query->whereDate('payment_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('payment_date', '<=', $request->input('end_date'));
        }

        $incomes = $query->orderBy('payment_date', 'desc')
            ->paginate($request->input('per_page', 10));

        return response()->json([
            'data' => $incomes->items(),
            'meta' => [
                'current_page' => $incomes->currentPage(),
                'last_page' => $incomes->lastPage(),
                'per_page' => $incomes->perPage(),
                'total' => $incomes->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $this->normalizeDateFields($request, ['payment_date']);

        $data = $request->validate([
            'student_name' => ['required', 'string', 'max:100'],
            'fee_type' => ['required', 'string', 'max:80'],
            'amount' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'payment_date' => ['required', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
        ]);

        $data['receipt_number'] = Str::upper('RCPT-' . Str::random(8));

        $income = Income::create($data);

        return response()->json($income, 201);
    }

    public function update(Request $request, Income $income)
    {
        $this->normalizeDateFields($request, ['payment_date']);

        $data = $request->validate([
            'student_name' => ['required', 'string', 'max:100'],
            'fee_type' => ['required', 'string', 'max:80'],
            'amount' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'payment_date' => ['required', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
        ]);

        $income->update($data);

        return response()->json($income);
    }

    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $incomes = Income::orderBy('payment_date', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([
            ['Receipt', 'Student Name', 'Fee Type', 'Amount', 'Payment Date', 'Created At'],
        ]);

        foreach ($incomes as $rowIndex => $income) {
            $sheet->fromArray([
                $income->receipt_number,
                $income->student_name,
                $income->fee_type,
                number_format($income->amount, 2),
                $income->payment_date->format('Y-m-d'),
                $income->created_at->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
            ], null, 'A' . ($rowIndex + 2));
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'income-export-' . now(config('app.timezone'))->format('Ymd-His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
