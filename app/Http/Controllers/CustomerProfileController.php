<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class CustomerProfileController extends Controller
{
    public function edit()
    {
        $customer = Auth::guard('customer')->user();

        return Inertia::render('Customer/Profile', [
            'customer' => [
                'name' => $customer->name,
                'email' => $customer->email,
            ],
        ]);
    }

    public function updateName(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $customer = Auth::guard('customer')->user();
        $customer->update([
            'name' => $validated['name'],
        ]);

        return back()->with('success', 'Nama berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $customer = Auth::guard('customer')->user();

        if (!Hash::check($validated['current_password'], $customer->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }

        $customer->update([
            'password' => $validated['password'],
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|string',
        ]);

        $customer = Auth::guard('customer')->user();

        if (!Hash::check($validated['password'], $customer->password)) {
            return back()->withErrors([
                'delete_password' => 'Password tidak sesuai.',
            ]);
        }

        Auth::guard('customer')->logout();
        $customer->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun berhasil dihapus.');
    }
}
