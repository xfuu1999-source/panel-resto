<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class MenuItemEditScreen extends Screen
{
    public function query(MenuItem $item): iterable
    {
        return [
            'item' => $item,
        ];
    }

    public function name(): ?string
    {
        return request()->route('item')?->exists ? 'Edit Menu Item' : 'Create Menu Item';
    }

    public function permission(): ?iterable
    {
        return ['platform.catalog.items'];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Remove')
                ->icon('bs.trash3')
                ->confirm('Delete this menu item?')
                ->method('remove')
                ->canSee(request()->route('item')?->exists === true),
            Button::make('Save')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Select::make('item.category_id')
                    ->title('Category')
                    ->options(MenuCategory::query()->orderBy('sort_order')->pluck('name', 'id')->toArray())
                    ->required(),
                Input::make('item.name')
                    ->title('Name')
                    ->required(),
                Input::make('item.slug')
                    ->title('Slug')
                    ->help('Kosongkan untuk generate otomatis.'),
                Input::make('item.price_riel')
                    ->title('Price (Riel)')
                    ->type('number')
                    ->required(),
                Input::make('item.image_url')
                    ->title('Image URL'),
                Input::make('item.sort_order')
                    ->title('Sort Order')
                    ->type('number')
                    ->value(0),
                CheckBox::make('item.is_active')
                    ->title('Active')
                    ->sendTrueOrFalse()
                    ->value(true),
                CheckBox::make('item.is_featured')
                    ->title('Featured')
                    ->sendTrueOrFalse()
                    ->value(false),
                TextArea::make('item.description')
                    ->title('Description')
                    ->rows(5),
            ]),
        ];
    }

    public function save(MenuItem $item, Request $request)
    {
        $payload = $request->validate([
            'item.category_id' => ['required', 'exists:menu_categories,id'],
            'item.name' => ['required', 'string', 'max:255'],
            'item.slug' => ['nullable', 'string', 'max:255', Rule::unique(MenuItem::class, 'slug')->ignore($item)],
            'item.description' => ['nullable', 'string'],
            'item.image_url' => ['nullable', 'string', 'max:2048'],
            'item.price_riel' => ['required', 'integer', 'min:0'],
            'item.sort_order' => ['nullable', 'integer', 'min:0'],
            'item.is_active' => ['nullable', 'boolean'],
            'item.is_featured' => ['nullable', 'boolean'],
        ])['item'];

        $payload['slug'] = Str::slug($payload['slug'] ?: $payload['name']);
        $payload['sort_order'] = (int) ($payload['sort_order'] ?? 0);
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);
        $payload['is_featured'] = (bool) ($payload['is_featured'] ?? false);

        $item->fill($payload)->save();

        Toast::info('Menu item saved.');

        return redirect()->route('platform.catalog.items');
    }

    public function remove(MenuItem $item)
    {
        $item->delete();
        Toast::info('Menu item removed.');

        return redirect()->route('platform.catalog.items');
    }
}
