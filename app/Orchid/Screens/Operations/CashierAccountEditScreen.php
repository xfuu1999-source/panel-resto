<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Operations;

use App\Models\CashierAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CashierAccountEditScreen extends Screen
{
    public function query(CashierAccount $cashier): iterable
    {
        return [
            'cashier' => $cashier,
        ];
    }

    public function name(): ?string
    {
        return request()->route('cashier')?->exists ? 'Edit Cashier Account' : 'Create Cashier Account';
    }

    public function permission(): ?iterable
    {
        return ['platform.operations.cashiers'];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Remove')
                ->icon('bs.trash3')
                ->confirm('Delete this cashier account?')
                ->method('remove')
                ->canSee(request()->route('cashier')?->exists === true),
            Button::make('Save')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('cashier.name')->title('Name')->required(),
                Input::make('cashier.code')->title('Code')->required(),
                Input::make('cashier.email')->title('Email'),
                Input::make('cashier.shift_label')->title('Shift Label'),
                Input::make('cashier.pin')->title('PIN (4 digit)')->type('password')->help('Isi hanya saat ingin set / reset PIN kasir.'),
                CheckBox::make('cashier.is_active')->title('Active')->sendTrueOrFalse()->value(true),
                TextArea::make('cashier.notes')->title('Notes')->rows(4),
            ]),
        ];
    }

    public function save(CashierAccount $cashier, Request $request)
    {
        $payload = $request->validate([
            'cashier.name' => ['required', 'string', 'max:255'],
            'cashier.code' => ['required', 'string', 'max:255', Rule::unique(CashierAccount::class, 'code')->ignore($cashier)],
            'cashier.email' => ['nullable', 'email', 'max:255'],
            'cashier.shift_label' => ['nullable', 'string', 'max:255'],
            'cashier.pin' => ['nullable', 'digits:4'],
            'cashier.notes' => ['nullable', 'string'],
            'cashier.is_active' => ['nullable', 'boolean'],
        ])['cashier'];

        $data = collect($payload)
            ->except('pin')
            ->toArray();

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        if (! empty($payload['pin'])) {
            $data['pin_hash'] = Hash::make($payload['pin']);
        }

        $cashier->fill($data)->save();

        Toast::info('Cashier account saved.');

        return redirect()->route('platform.operations.cashiers');
    }

    public function remove(CashierAccount $cashier)
    {
        $cashier->delete();
        Toast::info('Cashier account removed.');

        return redirect()->route('platform.operations.cashiers');
    }
}
