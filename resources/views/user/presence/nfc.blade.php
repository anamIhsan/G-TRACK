@extends('layouts.app')

@section('title', 'USER | QR Code Saya')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12">
            <div class="space-y-5 sm:space-y-6">

                {{-- Card QR Code --}}
                <div x-data x-init="new QRCode($refs.qrcode, {
                    text: '{{ Auth::user()->nfc_id }}',
                    width: 200,
                    height: 200
                })"
                    class="px-6 py-8 overflow-hidden text-center bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5">
                    <h3 class="mb-4 text-xl font-semibold text-gray-800 dark:text-white/90">
                        QR Code Saya
                    </h3>

                    <div class="flex justify-center mb-3">
                        <div x-ref="qrcode"></div>
                    </div>

                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        NFC ID: <span class="font-semibold">{{ Auth::user()->nfc_id }}</span>
                    </p>

                    <p class="mt-2 text-xs text-gray-400">
                        Tunjukkan QR Code ini untuk keperluan presensi.
                    </p>
                </div>

            </div>
        </div>
    </div>
@endsection
