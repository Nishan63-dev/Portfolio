<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

abstract class Controller
{
    protected function normalizeDateString(?string $value): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        $value = trim($value);
        $formats = ['Y-m-d', 'd-m-Y', 'm-d-Y'];

        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat('!' . $format, $value, config('app.timezone'));
            } catch (\Exception $e) {
                continue;
            }

            if ($date && $date->format($format) === $value) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }

    protected function normalizeDateFields(Request $request, array $fields): void
    {
        foreach ($fields as $field) {
            if ($request->filled($field)) {
                $normalized = $this->normalizeDateString($request->input($field));

                if ($normalized !== null) {
                    $request->merge([$field => $normalized]);
                }
            }
        }
    }
}
