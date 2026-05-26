# LeadNexa School Finance Module

A Laravel-based school finance management system for tracking income, expenses, payroll, and generating printable PDF reports.

## Project Overview

This application includes:

- Admin authentication and protected finance workflows
- Income management with fee receipt generation as PDF
- Expense management for school operating costs
- Payroll management with automatic net salary calculation
- Dashboard with income, expense, payroll, and net profit totals
- Date-range filtered financial report page
- Downloadable financial summary PDF
- REST-style API endpoints backing the UI

## Requirements

- PHP 8.2+
- Composer
- MySQL or compatible database
- Node.js 18+ and npm
- Git

## Installation

1. Clone the repository:

```powershell
git clone https://github.com/<your-username>/<repo-name>.git
cd <repo-name>
```

2. Copy environment configuration:

```powershell
copy .env.example .env
```

3. Install PHP dependencies:

```powershell
composer install
```

4. Generate the application key:

```powershell
php artisan key:generate
```

5. Configure your database in `.env`:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Run migrations and seed the default admin user:

```powershell
php artisan migrate
php artisan db:seed
```

7. Install frontend dependencies:

```powershell
npm install
```

8. Build assets:

```powershell
npm run build
```

9. Start the application:

```powershell
php artisan serve
```

Then visit `http://127.0.0.1:8000`.

## Default Admin Credentials

- Email: `admin@leadnexa.test`
- Password: `password`

## Main Features

### Authentication
- School admin login and logout
- Protected finance pages for authorized users only

### Income
- Create, edit, list, and view income entries
- Receipt PDF generation for each income record
- Date-normalized payment dates for consistent storage

### Expense
- Create, edit, and list expense records
- Expense date normalization and validation

### Payroll
- Create, edit, and list payroll entries
- Automatic `net_salary = salary + bonus - deduction`
- Payroll date normalization and validation

### Reports
- Filter financial data by start and end date
- Live summary totals for income, expenses, payroll, and net profit
- Download summary as PDF

### Dashboard
- Live dashboard totals powered by `/api/dashboard`
- Income, expenses, payroll, and net profit displayed in the UI

## Routes

### Web Routes

| URL | Purpose |
|---|---|
| `/login` | Login page |
| `/logout` | Logout action |
| `/dashboard` | Finance dashboard |
| `/incomes` | Income list |
| `/incomes/create` | Add income |
| `/incomes/{income}` | View income |
| `/incomes/{income}/edit` | Edit income |
| `/expenses` | Expense list |
| `/expenses/create` | Add expense |
| `/expenses/{expense}` | View expense |
| `/expenses/{expense}/edit` | Edit expense |
| `/payrolls` | Payroll list |
| `/payrolls/create` | Add payroll |
| `/payrolls/{payroll}` | View payroll |
| `/payrolls/{payroll}/edit` | Edit payroll |
| `/reports` | Financial reports page |
| `/pdf/receipt/{income}` | Download income receipt PDF |
| `/pdf/summary` | Download financial summary PDF |

### API Routes

| Method | URL | Purpose |
|---|---|---|
| `GET` | `/api/dashboard` | Dashboard totals |
| `GET` | `/api/reports/summary` | Filtered report data |
| `GET` | `/api/incomes` | Income list for API |
| `POST` | `/api/incomes` | Create income |
| `PUT` | `/api/incomes/{income}` | Update income |
| `GET` | `/api/expenses` | Expense list for API |
| `POST` | `/api/expenses` | Create expense |
| `PUT` | `/api/expenses/{expense}` | Update expense |
| `GET` | `/api/payrolls` | Payroll list for API |
| `POST` | `/api/payrolls` | Create payroll |
| `PUT` | `/api/payrolls/{payroll}` | Update payroll |

## Technology Stack

- Laravel 12
- Blade templates
- Axios for AJAX requests
- Vite for frontend bundling
- DomPDF for PDF generation
- MySQL for persistence
- Laravel authentication and middleware

## Important Notes

- The app uses `barryvdh/laravel-dompdf` for PDF receipts and summary exports.
- Date inputs are normalized to `YYYY-MM-DD` before validation.
- Currency values are displayed with the rupee symbol and formatted with two decimals.

## Contributing

1. Create a branch:

```powershell
git checkout -b feature/your-feature-name
```

2. Commit your changes:

```powershell
git add .
git commit -m "Add feature or fix"
```

3. Push the branch:

```powershell
git push -u origin feature/your-feature-name
```

## Troubleshooting

- If assets do not load, run `npm run build` again.
- If you see PDF encoding issues, make sure the view uses `DejaVu Sans` and rupee symbol entities `&#8377;`.
- If login fails, verify the seeded admin credentials and database connection.

## License

This repository is provided as-is for the school finance module demonstration.


