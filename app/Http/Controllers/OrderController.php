<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponseHelper;
use App\Http\Filters\OrderFilter;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private Order $order;

    private OrderRepositoryInterface $orderRepositoryInterface;

    private OrderService $orderService;

    public function __construct(Order $order, OrderService $orderService, OrderRepositoryInterface $orderRepositoryInterface)
    {
        $this->order = $order;
        $this->orderService = $orderService;
        $this->orderRepositoryInterface = $orderRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(FormRequest $request, OrderFilter $filter)
    {
        $this->authorize('viewAny', $this->order);
        $params = $request->only('user_id', 'trip_id');
        if (!Auth::user()->isAdmin()) $params['user_id'] = Auth::id();

        $orders = $this->orderRepositoryInterface->index($params, $filter);

        return JsonResponseHelper::success(OrderResource::collection($orders));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $params = $request->only('user_id', 'trip_id', 'comment', 'additional_services', 'tourists');

        $order = $this->orderService->createOrder($params);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = $this->orderRepositoryInterface->getById($id);
        $this->authorize('view', $order);

        return JsonResponseHelper::success(new OrderResource($order));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
