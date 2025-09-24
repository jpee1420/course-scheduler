<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Professor;
use App\Models\Room;
use App\Models\Course;

class DashboardController extends Controller
{
	public function index()
	{
		$schedules = Schedule::with(['course', 'professor', 'room'])->orderBy('day')->orderBy('start_time')->get();
		$professors = Professor::orderBy('id')->get();
		$rooms = Room::orderBy('id')->get();
		$courses = Course::orderBy('id')->get();

		return view('dashboard', compact('schedules', 'professors', 'rooms', 'courses'));
	}

	public function viewSchedules()
	{
		// Try to get Asia/Manila time from network, fallback to system time
		$timezone = $this->getTimezone();
		$now = now($timezone);
		$currentDay = $now->format('l'); // Returns Sunday, Monday, etc.
		$currentTime = $now->format('H:i:s');
		$currentTimeFormatted = $now->format('h:i A'); // For display in no-schedule message

		// Convert day name to MWF or TTH format
		$currentDayFormat = $this->getDayFormat($currentDay);

		// Get current and upcoming schedules
		$schedules = $this->getCurrentSchedules($currentTime, $currentDayFormat);

		$serverTimestamp = $now->timestamp;
		return view('view-schedules', compact('schedules', 'currentDay', 'currentTime', 'currentTimeFormatted', 'serverTimestamp'));
	}

	private function getDayFormat($dayName)
	{
		switch($dayName) {
			case 'Monday':
			case 'Wednesday':
			case 'Friday':
				return 'MWF';
			case 'Tuesday':
			case 'Thursday':
				return 'TTH';
			case 'Saturday':
				return 'Sat';
			default:
				return '';
		}
	}

	private function getTimezone()
	{
		// Check if we can connect to the internet by trying to get time from a reliable source
		try {
			$context = stream_context_create([
				'http' => [
					'timeout' => 3, // 3 second timeout
					'method' => 'HEAD'
				]
			]);
			
			// Try to connect to a reliable time server
			$result = @file_get_contents('http://worldtimeapi.org/api/timezone/Asia/Manila', false, $context);
			
			if ($result !== false) {
				// Network is available, use Asia/Manila
				return 'Asia/Manila';
			}
		} catch (\Exception $e) {
			// Network not available, fall through to system time
		}
		
		// Fallback to system timezone
		return config('app.timezone', 'UTC');
	}

	private function getCurrentSchedules($currentTime, $currentDayFormat)
	{
		// If it's Sunday or the day format is empty, return no schedules
		if (empty($currentDayFormat)) {
			return collect();
		}

		// Calculate time 5 minutes from now using the same timezone logic
		$timezone = $this->getTimezone();
		$fiveMinutesFromNow = now($timezone)->addMinutes(5)->format('H:i:s');

		// Get current classes and classes starting within next 5 minutes
		$schedules = Schedule::with(['course', 'professor', 'room'])
			->where('day', $currentDayFormat)
			->where(function($query) use ($currentTime, $fiveMinutesFromNow) {
				$query->where(function($q) use ($currentTime) {
					// Current classes
					$q->where('start_time', '<=', $currentTime)
					  ->where('end_time', '>=', $currentTime);
				})->orWhere(function($q) use ($currentTime, $fiveMinutesFromNow) {
					// Classes starting within next 5 minutes
					$q->where('start_time', '>', $currentTime)
					  ->where('start_time', '<=', $fiveMinutesFromNow);
				});
			})
			->orderBy('start_time')
			->get();

		// If no current or starting soon schedules, get the next upcoming hour
		if ($schedules->isEmpty()) {
			$nextSchedule = Schedule::where('day', $currentDayFormat)
				->where('start_time', '>', $fiveMinutesFromNow)
				->orderBy('start_time')
				->first();

			if ($nextSchedule) {
				$nextHour = date('H', strtotime($nextSchedule->start_time));
				$hourStart = $nextHour . ':00:00';
				$hourEnd = $nextHour . ':59:59';

				$schedules = Schedule::with(['course', 'professor', 'room'])
					->where('day', $currentDayFormat)
					->whereBetween('start_time', [$hourStart, $hourEnd])
					->orderBy('start_time')
					->get();
			}
		}

		return $schedules;
	}
}


