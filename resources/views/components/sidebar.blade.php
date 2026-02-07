<?php
$age_categories_data = App\Models\AgeCategory::get();
$age_categories = [];

foreach ($age_categories_data as $key => $age_category) {
    $age_categories[$age_category->slug] = [
        'name' => $age_category->nama,
        'icon' => 'fa-regular fa-user',
        'url' => route('admin.users.index', ['age_category_id' => $age_category->id]),
        'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok'],
    ];
}

$menus = [
    'menu' => [
        'name' => 'MENU',
        'icon' => 'fa-regular fa-menu',
        'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok', 'user'],
        'children' => [
            'dashboard' => [
                'name' => 'Dashboard',
                'icon' => 'fa-regular fa-house',
                'url' => route('dashboard'),
                'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok', 'user'],
            ],
            'user_presences' => [
                'name' => 'Presensi Pengguna',
                'icon' => 'fa-regular fa-newspaper',
                'role' => ['user'],
                'children' => [
                    'nfc' => [
                        'name' => 'NFC saya',
                        'url' => route('user.user_presences.nfc'),
                    ],
                    'index' => [
                        'name' => 'Data Kehadiran',
                        'url' => route('user.user_presences.index'),
                    ],
                ],
            ],
            'user_levels' => [
                'name' => 'Tingkat Kekhataman',
                'icon' => 'fa-regular fa-gem',
                'role' => ['user'],
                'url' => route('user.user_levels.index'),
            ],
            'user_activities' => [
                'name' => 'Kegiatan Saya',
                'icon' => 'fa-regular fa-calendar-days',
                'role' => ['user'],
                'url' => route('user.user_activities.index'),
            ],
            // "subadmins" => [
            //     "name" => "Data SubAdmin",
            //     "icon" => "fa-solid fa-user-lock",
            //     "url" => route("admin.subadmins.index"),
            //     "role" => ["master", "admin_daerah", "admin_desa", "admin_kelompok"],
            // ],
            'users' => [
                'name' => 'Data Pengguna',
                'icon' => 'fa-solid fa-users',
                'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok'],
                'children' => [
                    'users' => [
                        'name' => 'Semua Pengguna',
                        'icon' => 'fa-regular fa-user',
                        'url' => route('admin.users.index'),
                        'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok'],
                    ],
                    ...$age_categories,
                ],
            ],
            'history_users' => [
                'name' => 'History Pengguna',
                'icon' => 'fa-solid fa-user-clock',
                'url' => route('admin.history_users.index'),
                'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok'],
            ],
            'cards' => [
                'name' => 'Cetak Kartu',
                'icon' => 'fa-regular fa-id-badge',
                'url' => route('admin.cards.index'),
                'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok'],
            ],
            'levels' => [
                'name' => 'Tingkat Kekhataman',
                'icon' => 'fa-regular fa-gem',
                'url' => route('admin.levels.index'),
                'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok'],
            ],
            'activities' => [
                'name' => 'Jadwal Kegiatan',
                'icon' => 'fa-regular fa-calendar-days',
                'url' => route('admin.activities.index'),
                'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok'],
            ],
            'notifications' => [
                'name' => 'Notifikasi',
                'icon' => 'fa-regular fa-bell',
                'url' => route('admin.notifications.index'),
                'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok'],
            ],
            'presences' => [
                'name' => 'Presensi',
                'icon' => 'fa-regular fa-newspaper',
                'url' => route('admin.presences.index'),
                'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok'],
            ],
        ],
    ],
    'master_data' => [
        'name' => 'MASTER DATA',
        'icon' => 'fa-regular fa-master-data',
        'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok'],
        'children' => [
            'age_categories' => [
                'name' => 'Data Kategori Umur',
                'icon' => 'fa-solid fa-chart-simple',
                'url' => route('admin.age_categories.index'),
                'role' => ['master'],
            ],
            'zones' => [
                'name' => 'Data Daerah',
                'icon' => 'fa-solid fa-city',
                'url' => route('admin.zones.index'),
                'role' => ['master'],
            ],
            'villages' => [
                'name' => 'Data Desa',
                'icon' => 'fa-regular fa-building',
                'url' => route('admin.villages.index'),
                'role' => ['master', 'admin_daerah'],
            ],
            'groups' => [
                'name' => 'Data Kelompok',
                'icon' => 'fa-solid fa-people-group',
                'url' => route('admin.groups.index'),
                'role' => ['master', 'admin_daerah', 'admin_desa'],
            ],
            'interest' => [
                'name' => 'Data Minat',
                'icon' => 'fa-regular fa-hand-pointer',
                'url' => route('admin.interests.index'),
                'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok'],
            ],
            'communities' => [
                'name' => 'Data Komunitas',
                'icon' => 'fa-solid fa-handshake',
                'url' => route('admin.communities.index'),
                'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok'],
            ],
            'works' => [
                'name' => 'Data Pekerjaan',
                'icon' => 'fa-solid fa-briefcase',
                'url' => route('admin.works.index'),
                'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok'],
            ],
        ],
    ],
    'metafield' => [
        'name' => 'METAFIELD',
        'icon' => 'fa-regular fa-metafield',
        'role' => ['master', 'admin_daerah'],
        'children' => [
            'metafield_users' => [
                'name' => 'Custom Kolom Pengguna',
                'icon' => 'fa-solid fa-table-columns',
                'url' => route('admin.metafield_user.index'),
                'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok'],
            ],
            'metafield_levels' => [
                'name' => 'Materi Kekhataman',
                'icon' => 'fa-solid fa-table-columns',
                'url' => route('admin.metafield_level.index'),
                'role' => ['master', 'admin_daerah'],
            ],
        ],
    ],
    'others' => [
        'name' => 'OTHERS',
        'icon' => 'fa-regular fa-others',
        'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok', 'user'],
        'children' => [
            'profile' => [
                'name' => 'Profile',
                'icon' => 'fa-regular fa-user-circle',
                'url' => route('user.profiles.index'),
                'role' => ['user'],
            ],
            'setting' => [
                'name' => 'Pengaturan',
                'icon' => 'fa-regular fa-address-card',
                'url' => route('setting.index'),
                'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok', 'user'],
            ],
            'logout' => [
                'name' => 'Keluar',
                'icon' => 'fa-solid fa-arrow-right-from-bracket',
                'url' => route('auth.logout'),
                'role' => ['master', 'admin_daerah', 'admin_desa', 'admin_kelompok', 'user'],
            ],
        ],
    ],
];

