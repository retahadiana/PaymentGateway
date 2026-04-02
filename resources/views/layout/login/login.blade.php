<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Login - Fast Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .food-login-bg {
            background:
                radial-gradient(circle at top left, rgba(251, 146, 60, 0.18), transparent 30%),
                radial-gradient(circle at bottom right, rgba(239, 68, 68, 0.12), transparent 28%),
                linear-gradient(135deg, #fff7ed 0%, #fffaf4 45%, #fffbf7 100%);
        }
        .food-login-card {
            background: rgba(255,255,255,.88);
            border: 1px solid rgba(253, 186, 116, .55);
            backdrop-filter: blur(10px);
            box-shadow: 0 24px 50px rgba(124, 45, 18, .10);
        }
        .food-login-accent {
            background: linear-gradient(135deg, #ea580c 0%, #fb923c 100%);
        }
    </style>
</head>
<body class="food-login-bg min-h-screen flex items-center justify-center p-4 text-slate-900">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl food-login-accent shadow-lg mb-6 text-white">
                <i class="fas fa-mug-hot text-3xl"></i>
            </div>
            <p class="mb-2 inline-flex items-center rounded-full border border-orange-200 bg-white px-4 py-1 text-xs font-bold uppercase tracking-widest text-orange-700">
                Vendor Access
            </p>
            <h1 class="text-4xl font-black text-slate-900 mb-2">Login Vendor</h1>
            <p class="text-slate-600 text-base">Masuk untuk mengelola menu, pesanan, dan penjualan makanan minuman.</p>
        </div>

        <div class="food-login-card rounded-3xl p-8 sm:p-10 mb-6">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-start gap-3">
                    <i class="fas fa-circle-exclamation text-red-500 text-xl mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-red-800 mb-1">Login gagal</h3>
                        <ul class="text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-orange-600"></i>Email Vendor
                    </label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        placeholder="vendor@example.com"
                        class="w-full px-4 py-3 text-base border border-orange-200 rounded-2xl bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100 transition @error('email') border-red-400 @enderror"
                        required
                    >
                    @error('email')
                        <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                            <i class="fas fa-info-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-lock mr-2 text-orange-600"></i>Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Masukkan password"
                        class="w-full px-4 py-3 text-base border border-orange-200 rounded-2xl bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100 transition @error('password') border-red-400 @enderror"
                        required
                    >
                    @error('password')
                        <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                            <i class="fas fa-info-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-between gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            class="w-4 h-4 rounded border-orange-300 text-orange-600 focus:ring-orange-500 cursor-pointer"
                        >
                        <span class="text-sm font-medium text-slate-700">Remember me</span>
                    </label>
                    <span class="text-sm font-semibold text-orange-700">Tenant Food & Beverage</span>
                </div>

                <button
                    type="submit"
                    class="w-full py-3.5 food-login-accent text-white text-base font-bold rounded-2xl hover:opacity-95 transition shadow-lg hover:shadow-xl"
                >
                    <i class="fas fa-right-to-bracket mr-2"></i>Masuk Vendor
                </button>
            </form>
        </div>

        <div class="text-center text-xs text-slate-500">
            <p class="flex items-center justify-center gap-2">
                <i class="fas fa-shield-heart text-green-600"></i>
                Akses vendor aman untuk mengelola pesanan makanan dan minuman
            </p>
        </div>
    </div>
</body>
</html>
