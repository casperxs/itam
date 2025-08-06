# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is an IT Asset Management (ITAM) system built with Laravel 12 and Vite, designed to track and manage IT equipment, assignments, maintenance records, contracts, and email tickets. The system uses SQLite as its database and includes features for bulk imports, automated email processing, and reporting.

## Development Commands

### Backend (Laravel)
- `composer dev` - Start full development environment (server, queue, logs, and Vite)
- `php artisan serve` - Start Laravel development server only
- `php artisan queue:listen --tries=1` - Start queue worker
- `php artisan pail --timeout=0` - Start real-time log viewer
- `php artisan test` - Run PHPUnit tests
- `composer test` - Run tests with config clear

### Frontend (Vite/TailwindCSS)
- `npm run dev` - Start Vite development server
- `npm run build` - Build assets for production

### Database
- `php artisan migrate` - Run database migrations
- `php artisan db:seed` - Seed database with sample data
- `php artisan migrate:fresh --seed` - Fresh migration with seeding

### Code Quality
- `php artisan pint` - Laravel Pint code formatter (available via composer)

## Core Architecture

### Models and Relationships
The system revolves around these core entities:
- **Equipment**: Central model with relationships to EquipmentType, Supplier, Assignment, MaintenanceRecord, and EmailTicket
- **Assignment**: Links Equipment to ItUser with assignment/return tracking
- **Contract**: Manages supplier contracts with expiration alerts
- **EmailTicket**: Processes equipment-related support requests via email

### Key Services
- **BulkImportService**: Handles CSV/Excel imports for equipment data
- **EmailService**: Processes incoming email tickets and creates support records
- **PdfGeneratorService**: Generates reports and documentation
- **ReportService**: Creates monthly usage and maintenance reports

### Queue Jobs
- **ProcessBulkEquipmentImport**: Handles large equipment imports asynchronously
- **ProcessEmailTicket**: Processes incoming email tickets
- **SendContractExpirationAlert**: Automated contract expiration notifications

### Console Commands
- **CheckWarranties**: Monitors warranty expiration dates
- **GenerateMonthlyReports**: Creates automated monthly reports  
- **SendContractAlerts**: Sends contract expiration notifications
- **ProcessEmailTickets**: Batch processes email tickets
- **CleanupTempFiles**: Maintains system by removing temporary files

## Database Structure

Uses SQLite database (`database/database.sqlite`) with migrations for:
- Equipment tracking with types, suppliers, and specifications
- User assignments with date tracking
- Maintenance records with scheduled/completed tracking
- Contract management with expiration monitoring
- Email ticket processing with automated routing
- Bulk import tracking with status management

## File Storage

- Invoice files stored in `storage/app/public/invoices/`
- User documents in `storage/app/public/documents/`
- Temporary imports in `storage/app/private/imports/`

## Testing

- Uses PHPUnit with Feature and Unit test directories
- SQLite in-memory database for testing
- Test configuration in `phpunit.xml`
- Run single test: `php artisan test --filter=TestMethodName`
- Run specific test file: `php artisan test tests/Feature/ExampleTest.php`

## Notifications and Alerts

The system includes automated notifications for:
- Warranty expiration warnings
- Contract expiration alerts  
- Maintenance due notifications
- Monthly report generation

## Key Configuration Files

- `composer.json`: PHP dependencies and custom scripts
- `package.json`: Frontend dependencies (Vite, TailwindCSS)
- `phpunit.xml`: Testing configuration
- `vite.config.js`: Asset bundling configuration