$auth = [
    'role' => strtolower(Auth::user()->role) ?? 'guest',
];
?>

<aside :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0">
    <!-- SIDEBAR HEADER -->
    <div :class="sidebarToggle ? 'justify-center' : 'justify-between'"
        class="flex items-center gap-2 pt-5 sidebar-header pb-7">
        <a href="{{ route('dashboard') }}">
            <span class="logo" :class="sidebarToggle ? 'hidden' : ''">
                <img class="w-full bg-cover h-11 dark:hidden" src="/images/logo/logo-clear.png" alt="Logo" />
                <img class="hidden w-full bg-cover h-11 dark:block" src="/images/logo/white-logo.png" alt="Logo" />
            </span>

            <span class="bg-cover h-11">
                <img class="h-11 logo-icon" :class="sidebarToggle ? 'lg:block' : 'hidden'"
                    :src="darkMode ? '/images/logo/white-logo.png' : '/images/logo/logo-clear.png'" alt="Logo" />
            </span>
        </a>
    </div>
    <!-- SIDEBAR HEADER -->

    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <!-- Sidebar Menu -->
        <nav x-data="{ selected: $persist('Dashboard') }">
            <!-- Menu Group -->
            @foreach ($menus as $index => $menu)
                @if (in_array($auth['role'], $menu['role']))
                    <div>
                        <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                            <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
                                {{ $menu['name'] }}
                            </span>

                            <i class="{{ $menu['icon'] }} fa-lg mx-auto fill-current menu-group-icon"
                                :class="sidebarToggle ? 'lg:block hidden' : 'hidden'"></i>
                        </h3>

                        <ul class="flex flex-col gap-4 mb-6">
                            @foreach ($menu['children'] as $indexParent => $parent)
                                @if (in_array($auth['role'], $parent['role']))
                                    @if (isset($parent['children']))
                                        <li>
                                            <a href="{{ isset($parent['url']) ? $parent['url'] : '#' }}"
                                                @click.prevent="selected = (selected === '{{ $parent['name'] }}' ? '':'{{ $parent['name'] }}')"
                                                class="menu-item group {{ Request::is($auth['role'] != 'user' ? 'admin/' . $indexParent : 'user/' . $indexParent) ? 'menu-item-active' : 'menu-item-inactive' }}"
                                                :class="(selected === '{{ $parent['name'] }}') ? 'menu-item-active' :
                                                'menu-item-inactive'">
                                                <i class="{{ $parent['icon'] }} fa-lg {{ Request::is($auth['role'] != 'user' ? 'admin/' . $indexParent : 'user/' . $indexParent) ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"
                                                    :class="(selected === '{{ $parent['name'] }}') ? 'menu-item-icon-active' :
                                                    'menu-item-icon-inactive'"></i>

                                                <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                                    {{ $parent['name'] }}
                                                </span>

                                                <svg class="menu-item-arrow"
                                                    :class="[(selected === '{{ $parent['name'] }}') ?
                                                        'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                                                        sidebarToggle ? 'lg:hidden' : ''
                                                    ]"
                                                    width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                                                        stroke="" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </a>

                                            <div class="overflow-hidden transform translate"
                                                :class="(selected === '{{ $parent['name'] }}') ? 'block' : 'hidden'">
                                                <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                                    class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                                    @foreach ($parent['children'] as $indexChild => $child)
                                                        <li>
                                                            <a href="{{ $child['url'] }}"
                                                                class="menu-dropdown-item group {{ Request::is($auth['role'] != 'user' ? 'admin/' . $indexParent . '/' . $indexChild . '*' : 'user/' . $indexParent . '/' . $indexChild . '*') ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}">
                                                                {{ $child['name'] }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{ $parent['url'] }}"
                                                @click="selected = (selected === '{{ $parent['name'] }}' ? '':'{{ $parent['name'] }}')"
                                                class="menu-item group {{ Request::is($indexParent != 'dashboard' && $indexParent != 'setting' ? ($auth['role'] != 'user' ? 'admin/' . $indexParent . '*' : 'user/' . $indexParent . '*') : $indexParent) ? 'menu-item-active' : 'menu-item-inactive' }}">
                                                <i
                                                    class="{{ $parent['icon'] }} fa-lg {{ Request::is($auth['role'] != 'user' ? 'admin/' . $indexParent . '*' : 'user/' . $indexParent) ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"></i>

                                                <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                                    {{ $parent['name'] }}
                                                </span>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endforeach
        </nav>
        <!-- Sidebar Menu -->

        <!-- Promo Box -->
        {{--
            <div
            :class="sidebarToggle ? 'lg:hidden' : ''"
            class="w-full px-4 py-5 mx-auto mb-10 text-center max-w-60 rounded-2xl bg-gray-50 dark:bg-white/5"
            >
            <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">#1 Tailwind CSS Dashboard</h3>
            <p class="mb-4 text-gray-500 text-theme-sm dark:text-gray-400">
            Leading Tailwind CSS Admin Template with 400+ UI Component and Pages.
            </p>
            <a
            href="https://tailadmin.com/pricing"
            target="_blank"
            rel="nofollow"
            class="flex items-center justify-center p-3 font-medium text-white rounded-lg bg-brand-500 text-theme-sm hover:bg-brand-600"
            >
            Purchase Plan
            </a>
            </div>
        --}}
        <!-- Promo Box -->
    </div>
</aside>
