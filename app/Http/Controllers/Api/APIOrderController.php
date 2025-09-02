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
        $user = Auth::user();
        // $user = User::findOrFail(2);
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
            // If decoding fails or it's not exactly 5 items, store the number 5
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($data['production_step']) || count($data['production_step']) !== 5) {
                $data['production_step'] = 5;
            }
            $data['status'] = 'pending';
        }
        $validator = Validator::make($data, [
            'dealer_name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'product_id' => 'required',
            'production_step' => 'required|array',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'shade_number' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:100',
            'delivery_time' => 'nullable|date|after_or_equal:today',
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
            'product_id' => 'sometimes',
            'production_step' => 'sometimes|required|string|max:100',
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
        $user = Auth::user(); // use this in real app
        // $user = User::findOrFail($request->u_id); // TEMP for testing

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
                'reject' => OrderStep::STATUS_REJECTED,
                'rejected' => OrderStep::STATUS_REJECTED,
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
                            OrderStep::STATUS_PROGRESS,
                            OrderStep::STATUS_REJECTED
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

            $orderStep = OrderStep::where('o_id', $id)
                ->where('d_id', $user->d_id)
                ->first();
            if (!$orderStep) {
                return response()->json([
                    'message' => 'No step found for this department on the given order.'
                ], 404);
            }
            $order->update(['status' => $request->status]);
            $orderStep->update([
                'status' => $statusNorm === 'rejected' ? OrderStep::STATUS_COMPLETED : $statusNorm,
                'note' => $request->input('note'),
                'date' => now()->toDateString(),
            ]);

            if ($statusNorm === OrderStep::STATUS_COMPLETED) {
                $this->moveToNextDepartment($order, $user->d_id);

            } elseif ($statusNorm === 'rejected') {
                // $this->handleRejection($order, $user->d_id);
                $targetDeptId = $request->input('d_id');
                if ($targetDeptId) {
                    // Move to specific department for rework
                    $this->handleRejectionToDepartment($order, $user->d_id, (int) $targetDeptId);
                } else {
                    // Default behavior: move to previous department
                    $this->handleRejectionToPrevious($order, $user->d_id);
                }

            } elseif ($statusNorm === OrderStep::STATUS_PROGRESS) {
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

    /**
     * Move to next department in the flow
     */
    private function moveToNextDepartment(Order $order, int $currentDeptId): void
    {
        $flow = array_map('intval', (array) $order->production_step);
        $currentIndex = array_search($currentDeptId, $flow, true);

        if ($currentIndex !== false && isset($flow[$currentIndex + 1])) {
            $nextDeptId = (int) $flow[$currentIndex + 1];

            OrderStep::where('o_id', $order->id)
                ->where('d_id', $nextDeptId)
                ->update(['status' => OrderStep::STATUS_PROGRESS]);

            $order->update(['status' => OrderStep::STATUS_PROGRESS]);
        } else {
            // No next step -> whole order completed
            $order->update(['status' => OrderStep::STATUS_COMPLETED]);
        }
    }

    /**
     * Handle rejection - move back to previous department (TFO)
     */
    private function handleRejectionToDepartment(Order $order, int $rejectingDeptId, int $targetDeptId): void
    {
        $flow = array_map('intval', (array) $order->production_step);

        // Check if target department exists in the flow
        $targetIndex = array_search($targetDeptId, $flow, true);
        $rejectingIndex = array_search($rejectingDeptId, $flow, true);

        if ($targetIndex !== false && $targetIndex <= $rejectingIndex) {
            // Reset target department status to progress for rework
            OrderStep::where('o_id', $order->id)
                ->where('d_id', $targetDeptId)
                ->update([
                    'status' => OrderStep::STATUS_PROGRESS,
                    'note' => 'Sent back for rework after rejection'
                ]);

            // Set all departments after the target (including rejecting one) to pending
            for ($i = $targetIndex + 1; $i < count($flow); $i++) {
                OrderStep::where('o_id', $order->id)
                    ->where('d_id', $flow[$i])
                    ->update(['status' => OrderStep::STATUS_PENDING]);
            }

            // Update order status to indicate it's back for rework
            $order->update(['status' => 'rework']);

        } else {
            // If target department is invalid, fall back to previous department
            $this->handleRejectionToPrevious($order, $rejectingDeptId);
        }
    }

    /**
     * Handle rejection - move back to previous department for rework
     */
    private function handleRejectionToPrevious(Order $order, int $rejectingDeptId): void
    {
        $flow = array_map('intval', (array) $order->production_step);
        $currentIndex = array_search($rejectingDeptId, $flow, true);

        if ($currentIndex !== false && $currentIndex > 0) {
            // Find the previous department in the flow
            $previousDeptId = $flow[$currentIndex - 1];

            // Reset previous department status to progress for rework
            OrderStep::where('o_id', $order->id)
                ->where('d_id', $previousDeptId)
                ->update([
                    'status' => OrderStep::STATUS_PROGRESS,
                    'note' => 'Sent back for rework after rejection'
                ]);

            // Set the rejecting department and all subsequent departments to pending
            for ($i = $currentIndex; $i < count($flow); $i++) {
                OrderStep::where('o_id', $order->id)
                    ->where('d_id', $flow[$i])
                    ->update(['status' => OrderStep::STATUS_PENDING]);
            }

            // Update order status to indicate it's back for rework
            $order->update(['status' => 'rework']);

        } else {
            // If the first department rejects, mark as completed (can't go back further)
            $order->update(['status' => OrderStep::STATUS_COMPLETED]);
        }
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