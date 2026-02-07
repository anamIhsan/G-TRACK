@extends("layouts.app")

@section("title", "Tingkat Kekhataman")

@section("content")
    <div class="px-4 py-4">
        <div
            class="px-4 pt-4 pb-3 bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5 sm:px-6"
        >
            <h3 class="mb-5 text-lg font-semibold text-gray-800 border-b dark:text-white/90">Tingkat Kekhataman</h3>
            <div class="w-full space-y-3">
                @php
                    $metafieldLevelCount = count($metafieldLevels);
                @endphp

                <div
                    class="grid grid-cols-1 gap-10 {{ isset($metafieldLevels) ? ($metafieldLevelCount === 1 ? "md:grid-cols-1 lg:grid-cols-1" : ($metafieldLevelCount >= 2 ? "md:grid-cols-2 lg:grid-cols-2" : "md:grid-cols-1 lg:grid-cols-1")) : "md:grid-cols-1 lg:grid-cols-1" }}"
                >
                    @php
                        $level = $authData->level;
                    @endphp

                    @forelse ($metafieldLevels as $metafieldLevel)
                        @php
                            $totalHalaman = $metafieldLevel->halaman ?? 0;
                            $level = $authData->level;
                            $metafieldLevelUser = $level->where("metafield_level_id", $metafieldLevel->id)->first();
                            $metafieldStatus = isset($metafieldLevelUser) ? $metafieldLevelUser->status : "ACCEPTED";
                            $nilaiPersen = isset($metafieldLevelUser) ? $metafieldLevelUser->value ?? 0 : 0;
                            $nilaiHalaman = isset($metafieldLevelUser) ? $metafieldLevelUser->halaman ?? 0 : 0;
                            $itemId = $metafieldLevelUser->id ?? null;
                        @endphp

                        <form
                            action="{{ route("user.user_levels.update", $metafieldLevel->id) }}"
                            method="POST"
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
                                isEdit: false,
                                showTooltip: false,
                            }"
                            class="flex items-center justify-between w-full gap-5 p-3 border rounded-3xl"
                        >
                            @method("PUT")
                            @csrf
                            <label
                                class="mb-1.5 block text-sm font-medium pr-5 capitalize text-gray-800 dark:text-white/90"
                            >
                                {{ $metafieldLevel->field_name }}
                            </label>

                            <div class="flex flex-col flex-wrap items-center justify-end gap-5 lg:flex-row">
                                @if ($metafieldStatus != "PENDING")
                                    <div x-transition x-show="isEdit" class="flex flex-col">
                                        <label
                                            class="mb-1.5 block text-xs font-medium capitalize text-gray-700 dark:text-gray-400"
                                        >
                                            Persentase
                                        </label>
                                        <input
                                            name="value"
                                            type="number"
                                            x-model.number="persentase"
                                            :value="persentase"
                                            @input.debounce.300ms="syncFromPersentase"
                                            class="px-3 py-1 text-sm text-gray-800 bg-transparent border border-gray-300 rounded-lg dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        />
                                    </div>

                                    @if ($metafieldLevel->halaman)
                                        <div x-transition x-show="isEdit" class="flex flex-col">
                                            <label
                                                class="mb-1.5 block text-xs font-medium capitalize text-gray-700 dark:text-gray-400"
                                            >
                                                Halaman
                                            </label>
                                            <input
                                                name="halaman"
                                                type="number"
                                                x-model.number="halaman"
                                                :value="halaman"
                                                @input.debounce.300ms="syncFromHalaman"
                                                class="px-3 py-1 text-sm text-gray-800 bg-transparent border border-gray-300 rounded-lg dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                            />
                                        </div>
                                    @endif
                                @endif

                                <div x-transition x-show="!isEdit" class="flex gap-6">
                                    <span class="text-sm text-gray-800 dark:text-white/90">
                                        Khatam {{ $nilaiPersen ?? 0 }}%
                                    </span>
                                    @if ($metafieldLevel->halaman)
                                        <span class="text-sm text-gray-800 dark:text-white/90">
                                            {{ $nilaiHalaman ?? 0 }} halaman
                                        </span>
                                    @endif
                                </div>

                                @if ($metafieldStatus != "PENDING")
                                    <div class="flex gap-3">
                                        <button
                                            x-show="!isEdit"
                                            @click="isEdit = true"
                                            type="button"
                                            class="flex items-center justify-center w-full gap-2 px-2 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200 lg:inline-flex lg:w-auto"
                                        >
                                            <i class="fa-regular fa-edit"></i>
                                            Edit
                                        </button>
                                        <button
                                            x-show="isEdit"
                                            @click="isEdit = false"
                                            type="button"
                                            class="flex items-center justify-center w-full gap-2 px-2 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full shadow-theme-xs hover:bg-red-50 hover:text-red-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-red/5 dark:hover:text-red-200 lg:inline-flex lg:w-auto"
                                        >
                                            <i class="fa-solid fa-xmark"></i>
                                            Batal
                                        </button>
                                        <button
                                            x-show="isEdit"
                                            @click="isEdit = true"
                                            type="submit"
                                            class="flex items-center justify-center w-full gap-2 px-2 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full shadow-theme-xs hover:bg-brand-50 hover:text-brand-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-brand/5 dark:hover:text-brand-200 lg:inline-flex lg:w-auto"
                                        >
                                            <i class="fa-regular fa-save"></i>
                                            Simpan
                                        </button>
                                    </div>
                                @endif

                                @if ($metafieldStatus === "PENDING")
                                    <div class="relative">
                                        <div
                                            @mouseenter="showTooltip = true"
                                            @mouseleave="showTooltip = false"
                                            class="w-3 h-3 bg-yellow-500 rounded-full"
                                        ></div>

                                        <div
                                            x-show="showTooltip"
                                            x-transition
                                            class="absolute left-0 px-2 py-1 mt-2 text-black -translate-x-1/2 rounded z-99 dark:text-white w-fit bg-amber-200 dark:bg-gray-700 text-theme-xs"
                                            style="display: none"
                                        >
                                            Sedang dikoreksi oleh admin
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </form>
                    @empty
                        <div class="flex items-center">Tidak ada data</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
