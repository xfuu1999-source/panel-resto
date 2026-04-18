<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Models\CustomerOrder;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Dashboard')
                ->icon('bs.speedometer2')
                ->title('Navigation')
                ->route(config('platform.index')),

            Menu::make('Menu Categories')
                ->icon('bs.tags')
                ->route('platform.catalog.categories')
                ->permission('platform.catalog.categories')
                ->title('Catalog'),

            Menu::make('Menu Items')
                ->icon('bs.cup-hot')
                ->route('platform.catalog.items')
                ->permission('platform.catalog.items'),

            Menu::make('Promo Banners')
                ->icon('bs.megaphone')
                ->route('platform.content.promotions')
                ->permission('platform.content.promotions')
                ->title('Content'),

            Menu::make('CMS Pages')
                ->icon('bs.file-earmark-richtext')
                ->route('platform.content.pages')
                ->permission('platform.content.pages'),

            Menu::make('Voucher Packages')
                ->icon('bs.credit-card')
                ->route('platform.vouchers.packages')
                ->permission('platform.vouchers.packages')
                ->title('Vouchers'),

            Menu::make('Voucher Cards')
                ->icon('bs.ticket-perforated')
                ->route('platform.vouchers.cards')
                ->permission('platform.vouchers.cards'),

            Menu::make('Cashier Accounts')
                ->icon('bs.person-badge')
                ->route('platform.operations.cashiers')
                ->permission('platform.operations.cashiers')
                ->title('Operations'),

            Menu::make('Customer Orders')
                ->icon('bs.receipt')
                ->route('platform.operations.orders')
                ->permission('platform.operations.orders')
                ->badge(fn () => CustomerOrder::query()
                    ->where('order_status', CustomerOrder::ORDER_STATUS_PENDING)
                    ->count(), Color::WARNING)
                ->divider(),

            Menu::make(__('Users'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access Controls')),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group('Catalog')
                ->addPermission('platform.catalog.categories', 'Menu Categories')
                ->addPermission('platform.catalog.items', 'Menu Items'),
            ItemPermission::group('Content')
                ->addPermission('platform.content.promotions', 'Promo Banners')
                ->addPermission('platform.content.pages', 'CMS Pages'),
            ItemPermission::group('Vouchers')
                ->addPermission('platform.vouchers.packages', 'Voucher Packages')
                ->addPermission('platform.vouchers.cards', 'Voucher Cards'),
            ItemPermission::group('Operations')
                ->addPermission('platform.operations.cashiers', 'Cashier Accounts')
                ->addPermission('platform.operations.orders', 'Customer Orders'),
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
