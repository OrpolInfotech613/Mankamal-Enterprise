<?php

namespace App\Http\Controllers;

use App\Models\ProcessingStep;
use App\Models\Department;
use Illuminate\Http\Request;

class ProcessingStepController extends Controller
{
    /**
     * Display a listing of the steps.
     */
    public function index()
    {
        $steps = ProcessingStep::with('department')->orderBy('step_order')->get();
        return view('processing_steps.index', compact('steps'));
    }

    /**
     * Show the form for creating a new step.
     */
    public function create()
    {
        $departments = Department::all();
        return view('processing_steps.create', compact('departments'));
    }

    /**
     * Store a newly created step.
     */
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'step_order' => 'required|integer',
        ]);

        ProcessingStep::create($request->all());

        return redirect()->route('processing_steps.index')
                         ->with('success', 'Processing step created successfully.');
    }

    /**
     * Show the form for editing the step.
     */
    public function edit(ProcessingStep $processing_step)
    {
        $departments = Department::all();
        return view('processing_steps.edit', compact('processing_step', 'departments'));
    }

    /**
     * Update the step.
     */
    public function update(Request $request, ProcessingStep $processing_step)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'step_order' => 'required|integer',
        ]);

        $processing_step->update($request->all());

        return redirect()->route('processing_steps.index')
                         ->with('success', 'Processing step updated successfully.');
    }

    /**
     * Remove the step.
     */
    public function destroy(ProcessingStep $processing_step)
    {
        $processing_step->delete();

        return redirect()->route('processing_steps.index')
                         ->with('success', 'Processing step deleted successfully.');
    }
}
