<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Voucher;

use App\Models\VoucherPackage;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class VoucherPackageListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'packages' => VoucherPackage::query()->withCount('cards')->latest()->paginate(),
        ];
    }

    public function name(): ?string
    {
        return 'Voucher Packages';
    }

    public function permission(): ?iterable
    {
        return ['platform.vouchers.packages'];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Add Voucher Package')
                ->icon('bs.plus-circle')
                ->route('platform.vouchers.packages.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('packages', [
                TD::make('name'),
                TD::make('credits'),
                TD::make('price_riel', 'Price')->align(TD::ALIGN_RIGHT),
                TD::make('cards_count', 'Cards')->align(TD::ALIGN_CENTER),
                TD::make('is_active', 'Active')->render(fn (VoucherPackage $package) => $package->is_active ? 'Yes' : 'No'),
                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->render(fn (VoucherPackage $package) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Edit')->icon('bs.pencil')->route('platform.vouchers.packages.edit', $package),
                        ])),
            ]),
        ];
    }
}
