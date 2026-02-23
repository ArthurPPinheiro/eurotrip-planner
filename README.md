# ✈ EuroTrip Planner

A collaborative trip planning platform built with Laravel 11. Plan your European adventure with friends — manage destinations, points of interest, hotels, reservations, and important documents all in one place.

---

## Features

- **Multi-user trips** — Create trips and invite friends with a unique invite code
- **Day-by-day itinerary** — Organize your trip day by day, add multiple cities per day
- **Per-city details** — Add hotels, points of interest, reservations, and notes to each city
- **Document vault** — Upload and store passports, visas, insurance, tickets per trip
- **Role-based access** — Trip owners can edit/delete; all members can contribute

---

## Requirements

- PHP 8.2+
- Composer
- SQLite (default, no setup needed) or MySQL

---

## Setup Instructions

### 1. Clone / Extract the project

```bash
cd eurotrip
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Set up environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Set up the database

**SQLite (easiest):**
```bash
touch database/database.sqlite
php artisan migrate
```

**MySQL:**
Edit `.env` and set your DB credentials, then:
```bash
php artisan migrate
```

### 5. Create storage symlink (for file uploads)

```bash
php artisan storage:link
```

### 6. Start the server

```bash
php artisan serve
```

Visit **http://localhost:8000** in your browser.

---

## Usage

1. **Register** an account at `/register`
2. **Create a trip** — set a name, description, and dates
3. **Share the invite code** with your friends (shown on the trip page)
4. Friends **join** via the code at the trips list page
5. **Add days** to the itinerary
6. **Add cities** to each day
7. **Add items** to each city: hotels, POIs, reservations, notes
8. **Upload documents** (passports, visas, insurance) in the Documents tab

---

## Project Structure

```
app/
  Http/Controllers/
    AuthController.php        — Login, register, logout
    TripController.php        — Trip CRUD, invite joining, add day
    DestinationController.php — City add/remove per day
    ActivityController.php    — Hotel/POI/reservation/comment add/remove
    DocumentController.php    — Document upload/download/delete
  Models/
    User.php        — Users with avatar colors
    Trip.php        — Trip with auto invite_code generation
    Day.php         — Day in a trip
    Destination.php — City within a day
    Activity.php    — Item within a city (hotel, poi, etc.)
    Document.php    — Uploaded document
  Policies/
    TripPolicy.php  — Authorization: view/edit/delete rules

database/migrations/     — All table definitions
resources/views/
  layouts/app.blade.php    — Main layout with nav
  layouts/guest.blade.php  — Auth layout
  auth/                    — Login & register
  trips/                   — Index, create, edit, show
  documents/               — Document vault
routes/web.php             — All routes
```

---

## Customization

- **Colors / fonts**: Edit CSS variables in `resources/views/layouts/app.blade.php`
- **Activity types**: Add types in `Activity::typeIcon()` and `typeLabel()` methods
- **Document types**: Add to the select options in `documents/index.blade.php` and `Document::typeIcon()`
- **File size limit**: Change `max:10240` in `DocumentController` (currently 10MB)

