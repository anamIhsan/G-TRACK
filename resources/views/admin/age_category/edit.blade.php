@extends("layouts.app")

@section("title", "ADMIN | Ubah Kategori Umur")

@section("content")
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <form action="{{ route("admin.age_categories.update", $data->id) }}" method="POST" class="col-span-12">
            @csrf
            @method("PUT")
            <div class="flex">
                <div class="w-full bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/5">
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Ubah Kategori Umur</h3>
                    </div>
                    <div class="p-5 space-y-6 border-t border-gray-100 sm:p-6 dark:border-gray-800">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kategori umur
                            </label>
                            <input
                                name="nama"
                                type="text"
                                placeholder="Cth: Paud"
                                value="{{ old("nama", $data->nama) }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            />
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Range umur
                            </label>
                            <div class="flex flex-col gap-2 md:flex-row">
                                <input
                                    name="range_one"
                                    type="number"
                                    placeholder="Cth: 5"
                                    value="{{ old("range_one", $data->range_one) }}"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                />
                                <input
                                    name="range_two"
                                    type="number"
                                    placeholder="Cth: 8"
                                    value="{{ old("range_two", $data->range_two) }}"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-4">
                <a
                    href="{{ route("admin.age_categories.index") }}"
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-gray-700 transition bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Batal
                </a>

                <button
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600"
                >
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
