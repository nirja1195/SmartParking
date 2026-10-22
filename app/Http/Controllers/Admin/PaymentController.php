<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Requirement #35: Admin Payment Management (/admin/payments).
     */
    public function index(Request $request): View
    {
        $query = Payment::with(['booking.parkingLocation', 'user'])->orderBy('id', 'desc');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhere('payment_id', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        if ($method = $request->input('method')) {
            $query->where('payment_method', $method);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $payments = $query->paginate(15)->withQueryString();
        $totalRevenue = Payment::where('status', Payment::STATUS_SUCCESS)->sum('amount');

        return view('admin.payments.index', compact('payments', 'totalRevenue'));
    }

    public function transactions(Request $request): View
    {
        $query = Payment::with(['booking.parkingLocation', 'booking.slot', 'user'])->orderBy('id', 'desc');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhere('payment_id', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $transactions = $query->paginate(20)->withQueryString();

        return view('admin.payments.transactions', compact('transactions'));
    }
}
