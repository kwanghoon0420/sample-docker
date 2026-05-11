<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Domains\Queries\OrderQuery;
use App\Services\Order\Commands\CancelOrderCommand;
use App\Services\Order\Commands\ConfirmOrderCommand;
use App\Services\Order\OrderApplicationService;

class OrderController extends Controller
{
    public function orderList(Request $request, OrderQuery $orderQuery)
    {
        $orders = $orderQuery
            ->setSearch($request->input('search'), $request->input('search_by'))
            ->query()
            ->paginate(10);

        return view('admin.order_list', ['orders' => $orders]);
    }

    public function cancelOrder(Request $request, Order $order, OrderApplicationService $orderApplicationService)
    {
        $orderApplicationService->cancelOrder(
            new CancelOrderCommand($order->getKey(), $request->user()->id),
        );

        return redirect()
            ->route('admin.order_list', request()->query())
            ->with('alert', '주문이 취소되었습니다.');
    }

    public function confirmOrder(Request $request, Order $order, OrderApplicationService $orderApplicationService)
    {
        $orderApplicationService->confirmOrder(
            new ConfirmOrderCommand($order->getKey(), $request->user()->id),
        );

        return redirect()
            ->route('admin.order_list', request()->query())
            ->with('alert', '주문이 확정되었습니다.');
    }
}
