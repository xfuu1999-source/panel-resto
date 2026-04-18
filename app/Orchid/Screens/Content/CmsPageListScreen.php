<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Content;

use App\Models\CmsPage;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class CmsPageListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'pages' => CmsPage::query()->latest()->paginate(),
        ];
    }

    public function name(): ?string
    {
        return 'CMS Pages';
    }

    public function permission(): ?iterable
    {
        return ['platform.content.pages'];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Add Page')
                ->icon('bs.plus-circle')
                ->route('platform.content.pages.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('pages', [
                TD::make('title'),
                TD::make('slug'),
                TD::make('is_active', 'Active')->render(fn (CmsPage $page) => $page->is_active ? 'Yes' : 'No'),
                TD::make('updated_at', 'Updated')->render(fn (CmsPage $page) => optional($page->updated_at)?->format('d M Y H:i')),
                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->render(fn (CmsPage $page) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Edit')->icon('bs.pencil')->route('platform.content.pages.edit', $page),
                        ])),
            ]),
        ];
    }
}
