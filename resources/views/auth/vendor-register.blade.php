<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Vendor - Fast Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .food-register-bg {
            background:
                radial-gradient(circle at top right, rgba(251, 146, 60, 0.20), transparent 28%),
                radial-gradient(circle at bottom left, rgba(249, 115, 22, 0.14), transparent 26%),
                linear-gradient(135deg, #fff7ed 0%, #fffdf8 50%, #fffaf5 100%);
        }
        .food-register-card {
            background: rgba(255,255,255,.92);
            border: 1px solid rgba(253, 186, 116, .55);
            box-shadow: 0 24px 50px rgba(124, 45, 18, .10);
            backdrop-filter: blur(10px);
        }
        .food-register-accent {
            background: linear-gradient(135deg, #ea580c 0%, #fb923c 100%);
        }
    </style>
</head>
<body class="food-register-bg min-h-screen flex items-center justify-center p-4 text-slate-900">
    <div class="w-full max-w-2xl">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl food-register-accent shadow-lg mb-6 text-white">
                <i class="fas fa-store text-3xl"></i>
            </div>
            <p class="mb-2 inline-flex items-center rounded-full border border-orange-200 bg-white px-4 py-1 text-xs font-bold uppercase tracking-widest text-orange-700">
                Vendor Registration
            </p>
            <h1 class="text-4xl font-black text-slate-900 mb-2">Daftar Akun Vendor</h1>
            <p class="text-slate-600 text-base">Buat akun vendor untuk login ke dashboard dan mengelola menu, pesanan, serta pembayaran.</p>
        </div>

        <div class="food-register-card rounded-3xl p-8 sm:p-10">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                    <h3 class="font-semibold text-red-800 mb-2">Registrasi gagal</h3>
                    <ul class="text-sm text-red-700 space-y-1 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('vendor.register.store') }}" class="grid gap-5 md:grid-cols-2">
                @csrf

                <div class="md:col-span-2">
                    <label for="nama_vendor" class="block text-sm font-bold text-slate-700 mb-2">Nama Vendor</label>
                    <input type="text" name="nama_vendor" id="nama_vendor" value="{{ old('nama_vendor') }}" placeholder="Contoh: Dapur Nusantara" class="w-full px-4 py-3 border border-orange-200 rounded-2xl focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100" required>
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email Login</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="vendor@contoh.com" class="w-full px-4 py-3 border border-orange-200 rounded-2xl focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100" required>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-bold text-slate-700 mb-2">No. HP</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 border border-orange-200 rounded-2xl focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100">
                </div>

                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-bold text-slate-700 mb-2">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" placeholder="Alamat usaha vendor" class="w-full px-4 py-3 border border-orange-200 rounded-2xl focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100">{{ old('alamat') }}</textarea>
                </div>

                <div>
                    <label for="kota" class="block text-sm font-bold text-slate-700 mb-2">Kota</label>
                    <input type="text" name="kota" id="kota" value="{{ old('kota') }}" placeholder="Contoh: Jakarta" class="w-full px-4 py-3 border border-orange-200 rounded-2xl focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100">
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <input type="password" name="password" id="password" placeholder="Minimal 6 karakter" class="w-full px-4 py-3 border border-orange-200 rounded-2xl focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100" required>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi password" class="w-full px-4 py-3 border border-orange-200 rounded-2xl focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100" required>
                </div>

                <div class="md:col-span-2 flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl food-register-accent px-6 py-3 font-bold text-white shadow-lg hover:opacity-95 transition">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Vendor
                    </button>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-2xl border border-orange-200 bg-white px-6 py-3 font-semibold text-orange-700 hover:bg-orange-50 transition">
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>