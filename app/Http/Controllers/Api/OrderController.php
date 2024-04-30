<?php

namespace App\Http\Controllers\Api;

use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexOrderRequest $request)
    {
        $status = $request->input('status');
        $min = $request->input('min');
        $max = $request->input('max');
        $nationalCode = $request->input('national_code');
        $mobile = $request->input('mobile');

        $query = Order::when($status,  function (Builder $query) use ($status) {
            $query->whereStatus($status);
        });

        $query->filterNull('amount', $min, '>=');
        $query->filterNull('amount', $max, '<=');

        $query->when($nationalCode or $mobile, function (Builder $query) use ($nationalCode, $mobile) {
            $query->whereHas('user', function (Builder $userQuery) use ($nationalCode, $mobile) {
                $userQuery->when($nationalCode,  function (Builder $userQuery) use ($nationalCode) {
                    $userQuery->where('national_code', 'LIKE', "%$nationalCode%");
                });
                $userQuery->when($mobile,  function (Builder $userQuery) use ($mobile) {
                    $userQuery->where('mobile', 'LIKE', "%$mobile%");
                });
            });
        });

        return Response::data(new OrderCollection($query->paginate()));
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
    public function store(StoreOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
