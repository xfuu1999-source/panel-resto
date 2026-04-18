<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog;

use App\Models\MenuCategory;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class MenuCategoryListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'categories' => MenuCategory::query()
                ->withCount('items')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->paginate(),
        ];
    }

    public function name(): ?string
    {
        return 'Menu Categories';
    }

    public function description(): ?string
    {
        return 'Kelola kategori menu yang dipakai frontend dan backend order.';
    }

    public function permission(): ?iterable
    {
        return ['platform.catalog.categories'];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Add Category')
                ->icon('bs.plus-circle')
                ->route('platform.catalog.categories.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('categories', [
                TD::make('name')->sort(),
                TD::make('slug'),
                TD::make('items_count', 'Items')->align(TD::ALIGN_CENTER),
                TD::make('is_active', 'Active')
                    ->align(TD::ALIGN_CENTER)
                    ->render(fn (MenuCategory $category) => $category->is_active ? 'Yes' : 'No'),
                TD::make('sort_order', 'Sort')->align(TD::ALIGN_CENTER),
                TD::make('updated_at', 'Updated')->render(fn (MenuCategory $category) => optional($category->updated_at)?->format('d M Y H:i')),
                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->render(fn (MenuCategory $category) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Edit')
                                ->icon('bs.pencil')
                                ->route('platform.catalog.categories.edit', $category),
                        ])),
            ]),
        ];
    }
}
