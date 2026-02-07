<!DOCTYPE html>
<html lang="en" x-data="{
    page: '',
    loaded: true,
    darkMode: JSON.parse(localStorage.getItem('darkMode')),
    stickyMenu: false,
    sidebarToggle: false,
    scrollTop: false,
}" x-init="$watch('darkMode', (value) =>
    localStorage.setItem('darkMode', JSON.stringify(value)),
)" :class="{ 'dark': darkMode === true }">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Not Found')</title>

    @stack('styles')

    {{-- Icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- cdn qr code --}}
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

    {{-- Laravel + Tailwind + Alpine --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .custom-search-dropdown::-webkit-scrollbar {
            width: 6px; /* Lebar Scrollbar */
            height: 6px;
        }

        .custom-search-dropdown::-webkit-scrollbar-track {
            background: transparent; /* Membuat track transparan */
        }

        .custom-search-dropdown::-webkit-scrollbar-thumb {
            background-color: rgba(107, 114, 128, 0.5);
            border-radius: 10px;
        }

        .custom-search-dropdown::-webkit-scrollbar-thumb:hover {
            background-color: rgba(107, 114, 128, 0.7);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body x-cloak class="dark:bg-gray-900">
    {{-- @include('components.preloader') --}}

    <div class="flex h-screen overflow-hidden">
        @include('components.sidebar')

        <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
            @include('components.overlay')

            @include('components.header')

            <main>
                <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                    @if ($errors->any())
                        <div class="p-4 mb-4 text-red-800 border-l-4 border-red-500 rounded-lg shadow bg-red-50">
                            <div class="mb-2 font-semibold text-red-700">
                                Terjadi kesalahan:
                            </div>
                            <ul class="space-y-1 text-sm list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>

        <script>
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js');
            }

            document.addEventListener("DOMContentLoaded", async () => {
                if ('serviceWorker' in navigator) {
                    const registration = await navigator.serviceWorker.register('/sw.js');

                    const subscription = await registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: "{{ config('webpush.vapid.public_key') }}"
                    });

                    await fetch('/api/save-subscriptions', {
                        method: 'POST',
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify(subscription)
                    });
                }
            });
        </script>



        @stack('scripts')
</body>

</html>
