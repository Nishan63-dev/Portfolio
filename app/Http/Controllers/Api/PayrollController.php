<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::query();

        if ($request->filled('start_date')) {
            $query->whereDate('payroll_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('payroll_date', '<=', $request->input('end_date'));
        }

        $payrolls = $query->orderBy('payroll_date', 'desc')
            ->paginate($request->input('per_page', 10));

        return response()->json([
            'data' => $payrolls->items(),
            'meta' => [
                'current_page' => $payrolls->currentPage(),
                'last_page' => $payrolls->lastPage(),
                'per_page' => $payrolls->perPage(),
                'total' => $payrolls->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $this->normalizeDateFields($request, ['payroll_date']);

        $data = $request->validate([
            'employee_name' => ['required', 'string', 'max:100'],
            'salary' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'bonus' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'deduction' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'payroll_date' => ['required', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
        ]);

        $data['net_salary'] = $data['salary'] + $data['bonus'] - $data['deduction'];

        $payroll = Payroll::create($data);

        return response()->json($payroll, 201);
    }

    public function update(Request $request, Payroll $payroll)
    {
        $this->normalizeDateFields($request, ['payroll_date']);

        $data = $request->validate([
            'employee_name' => ['required', 'string', 'max:100'],
            'salary' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'bonus' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'deduction' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'payroll_date' => ['required', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
        ]);

        $data['net_salary'] = $data['salary'] + $data['bonus'] - $data['deduction'];
        $payroll->update($data);

        return response()->json($payroll);
    }

    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $payrolls = Payroll::orderBy('payroll_date', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([
            ['Employee Name', 'Salary', 'Bonus', 'Deduction', 'Net Salary', 'Payroll Date', 'Created At'],
        ]);

        foreach ($payrolls as $rowIndex => $payroll) {
            $sheet->fromArray([
                $payroll->employee_name,
                number_format($payroll->salary, 2),
                number_format($payroll->bonus, 2),
                number_format($payroll->deduction, 2),
                number_format($payroll->net_salary, 2),
                $payroll->payroll_date->format('Y-m-d'),
                $payroll->created_at->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
            ], null, 'A' . ($rowIndex + 2));
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'payroll-export-' . now(config('app.timezone'))->format('Ymd-His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
