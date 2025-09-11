# Hypersender Challenge

This repository contains my implementation of the **Hypersender Laravel challenge**.  
It is built with Laravel, Filament, and Pest, focusing on clean business logic, performance, and test coverage.  

---

## 🚀 Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/MoatazSalah306/hypersender-challenge.git
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
  - Styled widgets with 30s polling for auto-refresh.  
- **Relation Managers**
  - At the company level → manage its drivers, vehicles, and trips directly.
  - At the driver level → manage trips assigned to that driver.
  - At the vehicle level → manage trips assigned to that vehicle.

---

## 🛠️ Design Decisions

- **Overlap Validation**: Implemented inside the `Trip` model via a helper method (`hasOverlap`) to ensure consistent business rules, regardless of whether trips are created through Filament or elsewhere. In Filament Relation Managers, we hook into the `before` lifecycle of actions to provide user-friendly notifications instead of raw exceptions.  
- **Caching + Polling**: KPI counts are cached for performance (to avoid recalculating heavy queries). Polling every 30 seconds ensures the dashboard remains updated even if cached values are slightly stale — a balanced tradeoff between accuracy and efficiency.  
- **Relation Managers**: Used at the company, driver, and vehicle levels to give admins a seamless way to manage related entities directly from a parent resource, improving usability.  
- **Query Optimization**: Leveraged Eloquent relationships and count methods to avoid N+1 queries in tables and widgets.  
- **SQLite**: Chosen as the default database for simplicity and portability in challenge evaluation.

---

## 📖 Assumptions

- Each trip belongs to exactly one company.  
- A driver or vehicle cannot be double-booked (overlapping trips are disallowed).  
- If no `end_time` is provided, it defaults to 2 hours after `start_time`.  
- Admins will primarily manage the system through the Filament admin panel.  
- Widget auto-refresh (polling) is acceptable to keep stats near real-time despite caching.  

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
- Widgets are cached for performance but also use 30s polling to keep data refreshed automatically.  

