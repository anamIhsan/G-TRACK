<style>
    th.rotate {
        white-space: nowrap;
        vertical-align: middle;
        text-align: left;
    }
    .imageContainer {
        text-align: center;
        margin-bottom: 20px;
    }
    img {
        border-radius: 10%;
        height: 100px;
    }
</style>

@php
    $public_path = "";
    $image = $user->gambar;

    if (env("TYPE_SERVER") === "CPANEL" && env("APP_ENV") === "production") {
        $public_path = env("DOCUMENT_STORAGE");
        $image = asset($user->gambar);
    } else {
        $public_path = public_path();
        $image = public_path($user->gambar);
    }
@endphp

@if ($user->gambar && file_exists($public_path . $user->gambar))
    <div class="imageContainer">
        <img src="{{ $image }}" alt="profile" />
        <p style="font-size: 20px">{{ $user->nama }}</p>
    </div>
@endif

<table width="100%" border="1" cellspacing="0" cellpadding="6">
    <tr>
        <th class="rotate" colspan="2"><div>DATA USER</div></th>
    </tr>

    <tr>
        <th class="rotate"><div>Nama</div></th>
        <td>: {{ $user->nama }}</td>
    </tr>

    <tr>
        <th class="rotate"><div>Email</div></th>
        <td>: {{ $user->email }}</td>
    </tr>

    <tr>
        <th class="rotate"><div>No Telepon</div></th>
        <td>: {{ $user->no_tlp }}</td>
    </tr>

    <tr>
        <th class="rotate"><div>NFC ID</div></th>
        <td>: {{ $user->nfc_id }}</td>
    </tr>

    <tr>
        <th class="rotate"><div>Username</div></th>
        <td>: {{ $user->username }}</td>
    </tr>

    <tr>
        <th class="rotate"><div>Jenis Kelamin</div></th>
        <td>: {{ $user->kelamin === "PR" ? "Perempuan" : "Laki-laki" }}</td>
    </tr>

    <tr>
        <th class="rotate"><div>Tanggal Lahir</div></th>
        <td>: {{ $user->tanggal_lahir }}</td>
    </tr>

    <tr>
        <th class="rotate"><div>Umur</div></th>
        <td>: {{ $user->umur }}</td>
    </tr>
    {{--
        <tr>
        <th class="rotate"><div>Password</div></th>
        <td>: {{ $user->hint_password }}</td>
        </tr>
    --}}

    <tr>
        <th class="rotate"><div>Status Kawin</div></th>
        <td>: {{ $user->status_kawin }}</td>
    </tr>

    <tr>
        <th class="rotate"><div>Daerah</div></th>
        <td>: {{ $user->zone->nama ?? "" }}</td>
    </tr>

    <tr>
        <th class="rotate"><div>Desa</div></th>
        <td>: {{ $user->village->nama ?? "" }}</td>
    </tr>

    <tr>
        <th class="rotate"><div>Kelompok</div></th>
        <td>: {{ $user->group->nama ?? "" }}</td>
    </tr>

    <tr>
        <th class="rotate"><div>Pekerjaan</div></th>
        <td>: {{ $user->work->nama ?? "" }}</td>
    </tr>

    <tr>
        <th class="rotate"><div>Minat</div></th>
        <td>: {{ $user->interest->nama ?? "" }}</td>
    </tr>

    <tr>
        <th class="rotate"><div>Sub Minat</div></th>
        <td>: {{ $user->subInterest->nama ?? "" }}</td>
    </tr>

    <tr>
        <th class="rotate"><div>Komunitas</div></th>
        <td>: {{ $user->communities->pluck("nama")->implode(", ") }}</td>
    </tr>
</table>

<table width="100%" border="1" style="padding-top: 20px" cellspacing="0" cellpadding="6">
    <tr>
        <th class="rotate" colspan="2"><div>DATA TAMBAHAN</div></th>
    </tr>

    @forelse ($user->baseMetafieldUsers as $metafield)
        <tr>
            <th class="rotate">
                <div>
                    {{ $metafield->metafieldUser?->field ?? "Unknown" }}
                </div>
            </th>
            <td>: {{ $metafield->value ?? "" }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="2" class="p-4 text-center text-gray-500 text-theme-xs">Data Kosong</td>
        </tr>
    @endforelse
</table>

<table width="100%" border="1" style="padding-top: 20px" cellspacing="0" cellpadding="6">
    <tr>
        <th class="rotate" colspan="2"><div>TINGKAT KEKHATAMAN</div></th>
    </tr>

    @forelse ($user->level as $level)
        <tr>
            <th class="rotate">
                <div>
                    {{ $level->metafieldLevels->field_name ?? "Unknown" }}
                    @if ($level->metafieldLevels->halaman !== null)
                            ({{ $level->metafieldLevels->halaman ?? "Unknown" }} Halaman)
                    @endif
                </div>
            </th>
            <td>
                : {{ $level->value ?? "" }}
                @if ($level->halaman !== null)
                    ({{ $level->halaman ?? "Unknown" }} Halaman)
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="2" class="p-4 text-center text-gray-500 text-theme-xs">Data Kosong</td>
        </tr>
    @endforelse
</table>
