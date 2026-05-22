<?php

namespace App\Http\Controllers;

use App\Services\Order\Commands\CancelOrderCommand;
use App\Services\Order\Commands\ConfirmOrderCommand;
use App\Services\Order\Commands\PlaceOrderCommand;
use App\Services\Order\OrderApplicationService;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Domains\Queries\OrderQuery;

class OrderController extends Controller
{
    /**
     * 주문을 처리하는 API 엔드포인트
     */
    public function orderProduct(Request $request, OrderApplicationService $orderApplicationService)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'used_points' => 'integer',
        ]);

        $orderApplicationService->placeOrder(
            new PlaceOrderCommand(
                userId: $request->user()->id,
                productId: (int) $validatedData['product_id'],
                quantity: (int) $validatedData['quantity'],
                usedPoints: (string) ($validatedData['used_points'] ?? '0'),
            ),
        );

        return redirect()->route('mypage.order.list');
    }

    public function orderList(Request $request, OrderQuery $orderQuery)
    {
        $orders = $orderQuery
            ->forUser($request->user()->id)
            ->setSearch($request->input('search'), $request->input('search_by'))
            ->query()
            ->paginate(12)
            ->withQueryString();

        return view('mypage.order.list', ['orders' => $orders]);
    }

    public function cancelOrder(Request $request, Order $order, OrderApplicationService $orderApplicationService)
    {
        abort_if($request->user()->id !== $order->user_id, 403);

        $orderApplicationService->cancelOrder(
            new CancelOrderCommand($order->getKey(), $request->user()->id, 0),
        );

        return redirect()
            ->route('mypage.order.list', request()->query())
            ->with('alert', '주문이 취소되었습니다.');
    }

    public function confirmOrder(Request $request, Order $order, OrderApplicationService $orderApplicationService)
    {
        abort_if($request->user()->id !== $order->user_id, 403);

        $orderApplicationService->confirmOrder(
            new ConfirmOrderCommand($order->getKey(), $request->user()->id),
        );

        return redirect()
            ->route('mypage.order.list', request()->query())
            ->with('alert', '주문이 확정되었습니다.');
    }
}
