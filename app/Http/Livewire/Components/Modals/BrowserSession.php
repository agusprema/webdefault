<?php

namespace App\Http\Livewire\Components\Modals;

use LivewireUI\Modal\ModalComponent;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Forms;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BrowserSession extends ModalComponent implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    use WithRateLimiting;

    public $password;

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('password')->label('Confirm Password')
                ->type('password')->required()
        ];
    }

    public function submit(Request $request)
    {
        $data = $this->form->getState();

        /** @phpstan-ignore-next-line */ // @fixme
        if (!Hash::check($data['password'], $request->user()->password)) {
            throw ValidationException::withMessages([
                'password' => __('The provided password does not match your current password.'),
            ]);
        }

        Auth::logoutOtherDevices($data['password']);

        $this->deleteOtherSessionRecords();

        request()->session()->put([
            'password_hash_' . Auth::getDefaultDriver() => Auth::user()->getAuthPassword(),
        ]);

        $this->closeModalWithEvents([
            \App\Http\Livewire\Components\BrowserSession::getName() => 'userBrowserSessionUpdate',
        ]);
    }

    /**
     * Delete the other browser session records from storage.
     *
     * @return void
     */
    protected function deleteOtherSessionRecords()
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', Auth::user()->getAuthIdentifier())
            ->where('id', '!=', request()->session()->getId())
            ->delete();
    }

    public function render()
    {
        return view('livewire.components.modals.browser-session');
    }
}
