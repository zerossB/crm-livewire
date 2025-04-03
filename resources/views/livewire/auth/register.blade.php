<div>
    <x-card title="Register">
        <x-slot name="subtitle">
            Alweady have an account? <a wire:navigate href="{{ route('login') }}" class="link link-primary">Login</a>
        </x-slot>

        <x-form wire:submit="register">
            <x-input label="Name" wire:model="name"/>
            <x-input label="Email" wire:model="email"/>
            <x-input label="Confirm your email" wire:model="email_confirmation"/>
            <x-password label="Password" wire:model="password" right/>

            <x-slot:actions>
                <x-button label="Reset" type="reset"/>
                <x-button label="Register" class="btn-primary" type="submit" spinner="save"/>
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
