<div>
    <x-card title="Login">
        <x-slot name="subtitle">
            Need account? <a wire:navigate href="{{ route('register') }}" class="link link-primary">Register</a>
        </x-slot>

        @error('invalidCredentials')
        <x-alert title="Hey!" :description="$message" icon="o-exclamation-triangle" class="alert-warning text-sm"/>
        @enderror

        @error('throttle')
        <x-alert title="Hey!" :description="$message" icon="o-exclamation-triangle" class="alert-warning text-sm"/>
        @enderror

        <x-form wire:submit="login">
            <x-input label="Email" wire:model="email"/>
            <x-password label="Password" wire:model="password" right/>

            <x-slot:actions>
                <x-button label="Login" class="btn-primary" type="submit" spinner="save"/>
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
