<x:component::page.form title="Erinnerung anlegen">
    <x-slot:actions>
        <x:component::button.secondary wire:click="cancel">Abbrechen</x:component::button.secondary>
        <x:component::button.secondary wire:click="storeAndNew">Erstellen & Neu</x:component::button.secondary>
        <x:component::button.primary wire:click="storeAndIndex">Erstellen</x:component::button.primary>
    </x-slot:actions>

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
        <x:component::form.label value="Typ" />
        <x:component::form.select wire:model.live="type">
            <x:component::form.select-option name="daily" value="Täglich" />
            <x:component::form.select-option name="monthly" value="Monatlich" />
            <x:component::form.select-option name="yearly" value="Jährlich" />
        </x:component::form.select>
        <x:component::form.input-error :for="$type" />
    </div>

    @if ($type === 'daily')
        <div>
            <x:component::form.label value="Uhrzeit" />
            <x:component::form.input wire:model.live="time" type="time" />
            <x:component::form.input-error :for="$time" />
        </div>
    @endif

    @if ($type === 'monthly' || $type === 'yearly')
        <div>
            <x:component::form.label value="Tag" />
            <x:component::form.select wire:model.live="daily">
                @for ($i = 1; $i <= 30; $i++)
                    <x:component::form.select-option name="{{ $i }}" value="{{ $i }}" />
                @endfor
            </x:component::form.select>
            <x:component::form.input-error :for="$daily" />
        </div>
    @endif

    @if ($type === 'yearly')
        <div>
            <x:component::form.label value="Monat" />
            <x:component::form.select wire:model.live="monthly">
                @for ($i = 1; $i <= 12; $i++)
                    <x:component::form.select-option name="{{ $i }}" value="{{ $i }}" />
                @endfor
            </x:component::form.select>
            <x:component::form.input-error :for="$monthly" />
        </div>
    @endif
</x:component::page.form>
