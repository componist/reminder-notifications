<div>
    <x-slot name="header">
        <div class="flex items-center gap-1">
            <h2 class="font-semibold leading-tight">
                {{ __('Automatische Benachrichtigung Eintrag erstellen') }}
            </h2>
        </div>
    </x-slot>

    <div class="container px-5 mx-auto py-14">
        <div class="flex justify-end gap-4 my-12">
            <button type="button" wire:click="cancel"
                class="flex items-center justify-center w-56 px-5 py-2 text-gray-500 bg-gray-300 border-0 rounded-md shadow-sm hover:text-white hover:bg-gray-500 default-transition">
                Abbrechen
            </button>

            <button type="button" wire:click="storeAndNew"
                class="flex items-center justify-center w-56 px-5 py-2 text-white border-0 rounded-md shadow-sm whitespace-nowrap bg-dashboard-500 hover:text-white hover:bg-dashboard-600 default-transition ">
                erstellen & Neu
            </button>

            <button type="button" wire:click="storeAndIndex"
                class="flex items-center justify-center w-56 px-5 py-2 text-white border-0 rounded-md shadow-sm whitespace-nowrap bg-dashboard-500 hover:text-white hover:bg-dashboard-600 default-transition ">
                erstellen
            </button>
        </div>

        <div class="w-full md:w-9/12">
            <div class="grid grid-cols-1 gap-5 p-5 my-12 bg-white rounded-md shadow-sm">

                <div>
                    <x:component::form.label value="Titel" />
                    <x:component::form.input wire:model.live="title" type="text" />
                    <x:component::form.input-error :for="$title" />
                </div>
                <div>
                    <x:component::form.label value="Beschreibung" />
                    <x:component::form.input wire:model.live="description" type="text" />
                    <x:component::form.input-error :for="$description" />
                </div>
                <div>
                    <x:component::form.label value="E-Mail" />
                    <x:component::form.input wire:model.live="email" type="email" />
                    <x:component::form.input-error :for="$email" />
                </div>
                <div>
                    <x:component::form.label value="Type" />

                    <x:component::form.select wire:model.live="type">
                        <x:component::form.select-option name="daily" value="Täglich" />
                        <x:component::form.select-option name="monthly" value="Monatlich" />
                        <x:component::form.select-option name="yearly" value="Jährlich" />
                    </x:component::form.select>

                    <x:component::form.input-error :for="$type" />
                </div>

                @if ($type == 'daily')
                    <div>
                        <x:component::form.label value="Time" />
                        <x:component::form.input wire:model.live="time" type="time" />
                        <x:component::form.input-error :for="$time" />
                    </div>
                @endif


                @if ($type == 'monthly' or $type == 'yearly')
                    <div>
                        <x:component::form.label value="Tag" />
                        <x:component::form.select wire:model.live="daily">
                            @for ($i = 1; $i <= 30; $i++)
                                <x:component::form.select-option name="{{ $i }}"
                                    value="{{ $i }}" />
                            @endfor
                        </x:component::form.select>
                        <x:component::form.input-error :for="$time" />
                    </div>
                @endif

                @if ($type == 'yearly')
                    <div>
                        <x:component::form.label value="Monat" />
                        <x:component::form.select wire:model.live="monthly">
                            @for ($i = 1; $i <= 12; $i++)
                                <x:component::form.select-option name="{{ $i }}"
                                    value="{{ $i }}" />
                            @endfor
                        </x:component::form.select>
                        <x:component::form.input-error :for="$time" />
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
