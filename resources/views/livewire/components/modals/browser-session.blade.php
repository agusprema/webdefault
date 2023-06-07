<form wire:submit.prevent="submit" class="col-span-2 sm:col-span-1 mt-5 md:mt-0">
    <x-filament::card>

        <x-slot name="header">
            <div class="flex items-start justify-between">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ __('filament-breezy::default.profile.browser_session.heading') }}
                </h3>
                <button type="button" wire:click="$emit('closeModal')" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="defaultModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
        </x-slot>

        {{ $this->form }}

        <x-slot name="footer">
            <div class="text-right">
                <x-filament::button type="submit" form="submit">
                    {{ __('filament-breezy::default.profile.browser_session.submit.label') }}
                </x-filament::button>
            </div>
        </x-slot>
    </x-filament::card>
</form>
