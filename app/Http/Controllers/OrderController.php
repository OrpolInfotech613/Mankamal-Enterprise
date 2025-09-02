<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStep;
use App\Models\ProcessingStep;
use App\Models\Department;
use App\Models\Dealer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with([
            'department',
            'dealer',
            'Orderstep' => function ($q) {
                $q->where('status', 'progress');
            }
        ]);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('dealer_name')) {
            $query->where('dealer_name', 'like', '%' . $request->dealer_name . '%');
        }

        if ($request->has('customer_name')) {
            $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $orders = $query->paginate($perPage);

        $departmentIds = collect($orders->items())
            ->pluck('production_step')
            ->flatten()
            ->unique()
            ->filter()
            ->toArray();

        // Fetch department names
        $departments = Department::whereIn('id', $departmentIds)->pluck('name', 'id');

        return view('orders.index', compact('orders', 'departments'));
    }
    public function create()
    {
        $steps = ProcessingStep::with('department')
        ->orderBy('step_order')
        ->get()
        ->groupBy('step_order');

        $dealers = Dealer::all();
        return view('orders.create', compact('steps', 'dealers'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        if (isset($data['production_step']) && is_string($data['production_step'])) {
            $data['production_step'] = json_decode($data['production_step'], true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($data['production_step']) || count($data['production_step']) !== 5) {
                $data['production_step'] = 5;
            }
        }
        $data['status'] = 'pending';
        $validator = Validator::make($data, [
            'dealer_name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'production_step' => 'required|array',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'shade_number' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:100',
            'delivery_time' => 'nullable|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $order = Order::create($validator->validated());

        if ($order) {
            foreach ($data['production_step'] as $orderStep) {
                // $processing_step = ProcessingStep::where('step_order',$orderStep)->get();
                $step_order = ProcessingStep::where('department_id', $orderStep)->value('step_order');
                // dd($processing_step);
                OrderStep::create([
                    'o_id' => $order->id ?? 1,
                    'd_id' => $orderStep ?? 1,
                    'step_order' => $step_order,
                    'status' => 'pending',
                    'note' => null, // Use step_name as note
                    'date' => now(),
                ]);
            }
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with('Orderstep','dealer','department')->find($id);
        $departments = Department::all();
        if (!$order) {
            return response()->json([
                'message' => 'order not found'
            ], 404);
        }
        $dealers = Dealer::all(); 
        return view('orders.show', compact('order', 'departments','dealers'));
    }

    public function edit($id)
    {
        $departments = Department::all();
        $order = Order::with('Orderstep','dealer')->findOrFail($id);
        $dealers = Dealer::all();
        return view('orders.edit', compact('order', 'departments','dealers'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::with('Orderstep')->find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        $data = $request->all();

        // Handle production_step if it's a JSON string
        if (isset($data['production_step']) && is_string($data['production_step'])) {
            $data['production_step'] = json_decode($data['production_step'], true);
        }

        $validator = Validator::make($data, [
            'dealer_name' => 'sometimes|required|string|max:255',
            'customer_name' => 'sometimes|required|string|max:255',
            'product_name' => 'sometimes|required|string|max:255',
            'production_step' => 'sometimes|required|array',
            'price' => 'sometimes|required|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:1',
            'shade_number' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:100',
            'delivery_time' => 'nullable|date|after_or_equal:today',
            'status' => 'nullable|in:pending,completed,progress',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $statusUpdatedToProgress = isset($data['status']) &&
            $data['status'] === 'progress' &&
            $order->status !== 'progress';

        // Update the order
        $order->update($validator->validated());

        // If status was updated to 'progress', update the first OrderStep
        if ($statusUpdatedToProgress) {
            $firstOrderStep = $order->Orderstep->sortBy('step_order')->first();

            if ($firstOrderStep) {
                $firstOrderStep->update([
                    'status' => 'progress',
                    'date' => now() // Update the date to reflect when progress started
                ]);
            }
        }


        if (isset($data['production_step'])) {
            $currentSteps = $order->Orderstep->pluck('d_id')->toArray();
            $newSteps = $data['production_step'];

            $stepsToRemove = array_diff($currentSteps, $newSteps);

            $stepsToAdd = array_diff($newSteps, $currentSteps);

            if (!empty($stepsToRemove)) {
                OrderStep::where('o_id', $order->id)
                    ->whereIn('d_id', $stepsToRemove)
                    ->delete();
            }

            foreach ($stepsToAdd as $departmentId) {
                $step_order = ProcessingStep::where('department_id', $departmentId)->value('step_order');

                OrderStep::create([
                    'o_id' => $order->id,
                    'd_id' => $departmentId,
                    'step_order' => $step_order,
                    'status' => 'pending',
                    'note' => null,
                    'date' => now(),
                ]);
            }

            foreach ($newSteps as $index => $departmentId) {
                if (in_array($departmentId, $currentSteps)) {
                    $step_order = ProcessingStep::where('department_id', $departmentId)->value('step_order');

                    OrderStep::where('o_id', $order->id)
                        ->where('d_id', $departmentId)
                        ->update(['step_order' => $step_order]);
                }
            }
        }

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    /**
     * Update order status
    */
   
    public function updateStatus(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,progress,completed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status value'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // If status is being updated to 'progress', update the first OrderStep
            if ($request->status == 'progress') {
                $firstOrderStep = $order->Orderstep()->orderBy('step_order')->first();
                if ($firstOrderStep->status == 'progress') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Order is already in Production and cannot be modified'
                    ], 422);
                }
                if ($firstOrderStep) {
                    $firstOrderStep->update([
                        'status' => 'progress',
                        'date' => now()
                    ]);
                }
            }

            // If status is being updated to 'completed', update all OrderSteps
            if ($request->status === 'completed' && $order->status !== 'completed') {
                $order->Orderstep()->update([
                    'status' => 'completed',
                    'date' => now()
                ]);
            }
            $order->update(['status' => $request->status]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $order = Order::find($id);

            if (!$order) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'Order not found'
                    ], 404);
                }

                return redirect()->route('orders.index')
                    ->with('error', 'Order not found.');
            }

            // Delete associated order steps
            OrderStep::where('o_id', $id)->delete();

            $order->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Order deleted successfully'
                ], 200);
            }

            return redirect()->route('orders.index')
                ->with('success', 'Order deleted successfully.');

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to delete order: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('orders.index')
                ->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }
}