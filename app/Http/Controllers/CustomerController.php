<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customer.home', [
            'customer' => Auth::user(),
            'contract' => null,
            'invoices' => collect(),
            'vehicles' => Vehicle::where('status', 1)->get(),
        ]);
    }

    public function dashboard()
    {
        $user = Auth::user();
        $contract = Contract::with('vehicle')
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->latest('id')
            ->first();
        $invoices = $contract
            ? Invoice::with(['vehicle', 'invoiceDetails.service'])
                ->where('contract_id', $contract->id)
                ->latest('invoice_date')
                ->get()
            : collect();

        return view('customer.home', [
            'customer' => $user,
            'contract' => $contract,
            'invoices' => $invoices,
            'vehicles' => collect(),
        ]);
    }

    public function profile()
    {
        return view('customer.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);
        $user->update($data);

        if ($request->filled('new_password')) {
            $password = $request->validate(['new_password' => ['confirmed']]);
            $user->update(['password' => Hash::make($password['new_password'])]);
        }

        return redirect()->route('customer.home')->with('success', 'Thông tin đã được cập nhật!');
    }

    public function viewContract()
    {
        $customer = Auth::user();
        $contract = Contract::with('vehicle')
            ->where('user_id', $customer->id)
            ->latest('id')
            ->first();

        return view('customer.contract', compact('contract', 'customer'));
    }

    public function viewInvoices()
    {
        $customer = Auth::user();
        $invoices = Invoice::with(['vehicle', 'invoiceDetails.service'])
            ->whereHas('contract', fn ($query) => $query->where('user_id', $customer->id))
            ->latest('invoice_date')
            ->get();

        return view('customer.invoices', compact('invoices', 'customer'));
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'confirmed'],
        ]);
        $user = Auth::user();
        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không chính xác.');
        }

        $user->update(['password' => Hash::make($data['new_password'])]);

        return back()->with('success', 'Đã đổi mật khẩu.');
    }

    public function statistics()
    {
        $user = Auth::user();
        $contract = Contract::with('vehicle')
            ->where('user_id', $user->id)
            ->latest('id')
            ->first();
        $invoices = Invoice::with(['vehicle', 'invoiceDetails.service'])
            ->whereHas('contract', fn ($query) => $query->where('user_id', $user->id))
            ->latest('invoice_date')
            ->get();

        return view('customer.statistics', compact('contract', 'invoices'));
    }
}
