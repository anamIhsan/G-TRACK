@extends("layouts.app")

@section("title", "ADMIN | Edit Kekhataman Pengguna")

@section("content")
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <form
            action="{{ route("admin.levels.update", $data->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="col-span-12"
        >
            @csrf
            @method("PUT")
            <div class="flex flex-col gap-2 space-y-5 md:flex-row sm:space-y-6">
                <div class="w-full bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5">
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                            Edit Kekhataman {{ $data->nama }}
                        </h3>
                    </div>

                    <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6 dark:border-gray-800">
                        <div class="w-full">
                            @forelse ($metafieldLevels as $metafieldLevel)
                                @php
                                    $totalHalaman = $metafieldLevel->halaman ?? 0;
                                    $level = $data->level;
                                    $metafieldLevelUser = $level
                                        ->where("metafield_level_id", $metafieldLevel->id)
                                        ->where("user_id", $data->id)
                                        ->first();
                                    $metafieldLevelUserStatus = isset($metafieldLevelUser) ? $metafieldLevelUser->status : "ACCEPTED";
                                    $nilaiPersen = isset($metafieldLevelUser) ? $metafieldLevelUser->value ?? 0 : 0;
                                    $nilaiHalaman = isset($metafieldLevelUser) ? $metafieldLevelUser->halaman ?? 0 : 0;
                                @endphp

                                <div
                                    x-data="{
                                        totalHalaman: {{ $totalHalaman }},
                                        persentase: {{ $nilaiPersen }},
                                        halaman: {{ $nilaiHalaman }},
                                        syncFromPersentase() {
                                            this.halaman = Math.round(this.totalHalaman * (this.persentase / 100))
                                        },
                                        syncFromHalaman() {
                                            this.persentase = Math.round((this.halaman / this.totalHalaman) * 100)
                                        },
                                    }"
                                    class="flex items-center mb-1 justify-between w-full gap-5 p-3 border rounded-3xl {{ $metafieldLevelUserStatus === "PENDING" ? "bg-yellow-100" : "" }}"
                                >
                                    <label
                                        class="mb-1.5 block text-sm font-medium capitalize text-gray-700 {{ $metafieldLevelUserStatus != "PENDING" ? "dark:text-white/90" : "" }} text-wrap"
                                    >
                                        {{ $metafieldLevel->field_name }}
                                        {{ $metafieldLevelUserStatus === "PENDING" ? "(diubah oleh user)" : "" }}
                                    </label>

                                    <div class="flex flex-row gap-2">
                                        <div>
                                            <label
                                                class="mb-1.5 block text-xs font-medium capitalize text-gray-700 {{ $metafieldLevelUserStatus != "PENDING" ? "dark:text-white/90" : "" }}"
                                            >
                                                Persentase
                                            </label>
                                            <input
                                                name="metafield_level_id[{{ $metafieldLevel->id }}]"
                                                type="number"
                                                value="{{ $nilaiPersen || 0 }}"
                                                x-model.number="persentase"
                                                :value="persentase"
                                                @input.debounce.300ms="syncFromPersentase"
                                                class="dark:bg-dark-900 shadow-theme-xs focusb:order-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                            />
                                        </div>

                                        @if ($metafieldLevel->halaman)
                                            <div>
                                                <label
                                                    class="mb-1.5 block text-xs font-medium capitalize text-gray-700 {{ $metafieldLevelUserStatus != "PENDING" ? "dark:text-white/90" : "" }}"
                                                >
                                                    Halaman
                                                </label>
                                                <input
                                                    name="halaman[{{ $metafieldLevel->id }}]"
                                                    type="number"
                                                    x-model.number="halaman"
                                                    :value="halaman"
                                                    @input.debounce.300ms="syncFromHalaman"
                                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                />
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="flex items-center">
                                    <a
                                        class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:hover:bg-white/5 dark:hover:text-gray-200"
                                        href="{{ route("admin.metafield_level.create") }}?user={{ $data->id }}"
                                    >
                                        <i class="fa-regular fa-plus fa-lg"></i>
                                        Tambah Materi
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-4">
                <a
                    href="{{ route("admin.levels.index") }}"
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-gray-700 transition bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600"
                >
                    @php
                        $level = $data->level;
                        if (isset($level) && $level) {
                            foreach ($level as $key => $value) {
                                $levels[] = $value;
                            }

                            if (isset($levels) && count($levels) > 0) {
                                $found = ! empty(
                                    array_filter($levels, function ($item) {
                                        return $item->status === "PENDING";
                                    })
                                );

                                if ($found) {
                                    $cocok = true;
                                } else {
                                    $cocok = false;
                                }
                            } else {
                                $cocok = false;
                            }
                        } else {
                            $cocok = false;
                        }
                    @endphp

                    {{ isset($level) && $cocok ? "Terima dan simpan" : "Simpan" }}
                </button>
            </div>
        </form>
    </div>
@endsection
