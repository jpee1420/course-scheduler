# ClassScheduler

A comprehensive class scheduling management system built with Laravel and Docker. This application allows educational institutions to manage courses, professors, rooms, and class schedules with an intuitive web interface and real-time slideshow display.

## Features

### Core Management
- **Course Management**: Create and manage courses with lab/non-lab designation
- **Professor Management**: Manage professor profiles with image uploads and status tracking
- **Room Management**: Organize classroom and laboratory spaces
- **Schedule Management**: Create and manage class schedules with time slots and day assignments

### Class Scheduling
- **Real-time Schedule Display**: Carousel slideshow showing current and upcoming classes
- **Time Synchronization**: Server-time synced display with Asia/Manila timezone support
- **Status Tracking**: Live professor status (Present/Absent/On Leave/On Meeting)
- **Smart Filtering**: Shows current classes, starting soon, and next upcoming classes

### User Interface
- **Responsive Dashboard**: Bootstrap-powered interface with tabbed navigation
- **Search & Filter**: Live search functionality across all entities
- **Image Management**: Professor profile image upload with remove functionality
- **Independent Scrolling**: Optimized layout with fixed sidebars and scrollable content

### Display Features
- **Slideshow Mode**: Full-screen carousel for classroom displays
- **Auto-refresh**: Automatic content updates every 2 minutes
- **Progress Indicators**: Visual progress bars and slide navigation
- **Network-aware**: Automatic timezone detection with offline fallback

## Technology Stack

- **Backend**: Laravel 11.x with PHP 8.4
- **Database**: PostgreSQL 17
- **Frontend**: Bootstrap 5.3, JavaScript ES6
- **Containerization**: Docker with Laravel Sail
- **Testing**: PHPUnit with Feature and Unit tests

## Prerequisites

- Docker and Docker Compose
- Git

## Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd ClassScheduler
```

### 2. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
./vendor/bin/sail artisan key:generate
```

### 3. Start Docker Containers
```bash
# Start the application
./vendor/bin/sail up -d

# Install dependencies (if needed)
./vendor/bin/sail composer install
```

### 4. Database Setup
```bash
# Run migrations and seed data
./vendor/bin/sail artisan migrate:fresh --seed

# Clear caches
./vendor/bin/sail artisan config:clear
```

## Usage

### Accessing the Application
- **Main Dashboard**: http://localhost
- **Slideshow Display**: http://localhost/view-schedules

### Default Data
The seeder creates:
- Sample courses (58 courses including labs)
- Default professors (Juan Dela Cruz, TBA)
- Room configurations (LR1, LR2, etc.)
- Empty schedule template

## Configuration

### Environment Variables
Key variables in `.env`:
```bash
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

APP_PORT=80
FORWARD_DB_PORT=5432
```

### Timezone Configuration
The application uses Asia/Manila timezone with automatic network detection and system fallback.

## Troubleshooting

### Common Issues

**Database connection refused**:
```bash
./vendor/bin/sail down
./vendor/bin/sail up -d
# Wait for PostgreSQL to fully start
```

**Cache issues**:
```bash
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan view:clear
```

**PostgreSQL version incompatibility**:
```bash
# Remove old PostgreSQL volumes
./vendor/bin/sail down -v
./vendor/bin/sail up -d
