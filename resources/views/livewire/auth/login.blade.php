<div>
    <x-card title="Login">
        <x-slot name="subtitle">
            Need account? <a wire:navigate href="{{ route('register') }}" class="link link-primary">Register</a>
        </x-slot>

        @if($errors->hasAny(['throttle', 'invalidCredentials']))
            @error('throttle')
            <x-alert title="Hey!" :description="$message" icon="o-exclamation-triangle" class="alert-warning text-sm"/>
            @enderror

            @error('invalidCredentials')
            <x-alert title="Hey!" :description="$message" icon="o-exclamation-triangle" class="alert-warning text-sm"/>
            @enderror
        @endif

        <x-form wire:submit="login">
            <x-input label="Email" wire:model="email"/>
            <x-password label="Password" wire:model="password" right>
                <x-slot name="hint">
                    <a wire:navigate href="{{ route('password.recovery') }}" class="link link-primary">
                        Forgot Password?
                    </a>
                </x-slot>
            </x-password>

            <x-checkbox label="Remember Me" wire:model="remember"/>

            <x-slot name="footer">
                <a wire:navigate href="{{ route('password.recovery') }}" class="link link-primary">Forgot
                    Password?</a>
            </x-slot>

            <x-slot:actions>
                <x-button label="Login" class="btn-primary" type="submit" spinner="save"/>
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
