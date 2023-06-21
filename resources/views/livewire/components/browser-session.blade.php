@php
    \Filament\Facades\Filament::registerRenderHook('body.end', fn(): string => \Illuminate\Support\Facades\Blade::render('@livewire(\'livewire-ui-modal\')'));
    
    \Filament\Facades\Filament::registerScripts(['https://unpkg.com/@alpinejs/focus@3.x.x/dist/cdn.min.js']);
@endphp

<x-filament-breezy::grid-section class="mt-8">

    <x-slot name="title">
        {{ __('filament-breezy::default.profile.browser_session.heading') }}
    </x-slot>

    <x-slot name="description">
        {{ __('filament-breezy::default.profile.browser_session.subheading') }}
    </x-slot>

    <div class="col-span-2 sm:col-span-1 mt-5 md:mt-0">
        <x-filament::card>

            {{-- {{ $this->updateProfileForm }} --}}
            <div class="flex items-center content-center justify-center">
                <x-filament-support::loading-indicator wire:loading.class.remove="hidden" class="filament-link-icon w-10 h-10 mr-1 rtl:ml-1 hidden" />
            </div>
            @if (count($this->sessions) > 0)
                <div class="mt-5 space-y-6" wire:loading.remove>
                    <!-- Other Browser Sessions -->
                    @foreach ($this->sessions as $session)
                        <div class="flex items-center">
                            <div>
                                @if ($session->agent->isDesktop())
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                    </svg>
                                @endif
                            </div>

                            <div class="ml-3">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $session->agent->platform() ? $session->agent->platform() : __('Unknown') }} - {{ $session->agent->browser() ? $session->agent->browser() : __('Unknown') }}
                                </div>

                                <div>
                                    <div class="text-xs text-gray-500">
                                        {{ $session->ip_address }},

                                        @if ($session->is_current_device)
                                            <span class="text-green-500 font-semibold">{{ __('This device') }}</span>
                                        @else
                                            {{ __('Last active') }} {{ $session->last_active }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="text-right">
                <x-filament::button wire:click="$emit('openModal', 'components.modals.browser-session')">
                    {{ __('filament-breezy::default.profile.browser_session.submit.label') }}
                </x-filament::button>
            </div>
        </x-filament::card>
    </div>

</x-filament-breezy::grid-section>
