<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class CustomerAuthController extends Controller
{
    /**
     * Show the customer login/register page.
     */
    public function show(Request $request)
    {
        $redirect = $request->get('redirect', '/');

        // If already logged in as customer, redirect to intended page
        if (Auth::guard('customer')->check()) {
            return redirect($redirect);
        }

        return Inertia::render('Auth/CustomerAuth', [
            'redirect' => $redirect,
        ]);
    }

    /**
     * Register a new customer.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:customers,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'remember' => 'nullable|boolean',
        ]);

        $customer = Customer::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'password' => $validated['password'],
        ]);

        $remember = (bool) ($validated['remember'] ?? false);
        Auth::guard('customer')->login($customer, $remember);

        $redirect = $request->input('redirect', '/');
        return redirect($redirect);
    }

    /**
     * Login an existing customer.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'remember' => 'nullable|boolean',
        ]);

        $remember = (bool) ($credentials['remember'] ?? false);

        if (Auth::guard('customer')->attempt([
            'email'    => $credentials['email'],
            'password' => $credentials['password'],
        ], $remember)) {
            $request->session()->regenerate();
            $redirect = $request->input('redirect', '/');
            return redirect($redirect);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    /**
     * Logout the customer.
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
