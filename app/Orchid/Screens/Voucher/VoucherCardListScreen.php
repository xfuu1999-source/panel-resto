<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Voucher;

use App\Models\VoucherCard;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class VoucherCardListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'cards' => VoucherCard::query()->with('package')->latest()->paginate(),
        ];
    }

    public function name(): ?string
    {
        return 'Voucher Cards';
    }

    public function permission(): ?iterable
    {
        return ['platform.vouchers.cards'];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Add Voucher Card')
                ->icon('bs.plus-circle')
                ->route('platform.vouchers.cards.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('cards', [
                TD::make('code'),
                TD::make('customer_name', 'Customer'),
                TD::make('package.name', 'Package'),
                TD::make('remaining_credits', 'Remaining')->align(TD::ALIGN_CENTER),
                TD::make('status')->render(fn (VoucherCard $card) => VoucherCard::statusOptions()[$card->status] ?? $card->status),
                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->render(fn (VoucherCard $card) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Edit')->icon('bs.pencil')->route('platform.vouchers.cards.edit', $card),
                        ])),
            ]),
        ];
    }
}
