Exchange Rates Mini Project
A simple Vue 3 + Laravel + Inertia.js SPA for displaying exchange rates with infinite scrolling and date filtering.

Features

-   Laravel backend with paginated exchange rates API
-   Vue 3 frontend with Inertia.js for SPA experience
-   Lazy loading / infinite scroll of rates (12 per page)
-   Filter rates by date using date picker
-   Loading spinner for smooth UX

Requirements

-   PHP 8.x
-   Composer
-   Node.js (v16+ recommended)
-   MYSQL

Setup Instructions

Backend Setup (Laravel)

-   Install PHP dependencies
    -   composer install
-   Update your database and other environment variables in .env.
-   Run backend server : php artisan serve

Frontend Setup (Vue 3 + Inertia.js)

-   Install Node dependencies : npm install
-   Run development server : npm run dev
-   Access the app : http://localhost:8000

Usage

-   Use the date picker to filter rates by effective date.
-   Scroll down to automatically load more rates (12 per page).
-   Spinner shows when loading data.
-   "No more data" message when all pages loaded.

Notes

-   Make sure your database has rates table with effective_date column.
-   Pagination is handled by Laravel backend and frontend requests next page on scroll.
-   Filtering uses Laravel's whereDate or whereBetween on effective_date.
