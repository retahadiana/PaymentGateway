<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Vendor - Fast Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .admin-bg {
            background:
                radial-gradient(circle at top left, rgba(251, 146, 60, 0.15), transparent 28%),
                linear-gradient(135deg, #fff7ed 0%, #fffdf8 45%, #fff9f3 100%);
        }
        .admin-card {
            background: rgba(255,255,255,.92);
            border: 1px solid rgba(253, 186, 116, .5);
            box-shadow: 0 24px 50px rgba(124, 45, 18, .10);
        }
        .admin-accent {
            background: linear-gradient(135deg, #b45309 0%, #f97316 100%);
        }
    </style>
</head>
<body class="admin-bg min-h-screen text-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-8">
            <div>
                <p class="mb-2 inline-flex items-center rounded-full border border-orange-200 bg-white px-4 py-1 text-xs font-bold uppercase tracking-widest text-orange-700">Admin Panel</p>
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900">Tambah Akun Vendor</h1>
                <p class="mt-2 text-slate-600">Buat akun vendor baru, lalu akun tersebut bisa login dengan email dan password.</p>
            </div>
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-2xl border border-orange-200 bg-white px-5 py-3 font-semibold text-orange-700 hover:bg-orange-50 transition">
                <i class="fas fa-house mr-2"></i>Beranda
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-5">
            <div class="admin-card rounded-3xl p-6 lg:col-span-2">
                <h2 class="text-xl font-black text-slate-900 mb-4">Form Vendor Baru</h2>

                @if ($errors->any())
                    <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700">
                        <ul class="list-disc pl-5 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.vendors.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2" for="nama_vendor">Nama Vendor</label>
                        <input id="nama_vendor" name="nama_vendor" value="{{ old('nama_vendor') }}" class="w-full rounded-2xl border border-orange-200 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2" for="email">Email Login</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-orange-200 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2" for="phone">No. HP</label>
                        <input id="phone" name="phone" value="{{ old('phone') }}" class="w-full rounded-2xl border border-orange-200 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2" for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="3" class="w-full rounded-2xl border border-orange-200 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100">{{ old('alamat') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2" for="kota">Kota</label>
                        <input id="kota" name="kota" value="{{ old('kota') }}" class="w-full rounded-2xl border border-orange-200 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2" for="password">Password</label>
                        <input id="password" name="password" type="password" class="w-full rounded-2xl border border-orange-200 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2" for="password_confirmation">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded-2xl border border-orange-200 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100" required>
                    </div>
                    <button type="submit" class="admin-accent inline-flex w-full items-center justify-center rounded-2xl px-6 py-3 font-bold text-white shadow-lg hover:opacity-95 transition">
                        <i class="fas fa-store mr-2"></i>Simpan Vendor
                    </button>
                </form>
            </div>

            <div class="admin-card rounded-3xl p-6 lg:col-span-3">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h2 class="text-xl font-black text-slate-900">Daftar Vendor Terdaftar</h2>
                    <span class="rounded-full bg-orange-100 px-3 py-1 text-sm font-semibold text-orange-700">{{ $vendors->total() }} akun</span>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-orange-100">
                    <table class="min-w-full divide-y divide-orange-100 text-left text-sm">
                        <thead class="bg-orange-50 text-slate-700">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Nama</th>
                                <th class="px-4 py-3 font-semibold">Email</th>
                                <th class="px-4 py-3 font-semibold">Telepon</th>
                                <th class="px-4 py-3 font-semibold">Tipe</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-orange-50 bg-white">
                            @forelse ($vendors as $vendor)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-slate-900">{{ $vendor->name }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $vendor->email }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $vendor->phone ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-full bg-orange-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-orange-700">{{ $vendor->type }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-500">Belum ada akun vendor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $vendors->links() }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>