<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog;

use App\Models\MenuItem;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class MenuItemListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'items' => MenuItem::query()
                ->with('category')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->paginate(),
        ];
    }

    public function name(): ?string
    {
        return 'Menu Items';
    }

    public function description(): ?string
    {
        return 'CRUD menu frontend: nama, kategori, harga, gambar, dan status tampil.';
    }

    public function permission(): ?iterable
    {
        return ['platform.catalog.items'];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Add Menu Item')
                ->icon('bs.plus-circle')
                ->route('platform.catalog.items.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('items', [
                TD::make('name')->sort(),
                TD::make('category.name', 'Category'),
                TD::make('price_riel', 'Price')->align(TD::ALIGN_RIGHT),
                TD::make('is_featured', 'Featured')
                    ->align(TD::ALIGN_CENTER)
                    ->render(fn (MenuItem $item) => $item->is_featured ? 'Yes' : 'No'),
                TD::make('is_active', 'Active')
                    ->align(TD::ALIGN_CENTER)
                    ->render(fn (MenuItem $item) => $item->is_active ? 'Yes' : 'No'),
                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->render(fn (MenuItem $item) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Edit')
                                ->icon('bs.pencil')
                                ->route('platform.catalog.items.edit', $item),
                        ])),
            ]),
        ];
    }
}
