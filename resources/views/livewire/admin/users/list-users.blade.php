<div>
    <!-- HEADER -->
    <x-header title="Users" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search Users..." wire:model.live.debounce="search" clearable
                     icon="o-magnifying-glass"/>
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" class="btn-primary"/>
        </x-slot:actions>
    </x-header>


    <!-- TABLE  -->
    <x-card>
        <x-table
            :headers="$this->headers"
            :rows="$this->users"
            :link="route('admin.users.show', ['user' => '[id]'])"
            :sort-by="$sortBy"
            with-pagination
        >
            @scope('cell_permissions', $user)
            @foreach($user->permissions as $permission)
                <x-badge value="{{ \Illuminate\Support\Str::title($permission->name) }}" class="mr-1 badge-soft"/>
            @endforeach
            @endscope

            @scope('actions', $user)
                <livewire:admin.users.delete :id="$user->id" />
            @endscope
        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">

        <x-choices
            label="Permissions"
            wire:model.live.debounce="searchPermissions"
            :options="$this->permissions"/>

        <x-checkbox
            label="Only Trashed Users"
            wire:model.live.debounce="searchTrash" />

        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner/>
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false"/>
        </x-slot:actions>
    </x-drawer>
</div>
