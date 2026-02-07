@extends("layouts.app")

@section("title", "ADMIN | Detail Pengguna")

@section("content")
    <div class="p-5 bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5 lg:p-6">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">Detail Pengguna</h3>

        <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                <div class="flex flex-col items-center w-full gap-6 xl:flex-row">
                    <div class="w-20 h-20 overflow-hidden border border-gray-200 rounded-full dark:border-gray-800">
                        <img
                            class="w-full h-full bg-cover"
                            src="{{ $data->gambar ? $data->gambar : "https://ui-avatars.com/api/?name=" . $data->nama . "&background=random" }}"
                            alt="user"
                        />
                    </div>
                    <div class="order-3 xl:order-2">
                        <h4
                            class="mb-2 text-lg font-semibold text-center text-gray-800 dark:text-white/90 xl:text-left"
                        >
                            {{ $data->nama }}
                        </h4>
                        <div class="flex flex-col items-center gap-1 text-center xl:flex-row xl:gap-3 xl:text-left">
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $data->username }}</p>
                            <div class="hidden h-3.5 w-px bg-gray-300 dark:bg-gray-700 xl:block"></div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $data->kelamin === "LK" ? "Laki-laki" : ($data->kelamin === "PR" ? "Perempuan" : "Tidak diatur") }}
                            </p>
                        </div>
                    </div>
                </div>

                <a
                    href="{{ route("admin.users.edit", $data->id) }}"
                    @click="isProfileInfoModal = true; loaded = true"
                    class="flex items-center justify-center w-full gap-2 px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200 lg:inline-flex lg:w-auto"
                >
                    <svg
                        class="fill-current"
                        width="18"
                        height="18"
                        viewBox="0 0 18 18"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z"
                            fill=""
                        />
                    </svg>
                    Edit
                </a>
                <form id="{{ $data->id }}" action="{{ route("admin.users.destroy", $data->id) }}" method="POST">
                    @csrf
                    @method("DELETE")
                    <button
                        type="button"
                        onclick="confirmDelete('{{ $data->id }}')"
                        type="submit"
                        class="flex items-center justify-center w-full gap-2 px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full shadow-theme-xs hover:bg-gray-50 hover:text-red-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-red/5 dark:hover:text-red-200 lg:inline-flex lg:w-auto"
                    >
                        <i class="fa-regular fa-trash-can"></i>
                        <p class="hidden text-theme-sm md:block">Hapus</p>
                    </button>
                </form>
            </div>
        </div>

        <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">Data pribadi</h4>

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Nama</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $data->nama ?? "Tidak diatur" }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Username</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $data->username ?? "Tidak diatur" }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Email</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $data->email ?? "Tidak diatur" }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Nomor WA/Tlp</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $data->no_tlp ?? "Tidak diatur" }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">NFC ID</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $data->nfc_id ?? "Tidak diatur" }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Tanggal Lahir</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $data->tanggal_lahir ?? "Tidak diatur" }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Umur</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $data->umur ?? "Tidak diatur" }}
                                @if ($data->umur)
                                    Tahun
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                {{ $data->status_kawin === "BELUM" ? "Kategori umur" : "Status Menikah" }}
                            </p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                @if ($data->status_kawin === "BELUM")
                                    {{ $data->ageCategory?->nama ?? "Tidak diatur" }}
                                @else
                                    Sudah Menikah
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Jenis Kelamin</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $data->kelamin_format ?? "Tidak diatur" }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Pekerjaan</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $data->work?->nama ?? "Tidak diatur" }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Daerah</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $data->zone?->nama ?? "Tidak diatur" }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Desa</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $data->village?->nama ?? "Tidak diatur" }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Kelompok</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $data->group?->nama ?? "Tidak diatur" }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Minat</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $data->interest?->nama ?? "Tidak diatur" }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Sub minat</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $data->subInterest?->nama ?? "Tidak diatur" }}
                            </p>
                        </div>

                        @php
                            $baseMetafieldUsers = $data->baseMetafieldUsers;
                        @endphp

                        @foreach ($metafieldUsers as $metafield)
                            @if (isset($baseMetafieldUsers))
                                @php
                                    $baseMetafieldUser = $baseMetafieldUsers->where("metafield_user_id", $metafield->id)->first();
                                @endphp

                                @if ($baseMetafieldUser)
                                    <div>
                                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                            {{ $baseMetafieldUser->metafieldUser->field }}
                                        </p>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                            {{ $baseMetafieldUser->value ?? "Tidak diatur" }}
                                        </p>
                                    </div>
                                @else
                                    <div>
                                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                            {{ $metafield->field }}
                                        </p>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">Tidak diatur</p>
                                    </div>
                                @endif
                            @else
                                <div>
                                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                        {{ $metafield->field }}
                                    </p>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">Tidak diatur</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="p-5 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">Komunitas</h4>

                    <div class="flex flex-wrap gap-2">
                        @forelse ($data->communities as $community)
                            <span
                                class="inline-flex items-center px-3 py-1 text-sm font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-500/10 dark:text-green-400"
                            >
                                {{ $community->nama }}
                            </span>
                        @empty
                            <span class="text-xs text-gray-500 dark:text-gray-400">Tidak diatur</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- alert success --}}
    <script>
        if ({{ session()->has("success") }}) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
            });
        }
    </script>

    {{-- alert confirm delete --}}
    <script>
        function confirmDelete(formId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                html: `
            <div style="text-align:left">
                <label for="reason-select"
                    style="display:block; margin-bottom:6px; font-size:14px; font-weight:500; color:#374151;">
                    Pilih Alasan
                </label>

                <select id="reason-select"
                    style="
                        width:100%;
                        height:2.75rem;
                        border-radius:0.5rem;
                        border:1px solid #D1D5DB;
                        background-color:transparent;
                        padding:0.625rem 2.75rem 0.625rem 1rem;
                        font-size:0.875rem;
                        color:#1F2937;
                        appearance:none;
                        transition:all 0.2s;
                    ">
                    <option value="" disabled selected>-- Pilih Salah Satu --</option>
                    <option value="Mondok">Mondok</option>
                    <option value="Menikah">Menikah</option>
                    <option value="Pindah Sambung">Pindah Sambung</option>
                    <option value="Lainnya">Lainnya...</option>
                </select>

                <input id="custom-reason"
                    placeholder="Tulis alasan lain..."
                    style="
                        margin-top:0.75rem;
                        display:none;
                        width:100%;
                        height:2.75rem;
                        border-radius:0.5rem;
                        border:1px solid #D1D5DB;
                        padding:0.625rem 1rem;
                        font-size:0.875rem;
                        color:#1F2937;
                        background-color:transparent;
                        transition:all 0.2s;
                    " />
            </div>
        `,
                preConfirm: () => {
                    const select = document.getElementById('reason-select');
                    const custom = document.getElementById('custom-reason');

                    if (!select.value) {
                        Swal.showValidationMessage('Silakan pilih alasan!');
                        return false;
                    }

                    if (select.value === 'Lainnya' && !custom.value) {
                        Swal.showValidationMessage('Isi alasan lain!');
                        return false;
                    }

                    return select.value === 'Lainnya' ? custom.value : select.value;
                },
                didOpen: () => {
                    const select = document.getElementById('reason-select');
                    const custom = document.getElementById('custom-reason');

                    select.addEventListener('change', () => {
                        if (select.value === 'Lainnya') {
                            custom.style.display = 'block';
                            custom.focus();
                        } else {
                            custom.style.display = 'none';
                            custom.value = '';
                        }
                    });
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    const reason = result.value;

                    fetch(`/api/users/reason/${formId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            reason: reason,
                        }),
                    })
                        .then((res) => res.json())
                        .then((data) => {
                            console.log('Reason:', data.reason);
                        })
                        .catch((err) => {
                            console.error(err);
                        });

                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
@endpush
