<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\CashierAccount;
use App\Models\CmsPage;
use App\Models\CustomerOrder;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\PromoBanner;
use App\Models\VoucherCard;
use App\Models\VoucherPackage;
use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'stats' => [
                'categories' => MenuCategory::count(),
                'menuItems' => MenuItem::count(),
                'promoBanners' => PromoBanner::count(),
                'pages' => CmsPage::count(),
                'voucherPackages' => VoucherPackage::count(),
                'voucherCards' => VoucherCard::count(),
                'cashiers' => CashierAccount::count(),
                'pendingOrders' => CustomerOrder::query()
                    ->where('order_status', CustomerOrder::ORDER_STATUS_PENDING)
                    ->count(),
            ],
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'RestoPanel Dashboard';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Kontrol menu, konten, voucher, kasir, dan monitoring order pelanggan dari satu panel.';
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::view('platform.dashboard'),
        ];
    }
}
