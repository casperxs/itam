# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Development Commands

### Quick Start Development Environment
```bash
composer dev  # Start full development environment (server, queue, logs, and Vite)
```
This single command runs all necessary services concurrently: Laravel server, queue worker, log viewer, and Vite dev server.

### Individual Services
```bash
php artisan serve                     # Laravel development server only
php artisan queue:listen --tries=1    # Queue worker for background jobs
php artisan pail --timeout=0         # Real-time log viewer
npm run dev                          # Vite development server for assets
npm run build                        # Build production assets
```

### Database Operations
```bash
php artisan migrate                   # Run database migrations
php artisan db:seed                  # Seed database with sample data
php artisan migrate:fresh --seed     # Fresh migration with seeding
php artisan tinker                   # Laravel REPL for database testing
```

### Testing
```bash
composer test                                          # Run all tests with config clear
php artisan test                                      # Run PHPUnit tests directly
php artisan test --filter=TestMethodName             # Run specific test method
php artisan test tests/Feature/ExampleTest.php       # Run specific test file
```

### Code Quality
```bash
php artisan pint  # Laravel Pint code formatter (PSR-12 compliance)
```

### Console Commands (Custom)
```bash
php artisan check:warranties            # Monitor warranty expiration dates
php artisan generate:monthly-reports    # Create automated monthly reports
php artisan send:contract-alerts        # Send contract expiration notifications
php artisan process:email-tickets       # Batch process email tickets
php artisan cleanup:temp-files          # Clean temporary import files
php artisan equipment:sync-status       # Synchronize equipment status with assignments
```

## Architecture Overview

### Core Domain Models
This ITAM system is built around equipment lifecycle management with these central entities:

- **Equipment**: Core asset model linked to types, suppliers, assignments, maintenance, and support tickets
- **Assignment**: Tracks equipment-to-user relationships with historical snapshots and date tracking
- **Contract**: Manages supplier contracts with automated expiration monitoring
- **EmailTicket**: Processes equipment-related support requests from email integration
- **ItUser**: Represents equipment users (separate from system User model for permissions)

### Key Services Architecture
Located in `app/Services/`:
- **BulkImportService**: Handles CSV/Excel equipment data imports with validation
- **EmailService**: Processes incoming email tickets and creates support records automatically
- **PdfGeneratorService**: Generates equipment reports, assignments, and maintenance documentation
- **ReportService**: Creates monthly usage reports and maintenance summaries

### Background Job System
Queue jobs in `app/Jobs/` handle async operations:
- **ProcessBulkEquipmentImport**: Large equipment import processing to prevent timeouts
- **ProcessEmailTicket**: Email ticket processing with automated routing
- **SendContractExpirationAlert**: Scheduled notifications for contract renewals

### Database Architecture
Uses SQLite (`database/database.sqlite`) with these key relationships:
- Equipment → EquipmentType, Supplier (many-to-one)
- Equipment → Assignment → ItUser (equipment assignment tracking)
- Equipment → MaintenanceRecord (maintenance history)
- Equipment → EmailTicket (support ticket linking)
- Assignment table includes user snapshots to maintain historical data integrity

### Frontend Stack
- **Vite** for asset bundling (`vite.config.js`)
- **TailwindCSS v4** for styling
- **Laravel Blade** templates in `resources/views/`
- Built-in dark mode support with user preferences

### File Storage Structure
- **Invoices**: `storage/app/public/invoices/`
- **User Documents**: `storage/app/public/documents/`
- **Temporary Imports**: `storage/app/private/imports/`

## Development Notes

### Database Considerations
- Uses SQLite for simplicity in development and small deployments
- In-memory SQLite database for testing (configured in `phpunit.xml`)
- Historical data preservation through user snapshots in assignments table
- Equipment ratings system with separate criteria and ratings tables

### Queue System
- Uses `sync` queue driver by default for development
- Queue jobs designed for production async processing
- Console commands can trigger queue jobs for batch operations

### Email Integration
- Email ticket processing system for automated support workflows
- Email notifications for warranty expiration, contract alerts, and maintenance schedules

### Authentication & Authorization
- Custom admin middleware (`AdminMiddleware`) for admin-only operations
- Dark mode preference tracking per user
- Role-based access with 'admin' role distinction

### Testing Strategy
- Feature tests for HTTP endpoints and workflows
- Unit tests for services and models
- SQLite in-memory database for isolated testing
- Test factories for all major models

### Key Configuration Dependencies
- Laravel 12 framework
- PHP 8.2+ requirement
- barryvdh/laravel-dompdf for PDF generation
- Laravel Pail for enhanced log viewing
- Concurrent process management via npm concurrently package
