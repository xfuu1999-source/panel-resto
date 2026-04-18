<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Content;

use App\Models\PromoBanner;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PromoBannerEditScreen extends Screen
{
    public function query(PromoBanner $promoBanner): iterable
    {
        return [
            'promoBanner' => $promoBanner,
        ];
    }

    public function name(): ?string
    {
        return request()->route('promoBanner')?->exists ? 'Edit Promo Banner' : 'Create Promo Banner';
    }

    public function permission(): ?iterable
    {
        return ['platform.content.promotions'];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Remove')
                ->icon('bs.trash3')
                ->confirm('Delete this promo banner?')
                ->method('remove')
                ->canSee(request()->route('promoBanner')?->exists === true),
            Button::make('Save')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('promoBanner.title')->title('Title')->required(),
                TextArea::make('promoBanner.subtitle')->title('Subtitle')->rows(3),
                Input::make('promoBanner.image_url')->title('Image URL'),
                Input::make('promoBanner.cta_label')->title('CTA Label'),
                Input::make('promoBanner.cta_url')->title('CTA URL'),
                Input::make('promoBanner.sort_order')->title('Sort Order')->type('number')->value(0),
                DateTimer::make('promoBanner.starts_at')->title('Starts At')->allowInput(),
                DateTimer::make('promoBanner.ends_at')->title('Ends At')->allowInput(),
                CheckBox::make('promoBanner.is_active')->title('Active')->sendTrueOrFalse()->value(true),
            ]),
        ];
    }

    public function save(PromoBanner $promoBanner, Request $request)
    {
        $payload = $request->validate([
            'promoBanner.title' => ['required', 'string', 'max:255'],
            'promoBanner.subtitle' => ['nullable', 'string'],
            'promoBanner.image_url' => ['nullable', 'string', 'max:2048'],
            'promoBanner.cta_label' => ['nullable', 'string', 'max:255'],
            'promoBanner.cta_url' => ['nullable', 'string', 'max:2048'],
            'promoBanner.sort_order' => ['nullable', 'integer', 'min:0'],
            'promoBanner.starts_at' => ['nullable', 'date'],
            'promoBanner.ends_at' => ['nullable', 'date'],
            'promoBanner.is_active' => ['nullable', 'boolean'],
        ])['promoBanner'];

        $payload['sort_order'] = (int) ($payload['sort_order'] ?? 0);
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);

        $promoBanner->fill($payload)->save();

        Toast::info('Promo banner saved.');

        return redirect()->route('platform.content.promotions');
    }

    public function remove(PromoBanner $promoBanner)
    {
        $promoBanner->delete();
        Toast::info('Promo banner removed.');

        return redirect()->route('platform.content.promotions');
    }
}
