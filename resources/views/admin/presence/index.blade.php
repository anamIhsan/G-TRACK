@extends('layouts.app')

@section('title', 'ADMIN | Presensi')

@section('content')
    <div x-data="editStatusModal" class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12">
            <div class="space-y-5 sm:space-y-6">
                <div x-data="{
                    // Filter logic is left as a placeholder since it's not fully defined
                    showFilterCollapse: false,
                    filterActivity: '{{ request('activity_id', '') }}', // Use request helper to get initial filter value
                    applyFilter() {
                        console.log(
                            'Filter applied. Selected Activity ID:',
                            this.filterActivity,
                        )
                        this.showFilterCollapse = false
                        // In a real Laravel app, you would submit a form or redirect here:
                        // window.location.href = `{{ route('admin.presences.index') }}?activity_id=${this.filterActivity}`;
                    },
                }"
                    class="px-4 pt-4 pb-3 overflow-hidden bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5 sm:px-6">
                    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                Daftar Presensi
                                <div x-show="filterActivity" class="text-sm font-normal text-indigo-500">
                                    <span>{{ $activity->nama ?? 'Pilih kegiatan terlebih dahulu' }}</span>
                                    @if (isset($activity) && $activity->type === 'TERJADWAL')
                                        <a href="{{ $activity ? route('admin.presences.history', ['activity_id' => $activity->id]) : '#' }}"
                                            class="py-1 px-2 mt-2 bg-indigo-600 text-white rounded-xl font-normal shadow-lg hover:bg-indigo-700 transition duration-150 transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-indigo-300">
                                            <i class="fa-solid fa-clock-rotate-left"></i>
                                            History
                                        </a>
                                    @endif
                                </div>
                                @isset($activity)
                                    <div class="mt-2" x-data="{ showDetail: false }">
                                        <a @click="showDetail = !showDetail"
                                            class="flex flex-row items-center text-sm font-light text-blue-500 cursor-pointer">
                                            <p>Lihat detail nya</p>
                                            <i class="fa fa-angle-down"></i>
                                        </a>
                                        <div x-show="showDetail" x-transition class="mt-2">
                                            <h5 class="text-xs font-light text-gray-500 dark:text-white/90">
                                                Daerah : {{ $activity->zone->nama ?? 'Daerah tidak diatur' }}
                                            </h5>
                                            <h5 class="text-xs font-light text-gray-500 dark:text-white/90">
                                                Desa : {{ $activity->village->nama ?? 'Desa tidak diatur' }}
                                            </h5>
                                            <h5 class="text-xs font-light text-gray-500 dark:text-white/90">
                                                Kelompok : {{ $activity->group->nama ?? 'Desa tidak diatur' }}
                                            </h5>
                                        </div>
                                    </div>
                                @endisset
                            </h3>
                        </div>

                        <div class="flex items-center gap-3">
                            {{-- Button pilih kegiatan --}}
                            <button id="open-activity-modal-btn"
                                class="p-2 bg-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:bg-indigo-700 transition duration-150">
                                <i data-lucide="list-checks" class="inline w-5 h-5 mr-1"></i>
                                Pilih Kegiatan
                            </button>

                            {{-- Scan --}}
                            <a href="{{ $activity ? route('admin.presences.scanner', ['activity_id' => $activity->id]) : '#' }}"
                                class="p-2 text-white font-semibold rounded-xl shadow-lg
        {{ $activity ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-500 cursor-not-allowed' }}">
                                <i data-lucide="scan-line" class="inline w-5 h-5 mr-1"></i>
                                Scan Absensi
                            </a>

                            {{-- Export --}}
                            <a href="{{ $activity ? route('admin.export.presences', ['activity_id' => $activity->id]) : '#' }}"
                                class="p-2 text-white font-semibold rounded-xl shadow-lg
        {{ $activity ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-gray-500 cursor-not-allowed' }}">
                                <i data-lucide="file-down" class="inline w-5 h-5 mr-1"></i>
                                Export
                            </a>
                        </div>

                    </div>

                    <div class="w-full overflow-x-auto" x-data="{ updatePresence: false }">
                        @isset($activity)
                            <button type="button" @click="updatePresence = !updatePresence"
                                class="px-2 py-1 mb-2 text-sm font-semibold text-white transition duration-150 bg-yellow-500 shadow-lg rounded-xl hover:bg-yellow-600 focus:outline-none">
                                <span x-show="!updatePresence">Update Presensi</span>
                                <span x-show="updatePresence">Batalkan</span>
                            </button>
                            <button x-show="updatePresence" type="button"
                                @click="document.getElementById('update-presence-form').submit()"
                                class="px-2 py-1 mb-2 text-sm font-semibold text-white transition duration-150 bg-blue-500 shadow-lg rounded-xl hover:bg-blue-600 focus:outline-none">
                                <span>Simpan</span>
                            </button>
                        @endisset

                        <form id="update-presence-form"
                            action="{{ isset($activity) ? route('admin.presences.update', $activity->id) : '' }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <table class="min-w-full whitespace-nowrap">
                                <thead>
                                    <tr class="border-gray-100 border-y dark:border-gray-800">
                                        <th
                                            class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                            No.
                                        </th>
                                        <th
                                            class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                            Nama
                                        </th>
                                        <th
                                            class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                            Jam Datang
                                        </th>
                                        <th
                                            class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                            Status
                                        </th>
                                        <th
                                            class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400">
                                            Keterangan
                                        </th>
                                        {{--
                                            <th
                                            class="px-4 py-3 font-medium text-left text-gray-500 text-theme-xs dark:text-gray-400"
                                            >
                                            Aksi
                                            </th>
                                        --}}
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @forelse ($users as $item)
                                        @php
                                            if ($activity) {
                                                if ($activity->type == 'SEKALI') {
                                                    $presence = $item->presences
                                                        ->where('activity_id', $activity->id)
                                                        ->sortByDesc('created_at')
                                                        ->first();
                                                } else {
                                                    $presence = $item->presences
                                                        ->where('activity_id', $activity->id)
                                                        ->where('created_at', '>=', today()->startOfDay())
                                                        ->first();
                                                }
                                            } else {
                                                $presence = null;
                                            }
                                        @endphp

                                        <tr data-user-id="{{ $item->id }}">
                                            <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                                {{ $item->nama }}
                                            </td>
                                            <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                                <p x-show="!updatePresence">
                                                    {{ $presence?->jam_datang ?? '-' }}
                                                </p>
                                                <div class="relative" x-show="updatePresence">
                                                    <input value="{{ $presence?->jam_datang ?? '' }}"
                                                        name="jam_datang[{{ $item->id }}]" type="time"
                                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                        onclick="this.showPicker()" />
                                                    <span
                                                        class="absolute text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-3 dark:text-gray-400">
                                                        <svg class="fill-current" width="20" height="20"
                                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M10 1.66667C5.39763 1.66667 1.66667 5.39763 1.66667 10C1.66667 14.6024 5.39763 18.3333 10 18.3333C14.6024 18.3333 18.3333 14.6024 18.3333 10C18.3333 5.39763 14.6024 1.66667 10 1.66667ZM10 3.16667C13.774 3.16667 16.8333 6.226 16.8333 10C16.8333 13.774 13.774 16.8333 10 16.8333C6.226 16.8333 3.16667 13.774 3.16667 10C3.16667 6.226 6.226 3.16667 10 3.16667ZM9.25 5.83333C9.25 5.41912 9.58579 5.08333 10 5.08333C10.4142 5.08333 10.75 5.41912 10.75 5.83333V9.68934L13.0303 11.9697C13.3232 12.2626 13.3232 12.7374 13.0303 13.0303C12.7374 13.3232 12.2626 13.3232 11.9697 13.0303L9.46967 10.5303C9.32902 10.3897 9.25 10.1989 9.25 10V5.83333Z" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                                @php
                                                    $status = $presence?->status ?? 'ALPHA';

                                                    $colors = [
                                                        'HADIR' => 'bg-green-400 text-green-800',
                                                        'TERLAMBAT' => 'bg-green-200 text-green-800',
                                                        'IZIN' => 'bg-orange-200 text-orange-800',
                                                        'SAKIT' => 'bg-yellow-200 text-yellow-800',
                                                        'ALPHA' => 'bg-red-200 text-red-800',
                                                    ];

                                                    $bgColor = $colors[$status] ?? 'bg-gray-200 text-gray-800';
                                                @endphp

                                                <span x-show="!updatePresence"
                                                    class="{{ $bgColor }} p-2 rounded text-xs font-semibold uppercase">
                                                    {{ $status }}
                                                </span>

                                                <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent"
                                                    x-show="updatePresence">
                                                    <select name="status[{{ $item->id }}]"
                                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                        :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                                        @change="isOptionSelected = true">
                                                        <option {{ $presence?->status === 'HADIR' ? 'selected' : '' }}
                                                            value="HADIR">
                                                            Hadir
                                                        </option>
                                                        <option {{ $presence?->status === 'TERLAMBAT' ? 'selected' : '' }}
                                                            value="TERLAMBAT">
                                                            Terlambat
                                                        </option>
                                                        <option {{ $presence?->status === 'IZIN' ? 'selected' : '' }}
                                                            value="IZIN">
                                                            Izin
                                                        </option>
                                                        <option {{ $presence?->status === 'SAKIT' ? 'selected' : '' }}
                                                            value="SAKIT">
                                                            Sakit
                                                        </option>
                                                        <option {{ isset($presence) ? '' : 'selected' }}
                                                            {{ $presence?->status === 'ALPHA' ? 'selected' : '' }}
                                                            value="ALPHA">
                                                            Alpha
                                                        </option>
                                                    </select>
                                                    <span
                                                        class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-4 dark:text-gray-400">
                                                        <svg class="stroke-current" width="20" height="20"
                                                            viewBox="0 0 20 20" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396"
                                                                stroke="" stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="px-4 py-3 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                                <p x-show="!updatePresence">
                                                    {{ $presence?->keterangan ?? '-' }}
                                                </p>
                                                <div x-show="updatePresence">
                                                    <textarea name="keterangan[{{ $item->id }}]" type="text" placeholder="(Kosong)"
                                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
{{ $presence?->keterangan ?? '' }}</textarea
                                                    >
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="p-4 text-center text-gray-500 text-theme-xs">
                                                Data Kosong
                                            </td>
                                        </tr>
@endforelse
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ACTIVITY SELECTION MODAL --}}
        <div
            id="activity-modal"
            class="fixed inset-0 z-50 flex items-center justify-center hidden p-4 bg-gray-900/50 backdrop-blur-sm"
        >
            <div
                class="w-full max-w-lg mx-auto overflow-hidden transition-all duration-300 transform bg-white rounded-2xl shadow-3xl"
            >
                <div class="flex items-center justify-between p-5 text-white bg-blue-600 rounded-t-2xl">
                    <h2 class="text-xl font-semibold">Pilih Kegiatan</h2>
                    <button
                        id="close-activity-modal-btn"
                        class="p-1 text-white transition rounded-full hover:text-gray-200"
                    >
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <div class="p-6">
                    <form id="activity-form" action="{{ route('admin.presences.index') }}" method="GET">
                        <div id="activity-list" class="space-y-3 overflow-y-auto max-h-80">
                            {{-- Daftar Kegiatan --}}
                            {{-- NOTE: $activities is a placeholder array from the Laravel context --}}

                            @foreach ($activities as $activity)
<label
                                    class="flex items-center justify-between p-4 transition duration-200 border border-transparent shadow-sm cursor-pointer bg-gray-50 hover:bg-indigo-50 hover:border-indigo-400 rounded-xl"
                                >
                                    <span class="font-semibold text-gray-800">
                                        {{ $activity->nama }}
                                        ({{ $activity->type === 'SEKALI' ? \Carbon\Carbon::parse($activity->tanggal)->format('d M Y') : $activity->nama_hari }})
</span>
                                    <input
                                        type="radio"
                                        name="activity_id"
                                        value="{{ $activity->id }}"
                                        class="w-5 h-5 text-indigo-600 form-radio checked:bg-indigo-600 focus:ring-indigo-500"
                                        {{ request('activity_id') == $activity->id ? 'checked' : '' }}
                                    />
                                </label>
@endforeach
                        </div>
                        <div class="flex justify-end mt-6">
                            <button
                                type="submit"
                                class="px-6 py-2 font-semibold text-white transition duration-150 bg-indigo-600 shadow-lg rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300"
                            >
                                Tampilkan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- EDIT STATUS MODAL --}}
        <template x-teleport="body">
            <div
                x-show="editModal"
                x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/70 backdrop-blur-sm"
                @click.self="closeEditModal()"
            >
                <div class="w-full max-w-md mx-auto overflow-hidden bg-white dark:bg-gray-800 rounded-2xl shadow-3xl">
                    <div class="flex items-center justify-between p-5 text-white bg-yellow-500 rounded-t-2xl">
                        <h2 class="text-xl font-semibold">Edit Status Presensi</h2>
                        <button
                            @click="closeEditModal"
                            class="p-1 text-white transition rounded-full hover:text-gray-100"
                        >
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <form
                        action="{{ isset($activity) ? route('admin.presences.update', $activity->id) : '' }}"
                        method="POST"
                        x-data="{ handleStatusChange: '', keterangan: '' }"
                        class="p-6 space-y-5"
                    >
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="userId" x-model="userId" />

                        <div>
                            <label
                                for="status-select"
                                class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Status Kehadiran
                            </label>
                            <select
                                name="status"
                                id="status-select"
                                x-model="selectedStatus"
                                required
                                @change="handleStatusChange = $event.target.value"
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            >
                                <option value="" disabled selected>Pilih Status</option>
                                <option value="HADIR">HADIR</option>
                                <option value="TERLAMBAT">TERLAMBAT</option>
                                <option value="IZIN">IZIN</option>
                                <option value="SAKIT">SAKIT</option>
                                <option value="ALPHA">ALPHA</option>
                            </select>
                        </div>

                        <div x-show="handleStatusChange === 'IZIN'">
                            <label
                                for="keterangan"
                                class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Keterangan
                            </label>
                            <textarea
                                x-model="keterangan"
                                name="keterangan"
                                id="keterangan"
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                cols="5"
                                rows="5"
                                x-effect="if (handleStatusChange !== 'IZIN') keterangan = ''"
                            ></textarea>
                                                </div>

                                                <div>
                                                    <label for="jam-datang"
                                                        class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Jam Datang (HH:MM)
                                                    </label>
                                                    <input type="time" name="jam_datang" id="jam-datang"
                                                        x-model="jamDatang"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                        :required="selectedStatus === 'HADIR'" />
                                                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika status bukan HADIR
                                                    </p>
                                                </div>

                                                <div class="flex justify-end pt-3">
                                                    <button type="button" @click="closeEditModal"
                                                        class="px-4 py-2 mr-3 text-gray-700 transition bg-gray-200 rounded-xl hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                                        Batal
                                                    </button>
                                                    <button type="submit"
                                                        class="px-4 py-2 text-white transition duration-150 bg-yellow-500 shadow-lg rounded-xl hover:bg-yellow-600">
                                                        Simpan Perubahan
                                                    </button>
                                                </div>
                        </form>
                    </div>
                </div>
                </template>
            </div>
        @endsection

        @push('scripts')
            <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            {{-- alert --}}
            <script>
                if ({{ session()->has('error') }}) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal mengirim absensi!',
                        text: '{{ session('error') }}',
                    });
                }
            </script>
            <script>
                // --- ALPINE.JS SETUP ---
                document.addEventListener('alpine:init', () => {
                    // Edit Modal Logic (moved outside the main x-data block)
                    Alpine.data('editStatusModal', () => ({
                        editModal: false,
                        selectedStatus: '',
                        jamDatang: '',
                        userId: null,

                        openEditModal(userId, currentStatus, currentJam) {
                            this.userId = userId;
                            // Handle 'null' string passed from Blade/PHP for unrecorded status/time
                            this.selectedStatus = currentStatus === 'null' || currentStatus === '' ? 'ALPHA' :
                                currentStatus;
                            this.jamDatang = currentJam === 'null' || currentJam === '' ? '' : currentJam;
                            this.editModal = true;
                        },

                        closeEditModal() {
                            this.editModal = false;
                        },
                    }));
                });

                // --- ACTIVITY MODAL VANILLA JS ---
                const activityModal = document.getElementById('activity-modal');
                const openModalButton = document.getElementById('open-activity-modal-btn');
                const closeModalButton = document.getElementById('close-activity-modal-btn');
                const activityForm = document.getElementById('activity-form');

                function openActivityModal() {
                    activityModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }

                function closeActivityModal() {
                    activityModal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }

                openModalButton.addEventListener('click', openActivityModal);
                closeModalButton.addEventListener('click', closeActivityModal);

                // Handle backdrop click
                activityModal.addEventListener('click', (e) => {
                    if (e.target.id === 'activity-modal') {
                        closeActivityModal();
                    }
                });

                // Handle form submission to filter/redirect
                activityForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const selectedActivity = this.querySelector('input[name="activity_id"]:checked');
                    if (selectedActivity) {
                        const activityId = selectedActivity.value;
                        // Redirect to the current route with the activity ID as a query parameter
                        window.location.href = `{{ route('admin.presences.index') }}?activity_id=${activityId}`;
                    } else {
                        console.warn('Pilih salah satu kegiatan terlebih dahulu.');
                    }
                });

                // --- INITIALIZATION ---
                window.addEventListener('load', () => {
                    // 1. Inisialisasi Lucide Icons
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
            </script>
        @endpush
