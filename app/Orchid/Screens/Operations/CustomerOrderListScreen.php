<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Operations;

use App\Models\CustomerOrder;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CustomerOrderListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'orders' => CustomerOrder::query()
                ->latest('ordered_at')
                ->latest()
                ->paginate(),
        ];
    }

    public function name(): ?string
    {
        return 'Customer Orders';
    }

    public function description(): ?string
    {
        return 'Monitoring order web pelanggan, status proses, dan status pembayaran.';
    }

    public function permission(): ?iterable
    {
        return ['platform.operations.orders'];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('orders', [
                TD::make('order_code', 'Order'),
                TD::make('customer_name', 'Customer')
                    ->render(fn (CustomerOrder $order) => trim($order->customer_name.' '.$order->phone)),
                TD::make('payment_method', 'Payment')
                    ->render(fn (CustomerOrder $order) => CustomerOrder::paymentMethodOptions()[$order->payment_method] ?? $order->payment_method),
                TD::make('payment_status', 'Payment Status')
                    ->render(fn (CustomerOrder $order) => CustomerOrder::paymentStatusOptions()[$order->payment_status] ?? $order->payment_status),
                TD::make('order_status', 'Order Status')
                    ->render(fn (CustomerOrder $order) => CustomerOrder::orderStatusOptions()[$order->order_status] ?? $order->order_status),
                TD::make('total_riel', 'Total')->align(TD::ALIGN_RIGHT),
                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->render(fn (CustomerOrder $order) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Button::make('Mark Paid')
                                ->method('updatePaymentStatus', ['id' => $order->id, 'value' => CustomerOrder::PAYMENT_STATUS_PAID]),
                            Button::make('Set Processing')
                                ->method('updateOrderStatus', ['id' => $order->id, 'value' => CustomerOrder::ORDER_STATUS_PROCESSING]),
                            Button::make('Set Ready')
                                ->method('updateOrderStatus', ['id' => $order->id, 'value' => CustomerOrder::ORDER_STATUS_READY]),
                            Button::make('Set Completed')
                                ->method('updateOrderStatus', ['id' => $order->id, 'value' => CustomerOrder::ORDER_STATUS_COMPLETED]),
                        ])),
            ]),
        ];
    }

    public function updatePaymentStatus(Request $request): void
    {
        $data = $request->validate([
            'id' => ['required', 'exists:customer_orders,id'],
            'value' => ['required'],
        ]);

        CustomerOrder::findOrFail($data['id'])->update([
            'payment_status' => $data['value'],
        ]);

        Toast::info('Payment status updated.');
    }

    public function updateOrderStatus(Request $request): void
    {
        $data = $request->validate([
            'id' => ['required', 'exists:customer_orders,id'],
            'value' => ['required'],
        ]);

        CustomerOrder::findOrFail($data['id'])->update([
            'order_status' => $data['value'],
        ]);

        Toast::info('Order status updated.');
    }
}
