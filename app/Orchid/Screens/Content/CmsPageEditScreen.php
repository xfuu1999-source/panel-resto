<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Content;

use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CmsPageEditScreen extends Screen
{
    public function query(CmsPage $page): iterable
    {
        return [
            'page' => $page,
        ];
    }

    public function name(): ?string
    {
        return request()->route('page')?->exists ? 'Edit CMS Page' : 'Create CMS Page';
    }

    public function permission(): ?iterable
    {
        return ['platform.content.pages'];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Remove')
                ->icon('bs.trash3')
                ->confirm('Delete this page?')
                ->method('remove')
                ->canSee(request()->route('page')?->exists === true),
            Button::make('Save')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('page.title')->title('Title')->required(),
                Input::make('page.slug')->title('Slug')->help('Kosongkan untuk generate otomatis.'),
                TextArea::make('page.excerpt')->title('Excerpt')->rows(3),
                TextArea::make('page.content')->title('Content')->rows(16),
                CheckBox::make('page.is_active')->title('Active')->sendTrueOrFalse()->value(true),
            ]),
        ];
    }

    public function save(CmsPage $page, Request $request)
    {
        $payload = $request->validate([
            'page.title' => ['required', 'string', 'max:255'],
            'page.slug' => ['nullable', 'string', 'max:255', Rule::unique(CmsPage::class, 'slug')->ignore($page)],
            'page.excerpt' => ['nullable', 'string'],
            'page.content' => ['nullable', 'string'],
            'page.is_active' => ['nullable', 'boolean'],
        ])['page'];

        $payload['slug'] = Str::slug($payload['slug'] ?: $payload['title']);
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);

        $page->fill($payload)->save();

        Toast::info('CMS page saved.');

        return redirect()->route('platform.content.pages');
    }

    public function remove(CmsPage $page)
    {
        $page->delete();
        Toast::info('CMS page removed.');

        return redirect()->route('platform.content.pages');
    }
}
