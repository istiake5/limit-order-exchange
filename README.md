Limit-Order Exchange Mini Engine

This is a mini limit-order exchange system built with Laravel 12 API and Vue 3 + Vite frontend.
Supports user registration/login, placing limit buy/sell orders, order matching, and real-time updates using Pusher & Laravel Echo.

Features

User authentication (login & registration) using Laravel Sanctum

Dashboard with:

USD & crypto asset balances

Limit order placement

Orderbook display

Order filtering by symbol/side/status

Order matching engine (full match only)

Commission handling (1.5%)

Real-time updates for order matches and cancellations via Pusher

Frontend built with Vue 3 Composition API + TailwindCSS

Responsive UI and notifications (toasts) on order events

Tech Stack

Backend: Laravel 12, MySQL/PostgreSQL

Frontend: Vue 3 (Composition API), Vite, TailwindCSS

Real-time: Pusher via Laravel Broadcasting

Authentication: Laravel Sanctum (SPA mode)

Installation
1. Clone the repository
git clone <your-repo-url> limit-order-exchange
cd limit-order-exchange

2. Backend setup (Laravel API)
cd backend
composer install
cp .env.example .env


Update .env with your database credentials:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=limit_order_exchange
DB_USERNAME=root
DB_PASSWORD=secret

SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost


Generate app key:

php artisan key:generate


Migrate database:

php artisan migrate


Seed database with dummy users and assets:

php artisan db:seed


Start Laravel backend:

php artisan serve


Backend will run at http://localhost:8000

3. Frontend setup (Vue 3 + Vite)
cd ../frontend
npm install


Run development server:

npm run dev


Frontend will run at http://localhost:5173

4. Login Credentials (Seeded Users)
Email	Password
alice@example.com
	password
bob@example.com
	password
charlie@example.com
	password
5. Testing the App

Visit frontend: http://localhost:5173

Login using seeded user credentials.

Dashboard shows:

Wallet balances

Limit order form

Open orders

Place a buy/sell order; orders will match automatically if a counter order exists.

Real-time updates appear via toasts and updated order/asset balances.

6. API Endpoints
Method	Endpoint	Description
POST	/api/login	Login user
GET	/api/profile	Get user balances and assets
GET	/api/orders?symbol=BTC	Get open orders for symbol
POST	/api/orders	Place a limit order
POST	/api/orders/{id}/cancel	Cancel an open order

Use Axios with withCredentials: true for authenticated API calls.

7. Real-time Events
Event Name	Description	Channel
OrderMatched	When a limit order is fully matched	private-user.{user_id}
OrderCancelled	When an order is cancelled	private-user.{user_id}
8. Project Structure
limit-order-exchange/
├─ backend/        # Laravel API
│  ├─ app/
│  ├─ database/
│  │  ├─ migrations/
│  │  ├─ seeders/
│  └─ routes/
├─ frontend/       # Vue 3 SPA
│  ├─ src/
│  │  ├─ views/      # Login, Register, Dashboard
│  │  ├─ components/ # Wallet, Orders, OrderForm
│  │  ├─ api/        # Axios instance
│  │  └─ router/
│  └─ tailwind.config.js
└─ README.md

9. Notes & Troubleshooting

CORS issues:

Ensure SANCTUM_STATEFUL_DOMAINS=localhost:5173 in .env

withCredentials: true is required in Axios for all API calls

CSRF cookie: Always call /sanctum/csrf-cookie before login request:

await api.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true })


Database errors: If migrations fail due to existing tables, run:

php artisan migrate:fresh --seed


Pusher: Replace .env values with your Pusher credentials:

BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_key
PUSHER_APP_SECRET=your_secret
PUSHER_APP_CLUSTER=mt1

10. GitHub Repository Guidelines

Root folder contains two folders: backend/ and frontend/

Include .gitignore for both backend and frontend:

Backend: ignore vendor/, .env

Frontend: ignore node_modules/, .env.local

Commit messages: Use clear messages, e.g., "Add order matching logic" or "Implement Vue dashboard".
