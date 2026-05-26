# LeadNexa School Finance Module

This Laravel project implements a school finance reporting system with the following core features:

- School admin authentication
- Income CRUD with fee receipt PDF generation
- Expense CRUD
- Payroll CRUD with automatic net salary calculation
- Dashboard totals for income, expenses, payroll, and net profit
- Date-range financial report filtering
- Downloadable financial summary PDF
- API endpoints for dashboard, income, expense, payroll, and reports

## Setup

1. Copy `.env.example` to `.env` and set your MySQL credentials.
2. Run `composer install`.
3. Run `php artisan key:generate`.
4. Run `php artisan migrate`.
5. Run `php artisan db:seed` to create the default school admin account.
6. Run `npm install`.
7. Run `npm run build`.
8. Start the app with `php artisan serve`.

## Default Admin User

- Email: `admin@leadnexa.test`
- Password: `password`

## Files Included

- `database/leadnexa.sql` — SQL schema and seeded admin account.
- `postman/leadnexa-school-finance.postman_collection.json` — Postman collection for API testing.

## Key Routes

### Authentication
- `GET /login`
- `POST /login`
- `POST /logout`

### Web pages
- `GET /dashboard`
- `GET /incomes`
- `GET /incomes/create`
- `GET /incomes/{income}`
- `GET /incomes/{income}/edit`
- `GET /expenses`
- `GET /expenses/create`
- `GET /expenses/{expense}`
- `GET /expenses/{expense}/edit`
- `GET /payrolls`
- `GET /payrolls/create`
- `GET /payrolls/{payroll}`
- `GET /payrolls/{payroll}/edit`
- `GET /reports`
- `GET /pdf/receipt/{income}`
- `GET /pdf/summary`

### API endpoints
- `GET /api/dashboard`
- `GET /api/reports/summary`
- `GET /api/incomes`
- `POST /api/incomes`
- `PUT /api/incomes/{income}`
- `GET /api/expenses`
- `POST /api/expenses`
- `PUT /api/expenses/{expense}`
- `GET /api/payrolls`
- `POST /api/payrolls`
- `PUT /api/payrolls/{payroll}`

## Notes

- Admin-only access is enforced using `is_admin` on the `users` table.
- PDF generation is handled by `barryvdh/laravel-dompdf`.
- Report filters are powered by the `/api/reports/summary` endpoint and update dynamically on the Reports page.

## Testing

- Use the provided Postman collection at `postman/leadnexa-school-finance.postman_collection.json`.
- Start with `Open Login Page` and then `Login Admin` to establish session cookies.

## SQL Dump

The included SQL file `database/leadnexa.sql` contains the table schema and a seeded admin user.


