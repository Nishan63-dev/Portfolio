<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::query();

        if ($request->filled('start_date')) {
            $query->whereDate('expense_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('expense_date', '<=', $request->input('end_date'));
        }

        $expenses = $query->orderBy('expense_date', 'desc')
            ->paginate($request->input('per_page', 10));

        return response()->json([
            'data' => $expenses->items(),
            'meta' => [
                'current_page' => $expenses->currentPage(),
                'last_page' => $expenses->lastPage(),
                'per_page' => $expenses->perPage(),
                'total' => $expenses->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $this->normalizeDateFields($request, ['expense_date']);

        $data = $request->validate([
            'expense_title' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:80'],
            'amount' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'expense_date' => ['required', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
        ]);

        $expense = Expense::create($data);

        return response()->json($expense, 201);
    }

    public function update(Request $request, Expense $expense)
    {
        $this->normalizeDateFields($request, ['expense_date']);

        $data = $request->validate([
            'expense_title' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:80'],
            'amount' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'expense_date' => ['required', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
        ]);

        $expense->update($data);

        return response()->json($expense);
    }

    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $expenses = Expense::orderBy('expense_date', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([
            ['Title', 'Category', 'Amount', 'Expense Date', 'Created At'],
        ]);

        foreach ($expenses as $rowIndex => $expense) {
            $sheet->fromArray([
                $expense->expense_title,
                $expense->category,
                number_format($expense->amount, 2),
                $expense->expense_date->format('Y-m-d'),
                $expense->created_at->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
            ], null, 'A' . ($rowIndex + 2));
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'expense-export-' . now(config('app.timezone'))->format('Ymd-His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
