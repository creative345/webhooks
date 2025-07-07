# Payment CRM Dashboard

A Laravel-based dashboard for managing and tracking payment webhooks, with advanced filtering, search, and reporting features.

## Features
- Payments table with AJAX loading
- Filters: status, payment method, gateway, date range, and more
- Custom date range filter
- Responsive UI
- Database-driven (MySQL)

## Getting Started

### 1. Clone the repository
```bash
git clone https://github.com/creative345/webhooks.git
cd webhooks
```

### 2. Install dependencies
```bash
composer install
npm install && npm run build
```

### 3. Environment setup
- Copy `.env.example` to `.env`:
  ```bash
  cp .env.example .env
  ```
- Update your `.env` file with your local database and mail settings.
- Generate an application key:
  ```bash
  php artisan key:generate
  ```

### 4. Database setup
- Create a MySQL database (default: `webhooks`)
- Update DB settings in `.env` if needed
- Run migrations and seeders:
  ```bash
  php artisan migrate --seed
  ```

### 5. Run the application
```bash
php artisan serve
```
Visit [http://localhost:8000](http://localhost:8000) in your browser.

## .env Example
See `.env.example` for all required environment variables. Example DB section:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webhooks
DB_USERNAME=root
DB_PASSWORD=
```

## Contributing
Pull requests are welcome! For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](LICENSE)
