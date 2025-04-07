<div>
    <x-card title="Password Reset">
        @if($message = session()->get('status'))
            <x-alert title="Hey!" :description="$message" icon="o-exclamation-triangle" class="alert-error text-sm"/>
        @endif

        <x-form wire:submit="resetPassword">
            <x-input label="Email" wire:model="email" disabled="true"/>
            <x-password label="Password" wire:model="password" right/>
            <x-password label="Password Confirmation" wire:model="password_confirmation" right/>

            <x-slot:actions>
                <x-button label="Reset" class="btn-primary" type="submit" spinner="save"/>
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
