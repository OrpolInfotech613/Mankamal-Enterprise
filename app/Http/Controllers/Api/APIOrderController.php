<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStep;
use App\Models\User;
use App\Models\ProcessingStep;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class APIOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        // $user = Auth::user();
        $user= User::findOrFail(2);
        $query = Order::query();

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
        if ($user->d_id) {
            $orderSteps = OrderStep::with('order')
                ->where('d_id', $user->d_id)
                ->where('status', 'progress')
                ->get();

            $orderIds = $orderSteps->pluck('o_id')->unique()->toArray();

            if (!empty($orderIds)) {
                $query->whereIn('id', $orderIds);
            } else {
                $query->where('id', 0);
            }
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $orders = $query->paginate($perPage);

        return response()->json([
            'data' => $orders->items(),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'last_page' => $orders->lastPage(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->all();

        if (isset($data['production_step']) && is_string($data['production_step'])) {
            $data['production_step'] = json_decode($data['production_step'], true);

        }
        $validator = Validator::make($data, [
            'dealer_name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'production_step' => 'required|array',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'shade_number' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:100',
            'delivery_time' => 'nullable|date',
            'status' => 'nullable|in:pending,completed,progress',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
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

        return response()->json([
            'message' => 'order created successfully',
            'data' => $order
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {   
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Migration order not found'
            ], 404);
        }

        return response()->json([
            'data' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Migration order not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'dealer_name' => 'sometimes|required|string|max:255',
            'customer_name' => 'sometimes|required|string|max:255',
            'product_name' => 'sometimes|required|string|max:255',
            'production_step' => 'sometimes|required|string|max:100',
            'price' => 'sometimes|required|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:1',
            'shade_number' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:100',
            'delivery_time' => 'nullable|date',
            'status' => 'nullable|in:pending,completed,progress',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $order->update($validator->validated());

        return response()->json([
            'message' => 'order updated successfully',
            'data' => $order
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'order not found'
            ], 404);
        }

        $order->delete();

        return response()->json([
            'message' => 'order deleted successfully'
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        // $user = Auth::user(); // use this in real app
        $user = User::findOrFail(7); // TEMP for testing

        return DB::transaction(function () use ($request, $id, $user) {
            $order = Order::findOrFail($id);

            // --- 1) Normalize and validate status ---
            $rawStatus = (string) $request->input('status', '');
            $statusNorm = strtolower(trim($rawStatus));

            // Map common synonyms to allowed constants
            $map = [
                'complete' => OrderStep::STATUS_COMPLETED,
                'completed' => OrderStep::STATUS_COMPLETED,
                'done' => OrderStep::STATUS_COMPLETED,
                'in_progress' => OrderStep::STATUS_PROGRESS,
                'in-progress' => OrderStep::STATUS_PROGRESS,
                'inprogress' => OrderStep::STATUS_PROGRESS,
                'progress' => OrderStep::STATUS_PROGRESS,
                'pending' => OrderStep::STATUS_PENDING,
            ];
            if (isset($map[$statusNorm])) {
                $statusNorm = $map[$statusNorm];
            }

            $validator = Validator::make(
                ['status' => $statusNorm],
                [
                    'status' => [
                        'required',
                        Rule::in([
                            OrderStep::STATUS_PENDING,
                            OrderStep::STATUS_COMPLETED,
                            OrderStep::STATUS_PROGRESS
                        ])
                    ]
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // --- 2) Find current step for this department ---
            $orderStep = OrderStep::where('o_id', $id)
                ->where('d_id', $user->d_id)
                ->first();

            if (!$orderStep) {
                return response()->json([
                    'message' => 'No step found for this department on the given order.'
                ], 404);
            }

            // --- 3) Update current step ---
            $orderStep->update([
                'status' => $statusNorm,
                'note' => $request->input('note'),
                'date' => now()->toDateString(),
            ]);

            // --- 4) If completed, move to next department in the flow ---
            if ($statusNorm === OrderStep::STATUS_COMPLETED) {
                // production_step casted to array in Order model; make sure types align
                $flow = array_map('intval', (array) $order->production_step);
                $currentDept = (int) $user->d_id;

                $currentIndex = array_search($currentDept, $flow, true);

                if ($currentIndex !== false && isset($flow[$currentIndex + 1])) {
                    $nextDeptId = (int) $flow[$currentIndex + 1];

                    OrderStep::where('o_id', $id)
                        ->where('d_id', $nextDeptId)
                        ->update(['status' => OrderStep::STATUS_PROGRESS]);

                    $order->update(['status' => OrderStep::STATUS_PROGRESS]);
                } else {
                    // No next step -> whole order completed
                    $order->update(['status' => OrderStep::STATUS_COMPLETED]);
                }
            } elseif ($statusNorm === OrderStep::STATUS_PROGRESS) {
                // When a dept starts working, order should at least be progress
                if ($order->status !== OrderStep::STATUS_COMPLETED) {
                    $order->update(['status' => OrderStep::STATUS_PROGRESS]);
                }
            } else {
                // pending: no change to overall order status here
            }

            return response()->json([
                'message' => 'Order status updated successfully',
                'order' => $order->fresh('Orderstep')
            ]);
        });
    }

    public function getProcessingSteps(Request $request): JsonResponse
    {

        $user = Auth::user();
        $steps = ProcessingStep::with('department')->orderBy('step_order')->get();

        return response()->json([
            'data' => $steps
        ]);
    }
}