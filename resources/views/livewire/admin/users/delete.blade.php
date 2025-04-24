<div>
    <x-button spinner @click="$wire.modal = true" icon="o-trash" class="btn-error btn-sm btn-soft"/>


    <x-modal
        wire:model="modal" title="Deletion Confirmation" class="backdrop-blur"
    >

        <div class="text-center">
            <x-icon name="o-exclamation-triangle" class="text-error text-6xl"/>
            <h3 class="mt-4 text-lg font-semibold">
                Are you sure you want to delete {{ $user->name }}?
            </h3>
            <p class="mt-2 text-sm">
                This action cannot be undone.
            </p>
        </div>

        @error('confirmation')
        <x-alert icon="o-exclamation-triangle" class="alert-error my-4">
            {{ $message }}
        </x-alert>
        @enderror

        <x-input
            label="Write 'DART VADER' to confirm the deletion of the user: {{ $user->name }}"
            wire:model="confirmation_confirmation" placeholder="DART VADER" class="input-sm"
        />

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal = false"/>
            <x-button label="Confirm" class="btn-primary" spinner wire:click="destroy"/>
        </x-slot:actions>
    </x-modal>
</div>
