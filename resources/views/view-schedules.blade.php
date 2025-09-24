<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Current Schedules - Smart Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Auto refresh every 2 minutes -->
    <meta http-equiv="refresh" content="120">
    <style>
        /* Layout & Structure */
        .carousel-container {
            padding-top: 100px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            padding: 2rem;
            background-color: #f6f1f1;
        }

        .carousel-item {
            padding: 4.5rem;
            top: 60px;
        }

        .schedule-slide {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin: 0 auto;
            max-width: 1200px;
            max-height: calc(100vh - 250px);
            overflow-y: auto;
            padding: 5px;
            scrollbar-width: thin;
        }
        
        /* University Header */
        .university-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #870100;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }
        
        .university-name {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }
        
        .university-name .university-text {
            font-weight: bold;
            display: inline-block;
            margin-right: 10px;
            background: linear-gradient(to top, #ff8800, #ffff00);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .university-name .college-text {
            background: linear-gradient(to top, #888888, #ffffff);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .logo {
            height: 4rem;
            width: auto;
            margin-left: 15px;
            margin-right: 15px;
            object-fit: cover;
            display: block;
            transition: all 0.3s ease;
        }
        
        /* Card Styles */
        .schedule-card {
            height: 100%;
            transition: all 0.3s ease;
            margin: 0;
        }
        
        .schedule-card.inactive {
            opacity: 0.5;
        }
        
        .card-body {
            position: relative;
            padding-bottom: 40px;
            display: flex;
            flex-direction: column;
        }
        
        .card-title {
            margin-bottom: 0.25rem;
        }
        
        .card-subtitle {
            min-height: 38px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            margin-bottom: 0.25rem;
        }
        
        .schedule-card .d-flex {
            margin-bottom: 1rem !important;
        }
        
        /* Card header with professor image */
        .card-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-bottom: 0.1rem;
        }
        
        .card-header-text {
            flex: 1;
            padding-right: 15px;
        }
        
        .professor-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f8f9fa;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        /* Status indicators */
        .schedule-status {
            position: absolute;
            bottom: 10px;
            right: 10px;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
            z-index: 1;
        }
        
        .status-current {
            background-color: #28a745;
            color: white;
        }
        
        .status-upcoming {
            background-color: #ffc107;
            color: black;
        }
        
        .status-ended {
            background-color: #dc3545;
            color: white;
        }
        
        .professor-status-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            position: absolute;
            bottom: 10px;
            left: 20px;
        }
        
        /* UI Controls */
        .back-button {
            position: fixed;
            top: 100px !important;
            left: 70px !important;
            z-index: 10000;
            opacity: 1;
            transition: all 0.3s ease;
            font-size: 1.2rem;
            padding: 0.5rem;
            background-color: rgba(108, 117, 125, 0.8);
            color: white;
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .back-button:hover {
            background-color: rgba(108, 117, 125, 1);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        
        .current-time {
            position: fixed;
            top: 100px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10000;
            font-size: 1.5rem;
            font-weight: bold;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 0.5rem 1rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        /* Carousel navigation */
        .carousel-controls {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10001;
        }
        
        .carousel-controls .btn {
            padding: 8px 15px;
            font-weight: 500;
            border-radius: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }
        
        .carousel-controls .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        /* Progress bar */
        .carousel-progress-container {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            height: 8px;
            display: block;
            z-index: 10000;
        }
        
        .progress {
            height: 100%;
            border-radius: 0;
            background-color: rgba(0, 0, 0, 0.1);
        }
        
        #carouselProgress {
            width: 0%;
            height: 100%;
            background-color: rgba(40, 167, 69, 0.9);
            transition: width 0.1s linear;
        }
        
        /* No Schedule Message */
        .no-schedule {
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 3rem;
            font-size: 1.5rem;
            color: #6c757d;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 500px;
        }
        
        .no-schedule i {
            font-size: 4rem;
            color: #6c757d;
            margin-bottom: 1.5rem;
        }
        
        /* Status badge colors */
        .status-present { background: #198754; color: #fff; }
        .status-absent { background: #dc3545; color: #fff; }
        .status-on-leave { background: #ffc107; color: #212529; }
        .status-on-meeting { background: #0dcaf0; color: #212529; }
    </style>
</head>
<body>
    <div class="university-header">
        <h1 class="university-name">
            <span class="university-text">Smart Schedule</span> 
            <span class="college-text">Current Class Schedules</span>
        </h1>
    </div>

    <div class="current-time" id="currentTime">
        {{ \Carbon\Carbon::createFromTimestamp($serverTimestamp ?? time(), 'Asia/Manila')->format('l, h:i:s A') }}
    </div>
    
    <a href="{{ url('/') }}" class="btn btn-secondary back-button" title="Back to Dashboard">
        <i class="fas fa-arrow-left"></i>
    </a>

    <!-- Progress Bar -->
    <div class="carousel-progress-container">
        <div class="progress">
            <div id="carouselProgress" class="progress-bar bg-secondary" role="progressbar" style="width: 0%"></div>
        </div>
    </div>

    <div class="carousel-container">
        @if ($schedules->isEmpty())
            <div class="no-schedule">
                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                <p id="noScheduleMessage">No schedules for {{ $currentDay }} at {{ $currentTimeFormatted }}</p>
            </div>
        @else
        <div id="scheduleCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @php
                    $chunks = $schedules->chunk(4);
                @endphp
                @foreach ($chunks as $index => $chunk)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="schedule-slide">
                        @foreach ($chunk as $schedule)
                        <div class="card schedule-card" 
                             data-start="{{ $schedule->start_time }}" 
                             data-end="{{ $schedule->end_time }}">
                            <div class="card-body">
                                <div class="schedule-status"></div>
                                <div class="card-header-content">
                                    <div class="card-header-text">
                                        <h5 class="card-title mb-2">{{ $schedule->course->course_code }}</h5>
                                        <div class="card-subtitle mb-1">{{ $schedule->course->course_name }}</div>
                                        <small class="text">Prof. {{ $schedule->professor->name }}</small>
                                    </div>
                                    <img src="{{ asset('uploads/' . ($schedule->professor->profile_image && $schedule->professor->profile_image !== 'placeholder.png' ? $schedule->professor->profile_image : 'placeholder.png')) }}" 
                                         class="professor-image" alt="Professor">
                                </div>
                                <p class="card-text">
                                    <i class="fas fa-clock me-2"></i>
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                </p>
                                <p class="card-text">
                                    <i class="fas fa-calendar me-2"></i>
                                    {{ $schedule->day }}
                                </p>
                                <p class="card-text">
                                    <i class="fas fa-door-open me-2"></i>
                                    Room {{ $schedule->room->name }}
                                </p>
                                @php
                                    $badgeClass = 'status-present';
                                    if ($schedule->professor_status === 'Absent') {
                                        $badgeClass = 'status-absent';
                                    } elseif ($schedule->professor_status === 'On Leave') {
                                        $badgeClass = 'status-on-leave';
                                    } elseif ($schedule->professor_status === 'On Meeting') {
                                        $badgeClass = 'status-on-meeting';
                                    }
                                @endphp
                                <span class="professor-status-badge {{ $badgeClass }}">
                                    {{ $schedule->professor_status }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Manual navigation buttons -->
            <div class="carousel-controls">
                <button class="btn btn-outline-secondary" id="prevSlide">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-outline-secondary" id="nextSlide">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Server time sync
        const serverTimestampMs = Number(@json($serverTimestamp ?? null)) * 1000;
        const serverOffsetMs = isNaN(serverTimestampMs) ? 0 : (serverTimestampMs - Date.now());
        function getServerNow(){
            return new Date(Date.now() + serverOffsetMs);
        }

        // Global variables for carousel control
        let carouselDuration = 10; // Default duration in seconds
        let carouselInstance;
        let countdownInterval;
        let countdownValue = carouselDuration;
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize carousel
            const carousel = document.getElementById('scheduleCarousel');
            if (carousel) {
                carouselInstance = new bootstrap.Carousel(carousel, {
                    interval: false,  // Disable auto cycling initially
                    pause: false,
                    wrap: true
                });
            }
            
            // Setup manual navigation buttons
            const prevButton = document.getElementById('prevSlide');
            const nextButton = document.getElementById('nextSlide');
            
            if (prevButton && nextButton) {
                prevButton.addEventListener('click', function() {
                    if (carouselInstance) {
                        carouselInstance.prev();
                    }
                    resetCountdown();
                });
                
                nextButton.addEventListener('click', function() {
                    if (carouselInstance) {
                        carouselInstance.next();
                    }
                    resetCountdown();
                });
            }
            
            // Start the countdown timer
            startCountdown();
        });
        
        // Function to start the countdown timer
        function startCountdown() {
            // Clear any existing interval
            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
            
            // Reset progress bar
            const progressBar = document.getElementById('carouselProgress');
            if (progressBar) {
                progressBar.style.transition = 'none';
                progressBar.style.width = '0%';
                
                setTimeout(function() {
                    progressBar.style.transition = 'width 0.1s linear';
                    
                    const updateInterval = 50;
                    const steps = (carouselDuration * 1000) / updateInterval;
                    const incrementPerStep = 100 / steps;
                    let currentProgress = 0;
                    
                    function updateProgress() {
                        currentProgress += incrementPerStep;
                        
                        if (progressBar) {
                            progressBar.style.width = Math.min(currentProgress, 100) + '%';
                        }
                        
                        if (currentProgress >= 100) {
                            setTimeout(function() {
                                const carousel = document.getElementById('scheduleCarousel');
                                if (carousel && carouselInstance) {
                                    carousel.addEventListener('slid.bs.carousel', function onSlideEnd() {
                                        carousel.removeEventListener('slid.bs.carousel', onSlideEnd);
                                        setTimeout(function() {
                                            startCountdown();
                                        }, 100);
                                    }, { once: true });
                                    
                                    carouselInstance.next();
                                }
                            }, 50);
                            
                            clearInterval(countdownInterval);
                            countdownInterval = null;
                        } else {
                            countdownInterval = setTimeout(updateProgress, updateInterval);
                        }
                    }
                    
                    countdownInterval = setTimeout(updateProgress, updateInterval);
                }, 10);
            }
        }
        
        // Function to reset the countdown timer
        function resetCountdown() {
            if (countdownInterval) {
                clearTimeout(countdownInterval);
                countdownInterval = null;
            }
            startCountdown();
        }
        
        // Function to update current time
        function updateTime() {
            const now = getServerNow();
            const options = { 
                weekday: 'long',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true 
            };
            const dateStr = now.toLocaleString('en-US', options);
            document.getElementById('currentTime').textContent = dateStr;
            
            // Also update the no-schedule message if it exists
            const noScheduleMessage = document.getElementById('noScheduleMessage');
            if (noScheduleMessage) {
                const dayName = now.toLocaleString('en-US', { weekday: 'long' });
                const timeStr = now.toLocaleString('en-US', { 
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true 
                });
                noScheduleMessage.textContent = `No schedules for ${dayName} at ${timeStr}`;
            }
        }

        // Function to check schedule status
        function updateScheduleStatuses() {
            const now = getServerNow();
            const currentTime = now.getHours().toString().padStart(2, '0') + ':' + 
                              now.getMinutes().toString().padStart(2, '0') + ':' + 
                              now.getSeconds().toString().padStart(2, '0');

            document.querySelectorAll('.schedule-card').forEach(card => {
                const startTime = card.dataset.start;
                const endTime = card.dataset.end;
                const statusElement = card.querySelector('.schedule-status');

                // Calculate time 5 minutes before start
                const startDate = new Date();
                const [startHours, startMinutes] = startTime.split(':');
                startDate.setHours(parseInt(startHours), parseInt(startMinutes), 0);
                
                const fiveMinutesBefore = new Date(startDate.getTime() - 5 * 60000);
                const currentDate = new Date();
                currentDate.setSeconds(0);
                
                if (currentDate >= fiveMinutesBefore && currentTime < startTime) {
                    statusElement.textContent = 'Starting Soon';
                    statusElement.className = 'schedule-status status-upcoming';
                } else if (currentTime < startTime) {
                    statusElement.textContent = 'Upcoming';
                    statusElement.className = 'schedule-status status-upcoming';
                } else if (currentTime >= startTime && currentTime <= endTime) {
                    statusElement.textContent = 'Current';
                    statusElement.className = 'schedule-status status-current';
                } else {
                    statusElement.textContent = 'Ended';
                    statusElement.className = 'schedule-status status-ended';
                    card.classList.add('inactive');
                }
            });
        }

        // Update time and schedule statuses every second
        setInterval(() => {
            updateTime();
            updateScheduleStatuses();
        }, 1000);

        // Initial update
        updateTime();
        updateScheduleStatuses();
    </script>
</body>
</html>
