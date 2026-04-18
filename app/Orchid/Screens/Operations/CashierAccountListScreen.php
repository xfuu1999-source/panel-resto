<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Operations;

use App\Models\CashierAccount;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class CashierAccountListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'cashiers' => CashierAccount::query()->latest()->paginate(),
        ];
    }

    public function name(): ?string
    {
        return 'Cashier Accounts';
    }

    public function permission(): ?iterable
    {
        return ['platform.operations.cashiers'];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Add Cashier')
                ->icon('bs.plus-circle')
                ->route('platform.operations.cashiers.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('cashiers', [
                TD::make('name'),
                TD::make('code'),
                TD::make('email'),
                TD::make('shift_label', 'Shift'),
                TD::make('is_active', 'Active')->render(fn (CashierAccount $cashier) => $cashier->is_active ? 'Yes' : 'No'),
                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->render(fn (CashierAccount $cashier) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Edit')->icon('bs.pencil')->route('platform.operations.cashiers.edit', $cashier),
                        ])),
            ]),
        ];
    }
}
