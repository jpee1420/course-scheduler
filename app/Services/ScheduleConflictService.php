<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\Professor;
use App\Models\Room;

class ScheduleConflictService
{
	public function checkConflicts(?int $professorId, ?int $roomId, string $day, string $startTime, string $endTime, ?int $excludeScheduleId = null): array
	{
		$conflicts = [];

		$isProfessorTba = false;
		if (!is_null($professorId)) {
			$professor = Professor::find($professorId);
			$isProfessorTba = $professor && strtoupper(trim($professor->name)) === 'TBA';
		}

		$isRoomTba = false;
		if (!is_null($roomId)) {
			$room = Room::find($roomId);
			$isRoomTba = $room && strtoupper(trim($room->name)) === 'TBA';
		}

		$baseQuery = Schedule::query()
			->with(['professor', 'room', 'course'])
			->where('day', $day)
			->where(function ($q) use ($startTime, $endTime) {
				$q->where(function ($qq) use ($startTime, $endTime) {
					$qq->where('start_time', '<', $endTime)
						->where('end_time', '>', $startTime);
				});
			});

		if (!is_null($excludeScheduleId)) {
			$baseQuery->where('id', '!=', $excludeScheduleId);
		}

		if (!$isProfessorTba && !is_null($professorId)) {
			$professorConflicts = (clone $baseQuery)
				->where('professor_id', $professorId)
				->whereHas('professor', function ($q) {
					$q->whereRaw("UPPER(name) != 'TBA'");
				})
				->get();

			foreach ($professorConflicts as $row) {
				$conflicts[] = sprintf(
					"Professor %s already has a class (%s) scheduled on %s from %s to %s",
					$row->professor?->name,
					$row->course?->course_name,
					$row->day,
					date('h:i A', strtotime($row->start_time)),
					date('h:i A', strtotime($row->end_time))
				);
			}
		}

		if (!$isRoomTba && !is_null($roomId)) {
			$roomConflicts = (clone $baseQuery)
				->where('room_id', $roomId)
				->whereHas('room', function ($q) {
					$q->whereRaw("UPPER(name) != 'TBA'");
				})
				->get();

			foreach ($roomConflicts as $row) {
				$conflicts[] = sprintf(
					"Room %s is already booked for %s with Prof. %s on %s from %s to %s",
					$row->room?->name,
					$row->course?->course_name,
					$row->professor?->name,
					$row->day,
					date('h:i A', strtotime($row->start_time)),
					date('h:i A', strtotime($row->end_time))
				);
			}
		}

		return $conflicts;
	}
}
