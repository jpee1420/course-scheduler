<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Course;
use App\Models\Professor;
use App\Models\Room;
use App\Services\ScheduleConflictService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __construct(private ScheduleConflictService $conflictService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = Schedule::with(['course', 'professor', 'room'])->get();
        return response()->json($schedules);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['required','exists:courses,id'],
            'professor_id' => ['nullable','exists:professors,id'],
            'room_id' => ['nullable','exists:rooms,id'],
            'start_time' => ['required','date_format:H:i'],
            'end_time' => ['required','date_format:H:i','after:start_time'],
            'day' => ['required','in:MWF,TTH,Sat'],
        ]);

        $conflicts = $this->conflictService->checkConflicts(
            professorId: $validated['professor_id'] ?? null,
            roomId: $validated['room_id'] ?? null,
            day: $validated['day'],
            startTime: $validated['start_time'],
            endTime: $validated['end_time'],
            excludeScheduleId: null
        );

        if (!empty($conflicts)) {
            return response()->json(['message' => 'Schedule conflicts found', 'conflicts' => $conflicts], 422);
        }

        $schedule = Schedule::create($validated);
        return response()->json($schedule, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'course_id' => ['required','exists:courses,id'],
            'professor_id' => ['nullable','exists:professors,id'],
            'room_id' => ['nullable','exists:rooms,id'],
            'start_time' => ['required','date_format:H:i'],
            'end_time' => ['required','date_format:H:i','after:start_time'],
            'day' => ['required','in:MWF,TTH,Sat'],
            'professor_status' => ['nullable','in:Present,Absent,On Leave,On Meeting'],
        ]);

        $conflicts = $this->conflictService->checkConflicts(
            professorId: $validated['professor_id'] ?? null,
            roomId: $validated['room_id'] ?? null,
            day: $validated['day'],
            startTime: $validated['start_time'],
            endTime: $validated['end_time'],
            excludeScheduleId: $schedule->id
        );

        if (!empty($conflicts)) {
            return response()->json(['message' => 'Schedule conflicts found', 'conflicts' => $conflicts], 422);
        }

        $schedule->update($validated);
        return response()->json($schedule);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return response()->noContent();
    }
}
