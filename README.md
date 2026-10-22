# SmartPark - Enterprise Parking Management System

A professional, scalable web application and REST API built with **Laravel (PHP) + MySQL** using the Service-Repository pattern, Form Request validation, multi-guard authentication, and responsive Blade user interfaces.

---

## Architecture Overview

```text
smartpark/
├── app/
│   ├── Console/Commands/             # Scheduled tasks (CheckOverdueReservations)
│   ├── Exceptions/                   # Custom exception handling & API error transforms
│   ├── Helpers/                      # ApiResponse formatter & utility helpers
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                # Dashboard, Lots, Bookings, Users
│   │   │   ├── Customer/             # Dashboard, Bookings, Vehicles, Profile
│   │   │   ├── Auth/                 # Login, Register, Password Recovery
│   │   │   └── Api/V1/               # Auth, Lots, Bookings REST APIs
│   │   ├── Middleware/               # AdminMiddleware, CustomerMiddleware, ForceJsonResponse
│   │   ├── Requests/                 # Admin, Customer, Auth, API Form Requests
│   │   └── Resources/V1/             # JSON API Transformers
│   ├── Models/                       # User, Role, ParkingLot, ParkingSpot, Vehicle, Booking, Payment
│   ├── Notifications/                # Multi-channel Booking & Overdue alerts
│   ├── Providers/                    # App, Repository, and Route Providers
│   ├── Repositories/                 # Contracts & Eloquent implementations
│   └── Services/                     # Core business logic (Lots, Bookings, Payments)
├── bootstrap/                        # App initialization & provider registration
├── config/                           # Application, Auth, Database, and Domain settings
├── database/
│   ├── factories/                    # Model factories for testing
│   ├── migrations/                   # Granular migrations with foreign keys & indexes
│   └── seeders/                      # Role, User, and Parking Lot demo seeds
├── public/                           # Web server entrypoint & assets
├── resources/views/                  # Responsive Blade templates (Admin, Customer, Auth)
├── routes/                           # Modular routing (web, admin, customer, auth, api, console)
├── storage/                          # Session, cache, and application logs
└── tests/                            # Feature and Unit tests
```

---

## Database Architecture & Relationships

- **roles**: `id`, `name`, `slug` (`admin`, `customer`, `operator`), `description`, timestamps.
- **users**: `id`, `role_id` (FK &rarr; `roles`), `name`, `email` (unique), `phone`, `password`, `is_active`, timestamps.
  - `User belongsTo Role`
  - `User hasMany Vehicles`
  - `User hasMany Bookings`
  - `User hasMany Payments`
- **parking_lots**: `id`, `name`, `code` (unique), `address`, `city`, `latitude`, `longitude`, `total_spots`, `hourly_rate`, `is_active`, timestamps.
  - `ParkingLot hasMany ParkingSpots`
  - `ParkingLot hasManyThrough Bookings`
- **parking_spots**: `id`, `parking_lot_id` (FK &rarr; `parking_lots`), `spot_number`, `floor`, `spot_type` (`compact`, `standard`, `ev`, `handicapped`, `truck`), `is_active`, `is_occupied`, `hourly_rate_override`, timestamps.
  - Unique constraint: `[parking_lot_id, spot_number]`
  - `ParkingSpot belongsTo ParkingLot`
  - `ParkingSpot hasMany Bookings`
- **vehicles**: `id`, `user_id` (FK &rarr; `users`), `license_plate` (unique), `make`, `model`, `color`, `vehicle_type`, timestamps.
  - `Vehicle belongsTo User`
  - `Vehicle hasMany Bookings`
- **bookings**: `id`, `booking_reference` (unique), `user_id` (FK &rarr; `users`), `parking_spot_id` (FK &rarr; `parking_spots`), `vehicle_id` (FK &rarr; `vehicles`, nullable), `start_time`, `end_time`, `check_in_time`, `check_out_time`, `base_amount`, `penalty_amount`, `total_amount`, `status` (`pending`, `confirmed`, `active`, `completed`, `cancelled`, `overdue`), `cancellation_reason`, timestamps.
  - `Booking belongsTo User`
  - `Booking belongsTo ParkingSpot`
  - `Booking belongsTo Vehicle`
  - `Booking hasOne Payment`
- **payments**: `id`, `booking_id` (FK &rarr; `bookings`), `user_id` (FK &rarr; `users`), `transaction_reference` (unique), `payment_method`, `amount`, `status` (`pending`, `paid`, `failed`, `refunded`), `gateway_response`, `paid_at`, timestamps.
  - `Payment belongsTo Booking`
  - `Payment belongsTo User`

---

## Demo Credentials

| Role | Email | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin@smartpark.test` | `Admin@123456` |
| **Customer** | `customer@smartpark.test` | `Customer@123456` |

---

## Installation & Setup Instructions

### 1. Requirements
- PHP >= 8.2 with `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json` extensions.
- Composer
- MySQL 8.0+ or MariaDB 10.4+

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Setup
Configure your MySQL credentials in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smartpark
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

Create the MySQL database if it doesn't already exist:
```sql
CREATE DATABASE smartpark CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Key Generation & Migrations
```bash
php artisan key:generate
php artisan migrate --seed
```

### 5. Run the Application
```bash
php artisan serve
```
Access the application in your browser at `http://127.0.0.1:8000`.

---

## REST API V1 Endpoints

All responses follow a consistent JSON payload structure:
```json
{
  "success": true,
  "message": "Operation completed successfully.",
  "data": { ... },
  "timestamp": "2026-09-21T23:00:00Z"
}
```

### Authentication & Public Endpoints
- `POST /api/v1/auth/login`: Authenticate and receive Sanctum Bearer token.
- `POST /api/v1/auth/register`: Register new customer account and receive token.
- `GET /api/v1/parking-lots`: List active parking lots with available spot counts.
- `GET /api/v1/parking-lots/{id}`: Detailed lot metadata and spot layouts.
- `GET /api/v1/parking-lots/{id}/spots`: Real-time list of available spots.

### Protected Endpoints (`Authorization: Bearer <token>`)
- `GET /api/v1/auth/profile`: Authenticated user profile.
- `POST /api/v1/auth/logout`: Invalidate current token.
- `GET /api/v1/bookings`: Paginated customer booking history.
- `POST /api/v1/bookings`: Reserve a parking spot.
- `GET /api/v1/bookings/{reference}`: Booking details.
- `POST /api/v1/bookings/{reference}/check-in`: Gate check-in scan.
- `POST /api/v1/bookings/{reference}/check-out`: Gate check-out with automatic overtime penalty calculation.
- `POST /api/v1/bookings/{reference}/cancel`: Cancel reservation.

---

## Automated Background Tasks
To run the automated overdue reservation scanner manually:
```bash
php artisan parking:check-overdue
```
Or start the Laravel scheduler worker:
```bash
php artisan schedule:work
```
