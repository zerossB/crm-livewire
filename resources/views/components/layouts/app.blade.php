<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-base-200">

{{-- NAVBAR mobile only --}}
<x-nav sticky class="lg:hidden">
    <x-slot:brand>
        <x-app-brand/>
    </x-slot:brand>
    <x-slot:actions>
        <label for="main-drawer" class="lg:hidden me-3">
            <x-icon name="o-bars-3" class="cursor-pointer"/>
        </label>
    </x-slot:actions>
</x-nav>

{{-- MAIN --}}
<x-main>
    {{-- SIDEBAR --}}
    <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

        {{-- BRAND --}}
        <x-app-brand class="px-5 pt-4"/>

        {{-- MENU --}}
        <x-menu activate-by-route>
            {{-- User --}}
            @if($user = auth()->user())
                <x-menu-separator/>

                <x-list-item :item="$user" value="name" sub-value="email"
                             no-separator no-hover class="-mx-2 !-my-2 rounded">
                    <x-slot:actions>
                        <livewire:auth.logout/>
                    </x-slot:actions>
                </x-list-item>

                <x-menu-separator/>
            @endif

            @can(\App\Enums\Can::BE_AN_ADMIN)
                <x-menu-sub title="Admin" icon="o-lock-closed">
                    <x-menu-item title="Dashboard" icon="o-chart-bar-square" :link="route('admin.dashboard')"
                                 route="admin.dashboard"/>
                    <x-menu-item title="Users" icon="o-users" :link="route('admin.users.list')"
                                 route="admin.users.list"/>
                </x-menu-sub>
            @endcan
        </x-menu>
    </x-slot:sidebar>

    {{-- The `$slot` goes here --}}
    <x-slot:content>
        {{ $slot }}
    </x-slot:content>
</x-main>

{{--  TOAST area --}}
<x-toast/>
</body>
</html>
