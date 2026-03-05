<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-end">
            {{ $this->getCachedFormActions()[0] }}
        </div>
    </form>
</x-filament-panels::page>
