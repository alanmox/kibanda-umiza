# Kibanda Umiza - Football Viewing Center Management System

A complete PHP OOP web application for managing a football viewing center. Built with pure PHP OOP, MySQL, PDO, and Bootstrap 5.

## Features

- Public landing page with match schedules and ticket prices
- Admin authentication with secure session management
- Match management (CRUD with search)
- Customer registration with encrypted PII
- Automatic unique 6-digit ticket number generation
- Duplicate seat prevention per match
- Cash payment recording
- Daily reports (customers and revenue)
- Dashboard with statistics
- Responsive football-themed UI

## OOP Concepts Demonstrated

- **Classes & Objects**: All entities as PHP classes
- **Constructors**: `__construct()` in every class
- **Encapsulation**: Private/protected properties with getters/setters
- **Inheritance**: `Match`, `Customer`, `Payment` extend `BaseModel`
- **Polymorphism**: Method overriding (`read()`, `getAll()`, `validate()`)
- **Abstraction**: `ModelInterface` interface and `BaseModel` abstract class

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache with mod_rewrite enabled
- OpenSSL extension (for encryption)

## Installation

### 1. Database Setup

1. Open phpMyAdmin or MySQL CLI.
2. Run the SQL file:
   ```sql
   SOURCE /path/to/kibanda-umiza/database/schema.sql;
   ```

Or via command line:
```bash
mysql -u root -p < database/schema.sql
```

### 2. Configuration

Edit `config/database.php` and update:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'kibanda_umiza');
define('DB_USER', 'root');        // Change to your MySQL username
define('DB_PASS', '');            // Change to your MySQL password
define('ENCRYPTION_KEY', 'YourSecretKeyHere'); // Change for security
```

### 3. Deploy to Web Server

**Local (XAMPP/WAMP/MAMP):**
```bash
cp -r kibanda-umiza /path/to/htdocs/
```
Then access: `http://localhost/kibanda-umiza/`

**Apache Virtual Host:**
```apache
<VirtualHost *:80>
    ServerName kibanda.local
    DocumentRoot /var/www/html/kibanda-umiza/public
    <Directory /var/www/html/kibanda-umiza/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 4. Default Admin Login

- **Username:** `admin`
- **Password:** `admin123`

**Change the password immediately after first login.**

## Project Structure

```
kibanda-umiza/
├── config/
│   └── database.php          # Database configuration
├── classes/
│   ├── Database.php           # Singleton PDO connection
│   ├── Encryption.php         # AES-256-CBC encryption
│   ├── ModelInterface.php     # Interface for models
│   ├── BaseModel.php          # Abstract base model class
│   ├── Auth.php               # Authentication handler
│   ├── Match.php              # Match CRUD operations
│   ├── Customer.php           # Customer management
│   ├── Payment.php            # Payment recording
│   └── Report.php             # Report generation
├── views/
│   ├── landing.php            # Public landing page
│   ├── partials/              # Header/footer templates
│   └── admin/                 # Admin panel views
├── assets/
│   ├── css/style.css          # Custom styles
│   └── js/script.js           # JavaScript
├── database/
│   └── schema.sql             # Database schema + sample data
├── public/
│   └── index.php              # Front controller
├── .htaccess                  # URL rewriting
└── index.php                  # Redirect to public/
```

## Database Schema (3NF)

- `admins` - Admin credentials (PK: id)
- `matches` - Football matches (PK: id)
- `customers` - Customer registrations (PK: id, FK: match_id)
- `payments` - Payment records (PK: id, FK: customer_id, match_id)

## Security Features

- PDO prepared statements (prevents SQL injection)
- Password hashing with `password_hash()` / `password_verify()`
- AES-256-CBC encryption for customer PII
- Session-based authentication
- Input sanitization and validation
- XSS prevention with `htmlspecialchars()`

## AWS Deployment

### Option 1: EC2 Manual Deployment

1. **Launch EC2 Instance:**
   - Ubuntu 22.04 LTS (t2.micro for free tier)
   - Configure security group:
     - HTTP (80): 0.0.0.0/0
     - HTTPS (443): 0.0.0.0/0
     - SSH (22): Your IP only

2. **Connect to EC2:**
   ```bash
   ssh -i your-key.pem ubuntu@your-ec2-public-ip
   ```

3. **Install LAMP Stack:**
   ```bash
   sudo apt update && sudo apt upgrade -y
   sudo apt install apache2 mysql-server php php-mysql php-mbstring php-xml php-curl php-openssl -y
   sudo apt install libapache2-mod-php -y
   ```

4. **Configure MySQL:**
   ```bash
   sudo mysql
   CREATE DATABASE kibanda_umiza;
   CREATE USER 'kibanda_user'@'localhost' IDENTIFIED BY 'YourStrongPassword';
   GRANT ALL PRIVILEGES ON kibanda_umiza.* TO 'kibanda_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   mysql -u kibanda_user -p kibanda_umiza < database/schema.sql
   ```

5. **Deploy Application:**
   ```bash
   sudo rm -rf /var/www/html/*
   sudo cp -r ~/kibanda-umiza/* /var/www/html/
   ```

6. **Update Configuration:**
   ```bash
   sudo nano /var/www/html/config/database.php
   ```
   Update `DB_USER` to `kibanda_user` and `DB_PASS` to your password.

7. **Set Permissions:**
   ```bash
   sudo chown -R www-data:www-data /var/www/html/
   sudo chmod -R 755 /var/www/html/
   ```

8. **Enable mod_rewrite:**
   ```bash
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```

9. **Access:** `http://your-ec2-public-ip/`

### Option 2: AWS Elastic Beanstalk

1. Install EB CLI:
   ```bash
   pip install awsebcli
   ```

2. Initialize and deploy:
   ```bash
   cd kibanda-umiza
   eb init -p php kibanda-umiza
   eb create production
   ```

3. Set environment variables in EB console for database config.

### Option 3: Lightsail

Launch a PHP + MySQL Lightsail instance, then follow EC2 steps 4-9.

## License

MIT License - Free for educational and commercial use.
