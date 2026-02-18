@extends('layouts.app')

@section('title', 'ADMIN | Ubah Custom Kolom Pengguna')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <form x-data="sectionForm()" action="{{ route('admin.metafield_user.update', $data->id) }}" method="POST"
            class="col-span-12">
            @csrf
            @method('PUT')
            <div class="flex">
                <div class="w-full bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5">
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                            Ubah Custom Kolom Pengguna
                        </h3>
                    </div>
                    <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6 dark:border-gray-800">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nama Kolom
                            </label>
                            <input name="field" type="text" value="{{ old('field', $data->field) }}"
                                placeholder="Cth: Nama ayah"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                required />
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Type Kolom
                            </label>
                            <div class="relative z-20 bg-transparent">
                                <select name="type"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                    @change="isOptionSelected = true; type = $event.target.value" required>
                                    <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled selected>
                                        -- Pilih Salah Satu --
                                    </option>
                                    <option {{ old('type', $data->type) === 'STRING' ? 'selected' : '' }} value="STRING"
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Teks
                                    </option>
                                    <option {{ old('type', $data->type) === 'ENUM' ? 'selected' : '' }} value="ENUM"
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Pilihan
                                    </option>
                                </select>
                                <span
                                    class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-4 dark:text-gray-400">
                                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <template x-for="(input, index) in inputs" :key="index + '-' + 'input'">
                            <div class="ml-2" x-transition x-show="type === 'ENUM'">
                                <label
                                    class="mb-1.5 flex items-center text-sm font-medium text-gray-700 dark:text-gray-400">
                                    <span x-text="index + 1"></span>
                                    .&nbsp;
                                    <div class="flex w-full gap-1">
                                        <input :name="'enum_values[' + index + ']'" type="text" placeholder="Masukan opsi"
                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                            x-model="inputs[index]" />
                                        <button x-show="type === 'ENUM'"
                                            @click="if (inputs.length > 2) inputs.splice(index, 1)" x-model="inputs[index]"
                                            :disabled="inputs.length <= 2" type="button"
                                            class="inline-flex items-center px-2 py-1 text-sm font-medium text-white transition rounded-lg shadow-theme-xs"
                                            :class="inputs.length <= 2 ? 'bg-red-200 cursor-not-allowed' :
                                                'bg-red-500 hover:bg-red-600'">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                </label>
                            </div>
                        </template>

                        <div class="flex flex-row justify-between">
                            <div></div>
                            <button x-show="type === 'ENUM'" @click="inputs.push('')" type="button"
                                class="inline-flex items-center px-2 py-1 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                                <i class="fa-regular fa-plus"></i>
                                Ubah Kolom
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-4">
                <a href="{{ route('admin.metafield_user.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-gray-700 transition bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Batal
                </a>

                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function sectionForm() {
            return {
                // === INIT OLD VALUE ===
                type: '{{ old('type', $data->type) }}',
                isOptionSelected: false,
                inputs: @json(json_decode($data->enum_values, true)),

                zone_id: '{{ old('zone_id', $data->zone_id) }}',
            };
        }
    </script>
@endpush
