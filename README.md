# Hypersender Challenge

This repository contains my implementation of the **Hypersender Laravel challenge**.  
It is built with Laravel, Filament, and Pest, focusing on clean business logic, performance, and test coverage.  

---

## 🚀 Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repo-url>
   cd hypersender-challenge
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   The project is configured to use **SQLite** by default.  
   You can adjust `.env` if you prefer MySQL/Postgres.

4. **Run migrations and seed data**
   ```bash
   php artisan migrate --seed
   ```

5. **Start the dev servers**
   ```bash
   php artisan serve
   npm run dev
   ```

---

## ✨ Features

- **Company Management** with related Drivers, Vehicles, and Trips.  
- **Trip Overlap Validation** → prevents assigning the same driver/vehicle to overlapping trips.  
- **Driver & Vehicle Availability** detection in real time.  
- **KPI Dashboard Widget** with:
  - Active Trips  
  - Available Drivers  
  - Available Vehicles  
  - Completed Trips this month  
- **Optimized Performance**  
  - Uses caching for KPI counts.  
  - Avoids N+1 queries in resources.  
- **UI Customization**  
  - Custom logo and app name.  
  - Updated admin panel colors.  
  - Styled widgets.  
- **Relation Managers** used in Filament resources for managing trips, drivers, and vehicles directly from the company page.

---

## 🧪 Testing

This project uses [Pest](https://pestphp.com/) for testing.  

Run the tests with:
```bash
php artisan test
```

### Covered Scenarios
- Trip overlap detection (both overlap and non-overlap cases).  
- Active trips via `activeNow` scope.  
- Completed trips via `completedThisMonth` scope.  
- Driver and Vehicle availability queries.  
- KPI widget counts.  

Tests are designed to cover **core business logic** with >80% coverage.  
*(Coverage reporting requires Xdebug or PCOV, but tests can be run without them.)*  

---

## 📦 Tech Stack

- Laravel 11  
- Filament v3 (admin panel)  
- Pest (testing)  
- SQLite (default database, easy setup)  

---

## 📌 Notes

- Project is not deployed yet; runs locally with `php artisan serve`.  
- Widgets are cached for performance but can be manually refreshed.  

