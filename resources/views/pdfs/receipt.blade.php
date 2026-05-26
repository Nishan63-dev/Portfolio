<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Receipt - {{ $income->receipt_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 10mm 12mm;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: #f0f2f5;
            color: #1a202c;
            padding: 0;
            margin: 0;
            font-size: 12px;
        }

        .receipt-wrapper {
            width: 100%;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            page-break-inside: avoid;
            padding: 16px 18px 20px;
        }

        .section-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 16px 0;
        }

        .meta-strip td {
            width: 25%;
            padding: 10px 12px;
            vertical-align: top;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .meta-strip td:last-child {
            border-right: 1px solid #e2e8f0;
        }

        .meta-strip .info-label {
            display: block;
            font-size: 10px;
            color: #718096;
            margin-bottom: 4px;
        }

        .meta-strip .info-value {
            font-size: 12px;
            font-weight: 700;
            color: #1a202c;
        }

        /* HEADER */
        .receipt-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 18px;
        }
        .header-table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: middle; padding: 0; border: none; }

        .school-icon {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: #E1F5EE;
            text-align: center; line-height: 30px;
            display: inline-block; vertical-align: middle;
            margin-right: 8px;
        }
        .school-icon-svg { width: 16px; height: 16px; vertical-align: middle; fill: none; stroke: #0F6E56; stroke-width: 1.8; }
        .school-name { font-size: 14px; font-weight: 700; color: #1a202c; display: inline; vertical-align: middle; }
        .school-sub { font-size: 10px; color: #718096; margin-top: 2px; padding-left: 38px; }

        .brand-right { text-align: right; width: 160px; }
        .paid-badge {
            display: inline-block;
            background: #E1F5EE; color: #0F6E56;
            font-size: 10px; font-weight: 700;
            padding: 2px 10px; border-radius: 99px;
            margin-bottom: 4px;
        }
        .receipt-number { font-size: 16px; font-weight: 700; color: #1a202c; }
        .receipt-number-label { font-size: 10px; color: #a0aec0; }

        /* INFO STRIP */
        .info-strip { width: 100%; border-collapse: collapse; border-bottom: 1px solid #e2e8f0; }
        .info-strip td { width: 33.33%; padding: 8px 14px; border-right: 1px solid #e2e8f0; vertical-align: top; }
        .info-strip td:last-child { border-right: none; }
        .info-label { font-size: 10px; color: #718096; margin-bottom: 2px; }
        .info-value { font-size: 12px; font-weight: 700; color: #1a202c; }

        /* STUDENT */
        .student-section { padding: 8px 18px; border-bottom: 1px solid #e2e8f0; }
        .student-table { width: 100%; border-collapse: collapse; }
        .student-table td { vertical-align: middle; padding: 0; border: none; }
        .student-avatar {
            width: 30px; height: 30px; border-radius: 50%;
            background: #EBF4FF; text-align: center; line-height: 30px;
            display: inline-block;
        }
        .student-icon { width: 14px; height: 14px; vertical-align: middle; stroke: #3182ce; fill: none; stroke-width: 1.8; }
        .student-label { font-size: 10px; color: #718096; margin-bottom: 1px; }
        .student-name { font-size: 13px; font-weight: 700; color: #1a202c; }

        /* PAYMENT TABLE */
        .payment-section { padding: 8px 18px; border-bottom: 1px solid #e2e8f0; }
        .section-heading { font-size: 10px; font-weight: 700; color: #718096; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 6px; }
        .items-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .items-table th { background: #f8fafc; text-align: left; font-size: 10px; font-weight: 700; color: #718096; padding: 6px 8px; border: 1px solid #e2e8f0; }
        .items-table th.text-right, .items-table td.text-right { text-align: right; }
        .items-table td { font-size: 12px; color: #2d3748; padding: 7px 8px; border: 1px solid #e2e8f0; }
        .items-table td.amount { font-weight: 700; }

        /* SUMMARY */
        .summary-section { padding: 8px 18px; border-bottom: 1px solid #e2e8f0; }
        .summary-inner { width: 100%; border-collapse: collapse; }
        .summary-inner > tbody > tr > td { vertical-align: bottom; padding: 0; border: none; }
        .amount-words-cell { padding-right: 12px !important; }
        .amount-words-box { background: #f8fafc; border-radius: 6px; padding: 8px 10px; }
        .amount-words-label { font-size: 10px; color: #718096; margin-bottom: 3px; }
        .amount-words-text { font-size: 11px; font-weight: 700; color: #1a202c; line-height: 1.4; }

        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { font-size: 11px; padding: 5px 8px; border: 1px solid #e2e8f0; }
        .totals-table .t-label { color: #718096; }
        .totals-table .t-value { text-align: right; font-weight: 700; color: #1a202c; }
        .totals-table .t-value.balance { color: #2f855a; }
        .totals-table tr.total-row { background: #E1F5EE; }
        .totals-table tr.total-row td { border-color: #9FE1CB; color: #085041; font-size: 12px; font-weight: 700; padding: 7px 8px; }
        .totals-table tr.total-row td.t-value { text-align: right; }

        .notes-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
            margin: 18px 18px 0;
            font-size: 11px;
            color: #4a5568;
            line-height: 1.5;
        }

        /* FOOTER */
        .receipt-footer { padding: 10px 18px; }
        .footer-inner { width: 100%; border-collapse: collapse; }
        .footer-inner td { vertical-align: bottom; padding: 0; border: none; }
        .footer-left { font-size: 11px; color: #718096; line-height: 1.6; }
        .footer-left .check { color: #2f855a; font-weight: bold; }
        .footer-left .thank-you { font-size: 10px; color: #a0aec0; margin-top: 2px; }
        .footer-right { text-align: center; width: 180px; }
        .signature-line { height: 32px; border-bottom: 1px solid #cbd5e0; margin-bottom: 4px; }
        .signature-label { font-size: 10px; color: #718096; }

        /* PRINT */
        @media print {
            body { background: #fff; padding: 0; margin: 0; }
            .receipt-wrapper {
                border: 1px solid #e2e8f0;
                border-radius: 0;
                max-width: 100%;
                width: 100%;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

<div class="receipt-wrapper">

    <!-- HEADER -->
    <div class="receipt-header">
        <table class="header-table">
            <tr>
                <td>
                    <div class="school-icon">
                        <svg class="school-icon-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" stroke-linecap="round" stroke-linejoin="round"/>
                            <polyline points="9 22 9 12 15 12 15 22" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span class="school-name">RISMA HIGH SCHOOL</span>
                    <div class="school-sub">P.O. Box 1216 &middot; 60100 Embu &middot; Official Fee Receipt</div>
                </td>
                <td class="brand-right">
                    <div class="paid-badge">&#10003; Paid</div>
                    <div class="receipt-number">{{ $income->receipt_number }}</div>
                    <div class="receipt-number-label">Receipt no.</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- INFO STRIP -->
    <table class="info-strip">
        <tr>
            <td>
                <div class="info-label">Date</div>
                <div class="info-value">{{ $income->payment_date->format('d M Y') }}</div>
            </td>
            <td>
                <div class="info-label">Payment mode</div>
                <div class="info-value">Cash</div>
            </td>
            <td>
                <div class="info-label">Fee type</div>
                <div class="info-value">{{ $income->fee_type }}</div>
            </td>
        </tr>
    </table>

    <div class="section-divider"></div>

    <table class="meta-strip">
        <tr>
            <td>
                <span class="info-label">Receipt Time</span>
                <span class="info-value">{{ $receiptTime }}</span>
            </td>
            <td>
                <span class="info-label">Received by</span>
                <span class="info-value">{{ $processedBy }}</span>
            </td>
            <td>
                <span class="info-label">Receipt No.</span>
                <span class="info-value">{{ $income->receipt_number }}</span>
            </td>
            <td>
                <span class="info-label">Issue Date</span>
                <span class="info-value">{{ now()->format('d M Y') }}</span>
            </td>
        </tr>
    </table>

    <div class="section-divider"></div>

    <!-- STUDENT -->
    <div class="student-section">
        <table class="student-table">
            <tr>
                <td style="width: 42px;">
                    <div class="student-avatar">
                        <svg class="student-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </td>
                <td>
                    <div class="student-label">Received from</div>
                    <div class="student-name">{{ $income->student_name }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- PAYMENT TABLE -->
    <div class="payment-section">
        <div class="section-heading">Payment Details</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 8%;">#</th>
                    <th style="width: 62%;">Particular</th>
                    <th class="text-right" style="width: 30%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $income->fee_type }}</td>
                    <td class="text-right amount">&#8377; {{ number_format($income->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- SUMMARY -->
    <div class="summary-section">
        <table class="summary-inner">
            <tr>
                <td class="amount-words-cell">
                    <div class="amount-words-box">
                        <div class="amount-words-label">Amount in words</div>
                        <div class="amount-words-text">{{ $amountInWords }} Rupees Only</div>
                    </div>
                </td>
                <td style="width: 220px;">
                    <table class="totals-table">
                        <tr>
                            <td class="t-label">Subtotal</td>
                            <td class="t-value">&#8377; {{ number_format($income->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="t-label">Balance</td>
                            <td class="t-value balance">&#8377; 0.00</td>
                        </tr>
                        <tr class="total-row">
                            <td>Total paid</td>
                            <td class="t-value">&#8377; {{ number_format($income->amount, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="notes-box">
        <strong>Notes:</strong> This receipt is valid only when signed by the authorized representative and after full payment has been received. Please keep this document for your records.
    </div>

    <!-- FOOTER -->
    <div class="receipt-footer">
        <table class="footer-inner">
            <tr>
                <td class="footer-left">
                    <span class="check">&#10003;</span> Generated by Risma School Finance
                    <div class="thank-you">Thank you for your payment.</div>
                </td>
                <td class="footer-right">
                    <div class="signature-line"></div>
                    <div class="signature-label">Authorized Signature</div>
                </td>
            </tr>
        </table>
    </div>

</div>

</body>
</html>