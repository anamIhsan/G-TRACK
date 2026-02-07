@extends('layouts.app')

@section('title', 'ADMIN | Scanner Presensi')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12">
            <div class="space-y-6">
                <div class="p-6 bg-white border border-gray-200 shadow-xl rounded-2xl dark:border-gray-800 dark:bg-white/5">
                    <a href="{{ route('admin.presences.index', ['activity_id' => $activity->id]) }}"
                        class="p-2 dark:text-white text-gray-700 text-sm font-semibold rounded-xl shadow-lg bg-gray-600 hover:bg-gray-700 focus:ring-gray-300 transition duration-150 transform hover:scale-[1.01] focus:outline-none focus:ring-4">
                        Back
                    </a>
                    <h3 class="mb-6 text-2xl font-bold text-center text-gray-800 dark:text-white">
                        <i data-lucide="qr-code" class="inline w-6 h-6 mr-2 text-indigo-600"></i>
                        Scanner Presensi Cepat
                    </h3>

                    {{-- Live Time/Date Display --}}
                    <div class="mb-6 text-center">
                        <p id="current-date" class="text-sm font-medium text-gray-500 dark:text-gray-400"></p>
                        <p id="current-time" class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400"></p>
                    </div>

                    {{-- Camera Container --}}
                    <div id="camera-container"
                        class="relative w-full max-w-sm mx-auto overflow-hidden transition-all duration-500 border-4 border-indigo-400 shadow-2xl aspect-square rounded-3xl">
                        <div id="camera-loading"
                            class="absolute inset-0 flex items-center justify-center p-4 text-center bg-gray-100 dark:bg-gray-700">
                            <div class="text-indigo-600 dark:text-indigo-400">
                                <i data-lucide="loader-circle" class="w-8 h-8 mx-auto mb-2 animate-spin"></i>
                                <p class="font-semibold">Memuat kamera...</p>
                                <p class="mt-2 text-xs text-gray-500">Pastikan izin kamera sudah diberikan.</p>
                            </div>
                        </div>

                        {{-- The actual video feed element --}}
                        <div id="qr-reader" class="object-cover w-full h-full"></div>

                        {{-- Scanner Marker (The overlay box) --}}
                        <div id="scanner-marker"
                            class="absolute w-3/4 transition-all duration-300 -translate-x-1/2 -translate-y-1/2 border-4 border-dashed rounded-lg pointer-events-none top-1/2 left-1/2 h-3/4 border-white/80">
                            <div
                                class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-indigo-500 rounded-tl-lg">
                            </div>
                            <div
                                class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-indigo-500 rounded-tr-lg">
                            </div>
                            <div
                                class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-indigo-500 rounded-bl-lg">
                            </div>
                            <div
                                class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-indigo-500 rounded-br-lg">
                            </div>
                        </div>
                    </div>

                    {{-- Status Message Display --}}
                    <div id="status-message"
                        class="mt-8 min-h-[5rem] flex items-center justify-center p-4 rounded-xl transition duration-300 shadow-inner bg-gray-50 border border-gray-200">
                        <p class="font-medium text-gray-600">Arahkan kamera ke kode QR absensi.</p>
                    </div>

                    {{-- Simulation Button (for development/testing) --}}
                    {{--
                        <div class="mt-6 text-center">
                        <button
                        id="simulate-scan-btn"
                        class="px-8 py-3 bg-green-500 text-white font-semibold rounded-xl shadow-lg hover:bg-green-600 transition duration-150 transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-green-300"
                        >
                        Simulasi Scan (Testing)
                        </button>
                        </div>
                    --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        // --- INISIALISASI ELEMEN ---
        const statusMessage = document.getElementById('status-message');
        const cameraFrame = document.getElementById('camera-container');
        const timeDisplay = document.getElementById('current-time');
        const dateDisplay = document.getElementById('current-date');
        const simulateButton = document.getElementById('simulate-scan-btn');

        let html5QrCode = null;

        // --- CLOCK FUNCTION ---
        function updateClock() {
            const now = new Date();
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };

            timeDisplay.textContent = now.toLocaleTimeString('id-ID', timeOptions);
            dateDisplay.textContent = now.toLocaleDateString('id-ID', dateOptions);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // --- STATUS FUNCTION ---
        function showStatus(message, type = 'default') {
            if (!statusMessage || !cameraFrame) return;
            statusMessage.innerHTML = `<p>${message}</p>`;
            statusMessage.className =
                'mt-8 min-h-[5rem] flex items-center justify-center p-4 rounded-xl transition duration-300 shadow-inner';
            cameraFrame.classList.remove('border-green-500', 'border-red-500', 'border-yellow-500');
            statusMessage.classList.remove(
                'bg-green-100',
                'border-green-400',
                'bg-red-100',
                'border-red-400',
                'bg-yellow-100',
                'border-yellow-400',
                'bg-gray-50',
                'border-gray-200',
            );

            const pElement = statusMessage.querySelector('p');
            if (pElement) pElement.className = 'font-medium';

            switch (type) {
                case 'success':
                    statusMessage.classList.add('bg-green-100', 'border-green-400');
                    pElement.classList.add('text-green-800', 'font-bold');
                    cameraFrame.classList.add('border-green-500');
                    break;
                case 'error':
                    statusMessage.classList.add('bg-red-100', 'border-red-400');
                    pElement.classList.add('text-red-800', 'font-bold');
                    cameraFrame.classList.add('border-red-500');
                    break;
                case 'scanning':
                    statusMessage.classList.add('bg-yellow-100', 'border-yellow-400');
                    pElement.classList.add('text-yellow-800', 'font-bold');
                    cameraFrame.classList.add('border-yellow-500');
                    break;
                default:
                    statusMessage.classList.add('bg-gray-50', 'border-gray-200');
                    pElement.classList.add('text-gray-600', 'font-medium');
                    cameraFrame.classList.add('border-indigo-400');
                    break;
            }
        }

        // --- CAMERA & QR SCANNER ---
        function startScanner() {
            const qrReader = document.getElementById('qr-reader');
            if (!qrReader) return;

            showStatus('Kamera aktif...', 'scanning');

            html5QrCode = new Html5Qrcode('qr-reader');

            const config = {
                fps: 10, // frame per second
                qrbox: {
                    width: 250,
                    height: 250
                },
                aspectRatio: 1.0,
            };

            let lastCode = null;

            // showStatus('Arahkan kamera ke kode QR absensi.', 'scanning');

            html5QrCode
                .start({
                        facingMode: 'environment'
                    },
                    config,
                    (qrCodeMessage) => {
                        lastCode = qrCodeMessage;

                        fetch('/api/presence/scan', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                },
                                body: JSON.stringify({
                                    nfc_id: qrCodeMessage,
                                    activity_id: '{{ $activity->id }}',
                                }),
                            })
                            .then((res) => res.json())
                            .then((data) => {
                                if (data.status == 'success') {
                                    showStatus(
                                        `Presensi Berhasil pada pukul ${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}`,
                                        'success',
                                    );
                                } else {
                                    showStatus('Presensi Gagal! Kode tidak valid atau sudah terlambat.', 'scanning');
                                }
                                console.log(data);
                            })
                            .catch((err) => {
                                showStatus('Presensi Gagal! Kode tidak valid atau sudah terlambat.', 'scanning');
                                console.error(err);
                            });
                    },
                    (errorMessage) => {
                        showStatus('Arahkan kamera ke kode QR absensi.', 'scanning');
                    },
                )
                .catch((err) => {
                    console.error('Gagal mengakses kamera:', err);
                    showStatus('Gagal mengakses kamera. Periksa izin browser.', 'error');
                });
        }

        // --- STOP CAMERA ---
        function stopCamera() {
            if (html5QrCode) {
                html5QrCode.stop().catch(() => {});
                html5QrCode.clear();
            }
        }

        // --- SIMULASI TOMBOL ---
        if (simulateButton) {
            simulateButton.addEventListener('click', () => {
                simulateButton.disabled = true;
                showStatus('Memproses data presensi Anda...', 'scanning');

                setTimeout(() => {
                    const isSuccess = Math.random() > 0.3;
                    if (isSuccess) {
                        showStatus(
                            `Presensi Berhasil pada pukul ${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}`,
                            'success',
                        );
                    } else {
                        showStatus('Presensi Gagal! Kode tidak valid atau sudah terlambat.', 'error');
                    }
                    setTimeout(() => {
                        showStatus('Arahkan kamera ke kode QR absensi.');
                        simulateButton.disabled = false;
                    }, 5000);
                }, 1500);
            });
        }

        // --- INIT ---
        window.addEventListener('load', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            startScanner();
        });

        window.addEventListener('beforeunload', stopCamera);
    </script>
@endpush
