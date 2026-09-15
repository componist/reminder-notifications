<x:component::page.shell title="Automatische Benachrichtigungen">
    <x-slot:actions>
        <x:component::element.search wire:model.live.debounce.400ms="search" placeholder="Suche" />
        <x:component::button.primary href="{{ route('package.reminder-notification.create') }}">
            Neu anlegen
        </x:component::button.primary>
    </x-slot:actions>

    <x:component::table.wrapper>
        <x-slot:head>
            <x:component::table.row>
                <x:component::table.cell class="text-left font-semibold text-slate-700 dark:text-slate-200">
                    Titel
                </x:component::table.cell>
                <x:component::table.cell class="text-left font-semibold text-slate-700 dark:text-slate-200">
                    Typ
                </x:component::table.cell>
                <x:component::table.cell class="text-left font-semibold text-slate-700 dark:text-slate-200">
                    Zeit
                </x:component::table.cell>
                <x:component::table.cell class="text-left font-semibold text-slate-700 dark:text-slate-200">
                    E-Mail
                </x:component::table.cell>
                <x:component::table.cell class="text-left font-semibold text-slate-700 dark:text-slate-200">
                    Status
                </x:component::table.cell>
                <x:component::table.cell></x:component::table.cell>
            </x:component::table.row>
        </x-slot:head>

        <x-slot:body>
            @forelse ($content as $value)
                <x:component::table.row class="hover:bg-slate-50 dark:hover:bg-slate-800">
                    <x:component::table.cell class="text-left text-slate-500 dark:text-slate-300">
                        {{ $value->title }}
                    </x:component::table.cell>
                    <x:component::table.cell class="text-left text-slate-500 dark:text-slate-300">
                        @if ($value->type === 'daily')
                            <span class="rounded-full bg-teal-500 px-5 py-1 text-xs text-white shadow-sm">Täglich</span>
                        @elseif ($value->type === 'monthly')
                            <span class="rounded-full bg-slate-500 px-5 py-1 text-xs text-white shadow-sm">Monatlich</span>
                        @elseif ($value->type === 'yearly')
                            <span class="rounded-full bg-slate-600 px-5 py-1 text-xs text-white shadow-sm">Jährlich</span>
                        @endif
                    </x:component::table.cell>
                    <x:component::table.cell class="text-left text-slate-500 dark:text-slate-300">
                        {{ $value->time }}
                    </x:component::table.cell>
                    <x:component::table.cell class="text-left text-slate-500 dark:text-slate-300">
                        {{ $value->email }}
                    </x:component::table.cell>
                    <x:component::table.cell class="text-left text-slate-500 dark:text-slate-300">
                        <x:component::form.toggle wire:change="toggle({{ $value->id }},'status')"
                            status="{{ $value->status }}" />
                    </x:component::table.cell>
                    <x:component::table.cell>
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('package.reminder-notification.edit', $value->id) }}">
                                <x:component::button.edit />
                            </a>
                            <x:component::element.confirm-delete wire:click="delete({{ $value->id }})" />
                        </div>
                    </x:component::table.cell>
                </x:component::table.row>
            @empty
                <x:component::table.row>
                    <x:component::table.cell colspan="6" class="py-8 text-center text-slate-500 dark:text-slate-400">
                        Keine Einträge vorhanden.
                    </x:component::table.cell>
                </x:component::table.row>
            @endforelse
        </x-slot:body>
    </x:component::table.wrapper>

    @if ($content->hasPages())
        <div class="mt-6">
            {{ $content->links('livewire::tailwind') }}
        </div>
    @endif
</x:component::page.shell>
