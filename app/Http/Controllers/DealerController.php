<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use Illuminate\Http\Request;

class DealerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Dealer::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $dealers = $query->orderBy('id', 'desc')->paginate(15);

        if ($request->ajax()) {
            return view('dealers.rows', compact('dealers'))->render();
        }
        return view('dealers.index', compact('dealers'));
    }

    public function APIindex(Request $request)
    {
        $query = Dealer::query();

        if ($request->has('name') && !empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $dealers = $query->get();

        return response()->json($dealers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dealers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:dealers,email',
            'phone_no' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'gst_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        Dealer::create($validated);

        return redirect()->route('dealers.index')
            ->with('success', 'Dealer created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Dealer $dealer)
    {
        return view('dealers.show', compact('dealer'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dealer $dealer)
    {
        return view('dealers.edit', compact('dealer'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dealer $dealer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:dealers,email,' . $dealer->id,
            'phone_no' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'gst_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $dealer->update($validated);

        return redirect()->route('dealers.index')
            ->with('success', 'Dealer updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dealer $dealer)
    {
        $dealer->delete();

        return redirect()->route('dealers.index')
            ->with('success', 'Dealer deleted successfully!');
    }
}
