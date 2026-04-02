<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class VendorAccountController extends Controller
{
    public function showRegister()
    {
        return view('auth.vendor-register');
    }

    public function register(Request $request)
    {
        $data = $this->validateVendorData($request);

        $user = $this->createVendorAccount($data);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('vendor.dashboard')->with('success', 'Akun vendor berhasil dibuat.');
    }

    public function index()
    {
        $vendors = User::where('type', 'vendor')
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.vendors.index', compact('vendors'));
    }

    public function store(Request $request)
    {
        $data = $this->validateVendorData($request);

        $this->createVendorAccount($data);

        return back()->with('success', 'Akun vendor berhasil ditambahkan.');
    }

    private function validateVendorData(Request $request): array
    {
        $vendorRules = [
            'required',
            'email',
            'max:255',
            Rule::unique('users', 'email'),
        ];

        if (Schema::hasTable('vendor') && Schema::hasColumn('vendor', 'email')) {
            $vendorRules[] = Rule::unique('vendor', 'email');
        }

        return $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'email' => $vendorRules,
            'phone' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:1000',
            'kota' => 'nullable|string|max:100',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    private function createVendorAccount(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $vendorKeyColumn = Schema::hasColumn('vendor', 'id_vendor') ? 'id_vendor' : 'idvendor';

            $user = User::create([
                'name' => $data['nama_vendor'],
                'email' => $data['email'],
                'type' => 'vendor',
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
            ]);

            if (Schema::hasColumn('users', 'id_vendor')) {
                $user->forceFill([
                    'id_vendor' => $user->id,
                ])->save();
            }

            $vendorData = [
                'nama_vendor' => $data['nama_vendor'],
            ];

            $vendorData[$vendorKeyColumn] = $user->id;

            if (Schema::hasColumn('vendor', 'email')) {
                $vendorData['email'] = $data['email'];
            }

            if (Schema::hasColumn('vendor', 'phone')) {
                $vendorData['phone'] = $data['phone'] ?? null;
            }

            if (Schema::hasColumn('vendor', 'alamat')) {
                $vendorData['alamat'] = $data['alamat'] ?? null;
            }

            if (Schema::hasColumn('vendor', 'kota')) {
                $vendorData['kota'] = $data['kota'] ?? null;
            }

            DB::table('vendor')->insert($vendorData);

            return $user;
        });
    }
}