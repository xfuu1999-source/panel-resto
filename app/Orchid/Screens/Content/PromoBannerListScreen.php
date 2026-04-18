<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Content;

use App\Models\PromoBanner;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class PromoBannerListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'banners' => PromoBanner::query()
                ->orderBy('sort_order')
                ->orderByDesc('created_at')
                ->paginate(),
        ];
    }

    public function name(): ?string
    {
        return 'Promo Banners';
    }

    public function permission(): ?iterable
    {
        return ['platform.content.promotions'];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Add Promo Banner')
                ->icon('bs.plus-circle')
                ->route('platform.content.promotions.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('banners', [
                TD::make('title'),
                TD::make('cta_label', 'CTA'),
                TD::make('starts_at', 'Start')->render(fn (PromoBanner $banner) => optional($banner->starts_at)?->format('d M Y H:i')),
                TD::make('ends_at', 'End')->render(fn (PromoBanner $banner) => optional($banner->ends_at)?->format('d M Y H:i')),
                TD::make('is_active', 'Active')->render(fn (PromoBanner $banner) => $banner->is_active ? 'Yes' : 'No'),
                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->render(fn (PromoBanner $banner) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Edit')->icon('bs.pencil')->route('platform.content.promotions.edit', $banner),
                        ])),
            ]),
        ];
    }
}
