<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ProfessorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Professor::orderBy('id')->get());
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
            'name' => ['required','string','max:255'],
            'profile_image' => ['nullable','image','max:2048'],
            'remove_image' => ['nullable','string'],
        ]);

        $path = 'placeholder.png';
        
        // Only upload if not removing and file exists
        if ($request->input('remove_image') !== '1' && $request->hasFile('profile_image')) {
            $filename = time().'_'.$request->file('profile_image')->getClientOriginalName();
            $request->file('profile_image')->move(public_path('uploads'), $filename);
            $path = $filename;
        }

        $professor = Professor::create([
            'name' => $request->input('name'),
            'profile_image' => $path,
        ]);
        return response()->json($professor, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Professor $professor)
    {
        return response()->json($professor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Professor $professor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Professor $professor)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'profile_image' => ['nullable','image','max:2048'],
            'remove_image' => ['nullable','string'],
        ]);

        $data = ['name' => $request->input('name')];
        
        // Handle image removal
        if ($request->input('remove_image') === '1') {
            $data['profile_image'] = 'placeholder.png';
        } elseif ($request->hasFile('profile_image')) {
            // Delete old image if it's not placeholder
            if ($professor->profile_image && $professor->profile_image !== 'placeholder.png') {
                $oldImagePath = public_path('uploads/' . $professor->profile_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $filename = time().'_'.$request->file('profile_image')->getClientOriginalName();
            $request->file('profile_image')->move(public_path('uploads'), $filename);
            $data['profile_image'] = $filename;
        }

        $professor->update($data);
        return response()->json($professor);
    }

    public function setStatus(Request $request, Professor $professor)
    {
        $validated = $request->validate([
            'status' => ['required','in:Present,Absent,On Leave,On Meeting'],
        ]);

        Schedule::where('professor_id', $professor->id)
            ->update(['professor_status' => $validated['status']]);

        return response()->json(['ok' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Professor $professor)
    {
        $professor->delete();
        return response()->noContent();
    }
}
