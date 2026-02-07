@extends('layouts.app')

@section('title', 'ADMIN | Tambah Minat')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <form action="{{ route('admin.interests.store') }}" method="POST" class="col-span-12">
            @csrf

            <div class="bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5">
                <div class="px-5 py-4 border-b dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                        Tambah Minat (Multiple)
                    </h3>
                </div>

                <div class="p-5 space-y-6">

                    {{-- NAMA MINAT --}}
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama Minat
                        </label>

                        <select id="nama_minat" name="nama[]" multiple placeholder="Ketik atau pilih minat" class="w-full">
                            @foreach ($existingInterests as $nama)
                                <option value="{{ $nama }}">{{ $nama }}</option>
                            @endforeach
                        </select>

                        <p class="mt-1 text-xs text-gray-500">
                            Bisa ketik manual & pilih lebih dari satu
                        </p>
                    </div>

                    {{-- ZONE --}}
                    @if (Auth::user()->role === 'MASTER')
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-400">
                                Daerah
                            </label>
                            <select name="zone_id" required
                                class="h-11 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option disabled selected>-- Pilih Daerah --</option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                </div>
            </div>

            {{-- ACTION --}}
            <div class="flex gap-3 mt-4">
                <a href="{{ route('admin.interests.index') }}"
                    class="px-4 py-2.5 text-sm rounded-lg bg-gray-100 dark:bg-gray-800">
                    Batal
                </a>

                <button type="submit"
                    class="px-4 py-2.5 text-sm font-medium text-white rounded-lg bg-brand-500 hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        new TomSelect('#nama_minat', {
            plugins: ['remove_button'],
            create: true,
            persist: false,
            maxItems: null,
            placeholder: 'Ketik atau pilih minat'
        });
    </script>
@endpush
