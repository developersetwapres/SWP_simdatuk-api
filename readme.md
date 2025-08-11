![Logo](/public/img/logo.svg)

# Application Programming Interface (API)

This is the backend API for SIMDATUK (Sistem Informasi Manajemen Data Dukungan Kepegawaian) - Indonesian Government Human Resource Management System. It is built with Laravel and provides comprehensive server-side functionality for employee data management, organizational structure, reporting, and administrative operations for ASN, Non-ASN, and Outsourced personnel.

## Table of Contents
[[_TOC_]]

## Features

*   **Employee Management:** Complete CRUD operations for ASN, Non-ASN, and Outsourced employees with comprehensive profile data.
*   **Authentication & Authorization:** Secure JWT-based authentication with role-based access control (RBAC) and granular permissions.
*   **Position Management:** Hierarchical organizational structure with position availability tracking and echelon compatibility.
*   **Historical Data Tracking:** Complete audit trail for positions, grades, training, performance, recognition, and disciplinary records.
*   **Advanced Reporting:** Statistical summaries, recapitulations, employee comparisons, and organizational diagrams with export capabilities.
*   **Bulk Import/Export:** Excel-based bulk employee import with error handling and multiple export formats (Excel, PDF, CSV).
*   **Document Management:** Secure file uploads and management using AWS S3 integration.
*   **Training Management:** Comprehensive tracking for structural, functional, and technical training programs.
*   **Performance Evaluation:** SKP (Sasaran Kinerja Pegawai) and performance assessment management.
*   **API Documentation:** Automatically generated documentation using Scribe.

## Technology Stack

*   **Language:** PHP 8.1+
*   **Framework:** Laravel 10.x
*   **Database:** MySQL with custom query extensions
*   **Authentication:** Laravel Sanctum (JWT)
*   **File Storage:** AWS S3 with Flysystem
*   **PDF Generation:** DomPDF
*   **Excel Processing:** Maatwebsite Excel
*   **API Documentation:** Scribe
*   **Containerization:** Docker with multi-service setup
*   **Web Server:** Nginx with PHP-FPM

## Project Structure

The project follows Laravel's MVC architecture with repository pattern and helper traits for better organization and scalability.

```
.
├── app/
│   ├── Console/           # Artisan commands
│   ├── Exceptions/        # Custom exception handlers
│   ├── Exports/           # Excel export classes
│   ├── Helpers/           # Utility traits (Document, Responser)
│   ├── Http/
│   │   ├── Controllers/   # API controllers with comprehensive business logic
│   │   ├── Middleware/    # Authentication and authorization middleware
│   │   └── Requests/      # Form request validation classes
│   ├── Imports/           # Excel import processing
│   ├── Mail/              # Email notification classes
│   ├── Models/            # Eloquent models
│   ├── Providers/         # Service providers
│   └── Repositories/      # Data access layer with business logic
├── config/                # Laravel configuration files
├── database/
│   ├── migrations/        # Database schema migrations (40+ tables)
│   └── seeders/          # Database seeding scripts
├── docker/               # Docker configuration files
├── public/               # Web accessible files and assets
├── resources/            # Views, assets, and language files
├── routes/               # API route definitions (50+ endpoints)
├── storage/              # File storage and logs
└── tests/                # PHPUnit test suites
```

## Getting Started

### Prerequisites

*   PHP 8.1 or higher
*   Composer
*   Docker & Docker Compose
*   MySQL
*   AWS S3 account (for file storage)

### Installation

1.  **Clone the repository:**
    ```bash
    git clone http://git.ekuator.id/project/setneg/simdatuk/api.git
    cd api
    ```

2.  **Set up environment variables:**
    ```bash
    cp .env.example .env
    ```
    *   Configure database credentials, AWS S3 settings, and other environment-specific values.

3.  **Install dependencies:**
    ```bash
    composer install
    ```

4.  **Start Docker services:**
    ```bash
    docker-compose up -d
    ```

5.  **Access PHPMyAdmin at [http://localhost:8080](http://localhost:8080) and create the database.**

6.  **Run database migrations:**
    ```bash
    docker exec -it simdatuk-api php artisan migrate
    ```

7.  **Seed the database:**
    ```bash
    docker exec -it simdatuk-api php artisan db:seed
    ```

8.  **Generate application key:**
    ```bash
    docker exec -it simdatuk-api php artisan key:generate
    ```

The application will be available at `http://localhost` and webmail at `http://localhost:1080`.

## API Documentation

The API provides 50+ endpoints organized into the following groups:

*   **Authentication:** `/api/login`, `/api/logout`, `/api/forgot-password`
*   **Employee Management:** `/api/employees/*` (CRUD with advanced filtering)
*   **Reporting & Analytics:** `/api/summaries`, `/api/recapitulations/*`
*   **Historical Data:** `/api/*-histories` (position, grade, training, etc.)
*   **Master Data:** `/api/positions`, `/api/grades`, `/api/institutions`
*   **Export Operations:** `/api/export/*` (Excel, PDF formats)
*   **User Management:** `/api/users/*`, `/api/roles/*`

Access the interactive API documentation at `http://localhost/docs`.