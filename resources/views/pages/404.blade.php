<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta
            name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
        />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>404 NOT FOUND | System Absensi 2025</title>

        {{-- Laravel + Tailwind + Alpine --}}
        @vite(["resources/css/app.css", "resources/js/app.js"])
    </head>
    <body
        x-data="{
            page: 'page404',
            'loaded': true,
            'darkMode': false,
            'stickyMenu': false,
            'sidebarToggle': false,
            'scrollTop': false,
        }"
        x-init="
            darkMode = JSON.parse(localStorage.getItem('darkMode'))
            $watch('darkMode', (value) =>
                localStorage.setItem('darkMode', JSON.stringify(value)),
            )
        "
        :class="{'dark bg-gray-900': darkMode === true}"
    >
        <!-- ===== Preloader Start ===== -->
        @include("components.preloader")
        <!-- ===== Preloader End ===== -->

        <!-- ===== Page Wrapper Start ===== -->
        <div class="relative flex flex-col items-center justify-center min-h-screen p-6 overflow-hidden z-1">
            <!-- ===== Common Grid Shape Start ===== -->
            @include("components.common-grid-shape")
            <!-- ===== Common Grid Shape End ===== -->

            <!-- Centered Content -->
            <div class="mx-auto w-full max-w-[242px] text-center sm:max-w-[472px]">
                <h1 class="mb-8 font-bold text-gray-800 text-title-md dark:text-white/90 xl:text-title-2xl">ERROR</h1>

                <img src="/images/error/404.svg" alt="404" class="w-full dark:hidden" />
                <img src="/images/error/404-dark.svg" alt="404" class="hidden w-full dark:block" />

                <p class="mt-10 mb-6 text-base text-gray-700 dark:text-gray-400 sm:text-lg">
                    {{ isset($message) ? $message : "We can’t seem to find the page you are looking for!" }}
                </p>

                <a
                    href="{{ route("dashboard") }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200"
                >
                    Back to Dashboard
                </a>
            </div>
            <!-- Footer -->
            <p class="absolute text-sm text-center text-gray-500 -translate-x-1/2 bottom-6 left-1/2 dark:text-gray-400">
                &copy;
                <span id="year"></span>
                - System Absensi
            </p>
        </div>

        <!-- ===== Page Wrapper End ===== -->
    </body>
</html>
