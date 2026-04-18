<?php

declare(strict_types=1);

use App\Orchid\Screens\Catalog\MenuCategoryEditScreen;
use App\Orchid\Screens\Catalog\MenuCategoryListScreen;
use App\Orchid\Screens\Catalog\MenuItemEditScreen;
use App\Orchid\Screens\Catalog\MenuItemListScreen;
use App\Orchid\Screens\Content\CmsPageEditScreen;
use App\Orchid\Screens\Content\CmsPageListScreen;
use App\Orchid\Screens\Content\PromoBannerEditScreen;
use App\Orchid\Screens\Content\PromoBannerListScreen;
use App\Orchid\Screens\Operations\CashierAccountEditScreen;
use App\Orchid\Screens\Operations\CashierAccountListScreen;
use App\Orchid\Screens\Operations\CustomerOrderListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\Voucher\VoucherCardEditScreen;
use App\Orchid\Screens\Voucher\VoucherCardListScreen;
use App\Orchid\Screens\Voucher\VoucherPackageEditScreen;
use App\Orchid\Screens\Voucher\VoucherPackageListScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

Route::screen('catalog/categories', MenuCategoryListScreen::class)->name('platform.catalog.categories');
Route::screen('catalog/categories/create', MenuCategoryEditScreen::class)->name('platform.catalog.categories.create');
Route::screen('catalog/categories/{category}/edit', MenuCategoryEditScreen::class)->name('platform.catalog.categories.edit');

Route::screen('catalog/items', MenuItemListScreen::class)->name('platform.catalog.items');
Route::screen('catalog/items/create', MenuItemEditScreen::class)->name('platform.catalog.items.create');
Route::screen('catalog/items/{item}/edit', MenuItemEditScreen::class)->name('platform.catalog.items.edit');

Route::screen('content/promotions', PromoBannerListScreen::class)->name('platform.content.promotions');
Route::screen('content/promotions/create', PromoBannerEditScreen::class)->name('platform.content.promotions.create');
Route::screen('content/promotions/{promoBanner}/edit', PromoBannerEditScreen::class)->name('platform.content.promotions.edit');

Route::screen('content/pages', CmsPageListScreen::class)->name('platform.content.pages');
Route::screen('content/pages/create', CmsPageEditScreen::class)->name('platform.content.pages.create');
Route::screen('content/pages/{page}/edit', CmsPageEditScreen::class)->name('platform.content.pages.edit');

Route::screen('vouchers/packages', VoucherPackageListScreen::class)->name('platform.vouchers.packages');
Route::screen('vouchers/packages/create', VoucherPackageEditScreen::class)->name('platform.vouchers.packages.create');
Route::screen('vouchers/packages/{package}/edit', VoucherPackageEditScreen::class)->name('platform.vouchers.packages.edit');

Route::screen('vouchers/cards', VoucherCardListScreen::class)->name('platform.vouchers.cards');
Route::screen('vouchers/cards/create', VoucherCardEditScreen::class)->name('platform.vouchers.cards.create');
Route::screen('vouchers/cards/{card}/edit', VoucherCardEditScreen::class)->name('platform.vouchers.cards.edit');

Route::screen('operations/cashiers', CashierAccountListScreen::class)->name('platform.operations.cashiers');
Route::screen('operations/cashiers/create', CashierAccountEditScreen::class)->name('platform.operations.cashiers.create');
Route::screen('operations/cashiers/{cashier}/edit', CashierAccountEditScreen::class)->name('platform.operations.cashiers.edit');

Route::screen('operations/orders', CustomerOrderListScreen::class)->name('platform.operations.orders');
