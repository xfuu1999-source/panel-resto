<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog;

use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;

class MenuCategoryEditScreen extends Screen
{
    public function query(MenuCategory $category): iterable
    {
        return [
            'category' => $category,
        ];
    }

    public function name(): ?string
    {
        return request()->route('category')?->exists ? 'Edit Menu Category' : 'Create Menu Category';
    }

    public function permission(): ?iterable
    {
        return ['platform.catalog.categories'];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Remove')
                ->icon('bs.trash3')
                ->confirm('Delete this category?')
                ->method('remove')
                ->canSee(request()->route('category')?->exists === true),
            Button::make('Save')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('category.name')
                    ->title('Name')
                    ->required(),
                Input::make('category.slug')
                    ->title('Slug')
                    ->help('Kosongkan untuk generate otomatis dari nama.'),
                Input::make('category.sort_order')
                    ->title('Sort Order')
                    ->type('number')
                    ->value(0),
                CheckBox::make('category.is_active')
                    ->title('Active')
                    ->sendTrueOrFalse()
                    ->value(true),
                TextArea::make('category.description')
                    ->title('Description')
                    ->rows(4),
            ]),
        ];
    }

    public function save(MenuCategory $category, Request $request)
    {
        $payload = $request->validate([
            'category.name' => ['required', 'string', 'max:255'],
            'category.slug' => ['nullable', 'string', 'max:255', Rule::unique(MenuCategory::class, 'slug')->ignore($category)],
            'category.description' => ['nullable', 'string'],
            'category.sort_order' => ['nullable', 'integer', 'min:0'],
            'category.is_active' => ['nullable', 'boolean'],
        ])['category'];

        $payload['slug'] = Str::slug($payload['slug'] ?: $payload['name']);
        $payload['sort_order'] = (int) ($payload['sort_order'] ?? 0);
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);

        $category->fill($payload)->save();

        Toast::info('Category saved.');

        return redirect()->route('platform.catalog.categories');
    }

    public function remove(MenuCategory $category)
    {
        $category->delete();
        Toast::info('Category removed.');

        return redirect()->route('platform.catalog.categories');
    }
}
