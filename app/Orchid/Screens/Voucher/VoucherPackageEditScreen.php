<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Voucher;

use App\Models\VoucherPackage;
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

class VoucherPackageEditScreen extends Screen
{
    public function query(VoucherPackage $package): iterable
    {
        return [
            'package' => $package,
        ];
    }

    public function name(): ?string
    {
        return request()->route('package')?->exists ? 'Edit Voucher Package' : 'Create Voucher Package';
    }

    public function permission(): ?iterable
    {
        return ['platform.vouchers.packages'];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Remove')
                ->icon('bs.trash3')
                ->confirm('Delete this voucher package?')
                ->method('remove')
                ->canSee(request()->route('package')?->exists === true),
            Button::make('Save')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('package.name')->title('Name')->required(),
                Input::make('package.slug')->title('Slug')->help('Kosongkan untuk generate otomatis.'),
                Input::make('package.credits')->title('Credits')->type('number')->required(),
                Input::make('package.price_riel')->title('Price (Riel)')->type('number')->required(),
                CheckBox::make('package.is_active')->title('Active')->sendTrueOrFalse()->value(true),
                TextArea::make('package.description')->title('Description')->rows(4),
            ]),
        ];
    }

    public function save(VoucherPackage $package, Request $request)
    {
        $payload = $request->validate([
            'package.name' => ['required', 'string', 'max:255'],
            'package.slug' => ['nullable', 'string', 'max:255', Rule::unique(VoucherPackage::class, 'slug')->ignore($package)],
            'package.credits' => ['required', 'integer', 'min:1'],
            'package.price_riel' => ['required', 'integer', 'min:0'],
            'package.description' => ['nullable', 'string'],
            'package.is_active' => ['nullable', 'boolean'],
        ])['package'];

        $payload['slug'] = Str::slug($payload['slug'] ?: $payload['name']);
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);

        $package->fill($payload)->save();

        Toast::info('Voucher package saved.');

        return redirect()->route('platform.vouchers.packages');
    }

    public function remove(VoucherPackage $package)
    {
        $package->delete();
        Toast::info('Voucher package removed.');

        return redirect()->route('platform.vouchers.packages');
    }
}
