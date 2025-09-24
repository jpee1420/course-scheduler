# ClassScheduler

A comprehensive class scheduling management system built with Laravel and Docker. This application allows educational institutions to manage courses, professors, rooms, and class schedules with an intuitive web interface and real-time slideshow display.

## Features

### 📚 Core Management
- **Course Management**: Create and manage courses with lab/non-lab designation
- **Professor Management**: Manage professor profiles with image uploads and status tracking
- **Room Management**: Organize classroom and laboratory spaces
- **Schedule Management**: Create and manage class schedules with time slots and day assignments

### 🎯 Smart Scheduling
- **Real-time Schedule Display**: Carousel slideshow showing current and upcoming classes
- **Time Synchronization**: Server-time synced display with Asia/Manila timezone support
- **Status Tracking**: Live professor status (Present/Absent/On Leave/On Meeting)
- **Smart Filtering**: Shows current classes, starting soon, and next upcoming classes

### 🖥️ User Interface
- **Responsive Dashboard**: Bootstrap-powered interface with tabbed navigation
- **Search & Filter**: Live search functionality across all entities
- **Image Management**: Professor profile image upload with remove functionality
- **Independent Scrolling**: Optimized layout with fixed sidebars and scrollable content

### 📺 Display Features
- **Slideshow Mode**: Full-screen carousel for classroom displays
- **Auto-refresh**: Automatic content updates every 2 minutes
- **Progress Indicators**: Visual progress bars and slide navigation
- **Network-aware**: Automatic timezone detection with offline fallback

## Technology Stack

- **Backend**: Laravel 11.x with PHP 8.4
- **Database**: MySQL 8.0
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

# Add missing environment variables to .env
echo "MYSQL_EXTRA_OPTIONS=" >> .env
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
# Generate application key
./vendor/bin/sail artisan key:generate

# Run migrations and seed data
./vendor/bin/sail artisan migrate:fresh --seed

# Clear caches
./vendor/bin/sail artisan cache:clear
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

### Managing Content
1. **Dashboard Navigation**: Use the tabbed interface to switch between Schedules, Professors, Rooms, and Courses
2. **Adding Records**: Click "Add" buttons in each section
3. **Editing**: Click edit buttons in table rows
4. **Professor Images**: Upload images with remove functionality
5. **Search**: Use search boxes to filter content

## Development

### Running Tests
```bash
# Run all tests
./vendor/bin/sail artisan test

# Run specific test suites
./vendor/bin/sail artisan test --testsuite=Feature
./vendor/bin/sail artisan test --testsuite=Unit
```

### Database Management
```bash
# Fresh migration with seeding
./vendor/bin/sail artisan migrate:fresh --seed

# Reset auto-increment values
./vendor/bin/sail artisan tinker
# Then run: collect(['users', 'professors', 'courses', 'rooms', 'schedules'])->each(function($table) { $nextId = (DB::table($table)->max('id') ?? 0) + 1; DB::statement("ALTER TABLE $table AUTO_INCREMENT = $nextId"); });
```

### Accessing Database
```bash
# MySQL CLI access
./vendor/bin/sail mysql

# Laravel Tinker
./vendor/bin/sail artisan tinker
```

## Configuration

### Environment Variables
Key variables in `.env`:
```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=ClassScheduler
DB_USERNAME=sail
DB_PASSWORD=password

CACHE_STORE=database
MYSQL_EXTRA_OPTIONS=
```

### Timezone Configuration
The application uses Asia/Manila timezone with automatic network detection and system fallback.

## API Endpoints

### Professors
- `GET /professors` - List all professors
- `POST /professors` - Create professor
- `GET /professors/{id}` - Get professor details
- `PUT /professors/{id}` - Update professor
- `DELETE /professors/{id}` - Delete professor
- `POST /professors/{id}/status` - Update professor status

### Other Entities
Similar CRUD endpoints exist for courses, rooms, and schedules.

## File Structure

```
ClassScheduler/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php
│   │   ├── ProfessorController.php
│   │   └── ...
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── dashboard.blade.php
│       └── view-schedules.blade.php
├── routes/
│   └── web.php
└── tests/
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests: `./vendor/bin/sail artisan test`
5. Submit a pull request

## Troubleshooting

### Common Issues

**Port 3306 already in use:**
```bash
docker stop $(docker ps -q)
./vendor/bin/sail up -d
```

**Database connection refused:**
```bash
./vendor/bin/sail down
./vendor/bin/sail up -d
# Wait for MySQL to fully start
```

**Cache issues:**
```bash
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan view:clear
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
