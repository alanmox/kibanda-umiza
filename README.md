# Kibanda Umiza - Football Viewing Center Management System

PHP OOP web app for managing a football viewing center. Built with pure PHP OOP, MySQL, PDO, and Bootstrap 5.

## Access URLs

| Page | URL |
|------|-----|
| **Public Landing** | `http://localhost/kibanda-umiza/` |
| **Admin Panel** | `http://localhost/kibanda-umiza/?page=admin&action=login` |

## Default Admin Login

- **Username:** `admin`
- **Password:** `admin123`

> Change the password immediately after first login.

## Quick Start

1. Import database: `mysql -u root < database/schema.sql`
2. Edit `config/database.php` with your MySQL credentials.
3. Point Apache to the `public/` directory.

## Features

- Public landing page with today's & upcoming matches, prices, available seats
- Admin authentication (session-based, password hashing)
- Match management (add, edit, delete, search)
- Customer registration with encrypted PII (AES-256-CBC), auto 6-digit ticket, duplicate seat prevention
- Cash payment recording
- Daily reports (customers & revenue by date)
- Dashboard with stats (total matches, today's customers/revenue, available seats)
- Responsive football-themed UI (Bootstrap 5, green/dark palette)

## OOP Concepts

- **Classes & Objects** – All entities as classes
- **Constructors** – `__construct()` in every model
- **Encapsulation** – Private properties, public getters/setters
- **Inheritance** – Models extend `BaseModel`
- **Polymorphism** – Method overriding (`read()`, `getAll()`, `validate()`)
- **Abstraction** – `ModelInterface` + abstract `BaseModel`

## Security

- PDO prepared statements (SQL injection prevention)
- Password hashing (`password_hash` / `password_verify`)
- AES-256-CBC encryption for customer names & phones
- Input sanitization & XSS prevention

## Project Structure

```
├── config/database.php
├── classes/    (Database, Auth, Encryption, BaseModel, FootballMatch, Customer, Payment, Report)
├── views/      (landing, admin/*, partials/*)
├── assets/     (css/style.css, js/script.js)
├── database/schema.sql
├── public/index.php    (front controller)
└── .htaccess
```

## Database (3NF)

- `admins` – Admin credentials
- `matches` – Football matches
- `customers` – Customer registrations (FK: match_id)
- `payments` – Payment records (FK: customer_id, match_id)
