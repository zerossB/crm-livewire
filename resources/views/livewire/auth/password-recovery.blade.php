<div>
    <x-card title="Password Recovery">
        <x-slot name="subtitle">
            Remembered your password? <a wire:navigate href="{{ route('login') }}" class="link link-primary">Login</a>
        </x-slot>

        @if($successMessage)
            <x-alert class="alert-success">
                {{ $successMessage }}
            </x-alert>
        @endif

        @if($errors->hasAny(['throttle', 'invalidCredentials']))
            @error('throttle')
            <x-alert title="Hey!" :description="$message" icon="o-exclamation-triangle" class="alert-warning text-sm"/>
            @enderror

            @error('invalidCredentials')
            <x-alert title="Hey!" :description="$message" icon="o-exclamation-triangle" class="alert-warning text-sm"/>
            @enderror
        @endif

        <x-form wire:submit="recoveryPassword">
            <x-input label="Email" wire:model="email"/>

            <x-slot:actions>
                <x-button label="Recovery" class="btn-primary" type="submit" spinner="save"/>
            </x-slot:actions>
        </x-form>
    </x-card>

</div>
