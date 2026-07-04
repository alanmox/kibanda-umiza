<div align="center">

  <!-- 
    ════════════════════════════════════════════════════════════
                    SYSTEM INITIALIZATION SEQUENCE
    ════════════════════════════════════════════════════════════
    PROFILE: ALLAN MOX 
    STATUS: ACTIVE | DEPLOYMENT ENGINEER
    THEME: NEO-MATRIX | BONGO TECH ECOSYSTEM
    ════════════════════════════════════════════════════════════
  -->

  <!-- 🚀 ANIMATED TERMINAL HEADER -->
   <a href="https://git.io/typing-svg">
    <img src="https://readme-typing-svg.demolab.com?font=Fira+Code&weight=500&size=32&duration=2000&pause=800&color=00FF9D&center=true&vCenter=true&width=600&height=70&lines=ALLANMOX;Full+Stack+%7C+Deployment+Engineer;IT+Solutions+Architect;Bongo+Tech+%E2%80%A2+Global+Scale" 
         alt="Typing SVG: ALLANMOX – Full Stack | Deployment | Bongo Tech" />
  </a>

  <!-- 
    ████████████████████████████████████████████████████████████
    DYNAMIC CONTRIBUTION VISUALIZATION - PRODUCTION STREAMS
    ████████████████████████████████████████████████████████████
  -->
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/platane/platane/output/github-contribution-grid-snake-dark.svg">
    <source media="(prefers-color-scheme: light)" srcset="https://raw.githubusercontent.com/platane/platane/output/github-contribution-grid-snake.svg">
    <img width="100%" alt="GitHub contribution grid snake animation - visualizing deployment activity streams" src="https://raw.githubusercontent.com/platane/platane/output/github-contribution-grid-snake.svg">
  </picture>
  
  <!-- System status indicator -->
  <table>
    <tr>
      <td align="center">
        <code>⚡ PRODUCTION STREAMS: ACTIVE</code>
      </td>
      <td align="center">
        <code>🔄 DEPLOYMENT PIPELINE: OPTIMAL</code>
      </td>
      <td align="center">
        <code>🌐 BONGO TECH: SCALING</code>
      </td>
    </tr>
  </table>

  <br>

  <!-- 
    📱 SOCIAL PRESENCE MATRIX 
    High-visibility badges with enhanced styling
  -->
  <table>
    <tr>
      <td align="center">
        <a href="https://www.tiktok.com/@allan_tech441?_r=1&_t=ZS-93qwaaCjqE8">
          <img src="https://img.shields.io/badge/TikTok-@allan__tech-000000?style=for-the-badge&logo=tiktok&logoColor=white&labelColor=0a0a0a" alt="TikTok: @allan_tech" />
        </a>
      </td>
      <td align="center">
        <a href="https://youtube.com/@allantech441?si=cs67y5PrnnXHm7iz">
          <img src="https://img.shields.io/badge/YouTube-@allantech441-FF0000?style=for-the-badge&logo=youtube&logoColor=white&labelColor=cc0000" alt="YouTube: @allantech441" />
        </a>
      </td>
      <td align="center">
        <a href="https://www.instagram.com/alanmox8?igsh=MXQ3a3hlaWF5NTBzdw==">
          <img src="https://img.shields.io/badge/Instagram-@alanmox8-E4405F?style=for-the-badge&logo=instagram&logoColor=white&labelColor=d43f5a" alt="Instagram: @alanmox8" />
        </a>
      </td>
    </tr>
  </table>

</div>

<!-- 
  ════════════════════════════════════════════════════════════
                    VISUAL SEPARATOR - CORE INIT
  ════════════════════════════════════════════════════════════
-->
<img src="https://user-images.githubusercontent.com/73097560/115834477-dbab4500-a447-11eb-908a-139a6edaec5c.gif" width="100%">

<!-- 
  ╔═══════════════════════════════════════════════════════════╗
  ║                SYSTEM IDENTITY & CAPABILITIES              ║
  ╚═══════════════════════════════════════════════════════════╝
-->

<!-- SYSTEM STATUS CARD -->


<br/>



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
