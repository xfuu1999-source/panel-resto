<div class="row g-3">
    <div class="col-12">
        <div class="rounded shadow-sm bg-white p-4">
            <h2 class="mb-2">Control Panel Web Resto</h2>
            <p class="text-muted mb-0">
                Panel ini disiapkan untuk mengelola menu, promo, halaman CMS, voucher card, akun kasir, dan monitoring order pelanggan.
            </p>
        </div>
    </div>

    @php($cards = [
        ['label' => 'Menu Categories', 'value' => $stats['categories'] ?? 0, 'route' => route('platform.catalog.categories')],
        ['label' => 'Menu Items', 'value' => $stats['menuItems'] ?? 0, 'route' => route('platform.catalog.items')],
        ['label' => 'Promo Banners', 'value' => $stats['promoBanners'] ?? 0, 'route' => route('platform.content.promotions')],
        ['label' => 'CMS Pages', 'value' => $stats['pages'] ?? 0, 'route' => route('platform.content.pages')],
        ['label' => 'Voucher Packages', 'value' => $stats['voucherPackages'] ?? 0, 'route' => route('platform.vouchers.packages')],
        ['label' => 'Voucher Cards', 'value' => $stats['voucherCards'] ?? 0, 'route' => route('platform.vouchers.cards')],
        ['label' => 'Cashiers', 'value' => $stats['cashiers'] ?? 0, 'route' => route('platform.operations.cashiers')],
        ['label' => 'Pending Orders', 'value' => $stats['pendingOrders'] ?? 0, 'route' => route('platform.operations.orders')],
    ])

    @foreach($cards as $card)
        <div class="col-sm-6 col-xl-3">
            <a href="{{ $card['route'] }}" class="text-decoration-none">
                <div class="rounded shadow-sm bg-white p-4 h-100 border">
                    <div class="text-muted small">{{ $card['label'] }}</div>
                    <div class="display-6 text-dark fw-semibold mt-2">{{ $card['value'] }}</div>
                </div>
            </a>
        </div>
    @endforeach
</div>
