<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Voucher;

use App\Models\VoucherCard;
use App\Models\VoucherPackage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class VoucherCardEditScreen extends Screen
{
    public function query(VoucherCard $card): iterable
    {
        return [
            'card' => $card,
        ];
    }

    public function name(): ?string
    {
        return request()->route('card')?->exists ? 'Edit Voucher Card' : 'Create Voucher Card';
    }

    public function permission(): ?iterable
    {
        return ['platform.vouchers.cards'];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Remove')
                ->icon('bs.trash3')
                ->confirm('Delete this voucher card?')
                ->method('remove')
                ->canSee(request()->route('card')?->exists === true),
            Button::make('Save')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Select::make('card.voucher_package_id')
                    ->title('Voucher Package')
                    ->options(VoucherPackage::query()->orderBy('name')->pluck('name', 'id')->toArray())
                    ->required(),
                Input::make('card.code')->title('Card Code')->required(),
                Input::make('card.customer_name')->title('Customer Name')->required(),
                Input::make('card.phone')->title('Phone'),
                Input::make('card.telegram')->title('Telegram'),
                Select::make('card.status')
                    ->title('Status')
                    ->options(VoucherCard::statusOptions())
                    ->required(),
                Input::make('card.total_credits')->title('Total Credits')->type('number')->required(),
                Input::make('card.remaining_credits')->title('Remaining Credits')->type('number')->required(),
                DateTimer::make('card.activated_at')->title('Activated At')->allowInput(),
                DateTimer::make('card.expires_at')->title('Expires At')->allowInput(),
                TextArea::make('card.notes')->title('Notes')->rows(4),
            ]),
        ];
    }

    public function save(VoucherCard $card, Request $request)
    {
        $payload = $request->validate([
            'card.voucher_package_id' => ['required', 'exists:voucher_packages,id'],
            'card.code' => ['required', 'string', 'max:255', Rule::unique(VoucherCard::class, 'code')->ignore($card)],
            'card.customer_name' => ['required', 'string', 'max:255'],
            'card.phone' => ['nullable', 'string', 'max:255'],
            'card.telegram' => ['nullable', 'string', 'max:255'],
            'card.status' => ['required', Rule::in(array_keys(VoucherCard::statusOptions()))],
            'card.total_credits' => ['required', 'integer', 'min:0'],
            'card.remaining_credits' => ['required', 'integer', 'min:0'],
            'card.activated_at' => ['nullable', 'date'],
            'card.expires_at' => ['nullable', 'date'],
            'card.notes' => ['nullable', 'string'],
        ])['card'];

        $card->fill($payload)->save();

        Toast::info('Voucher card saved.');

        return redirect()->route('platform.vouchers.cards');
    }

    public function remove(VoucherCard $card)
    {
        $card->delete();
        Toast::info('Voucher card removed.');

        return redirect()->route('platform.vouchers.cards');
    }
}
