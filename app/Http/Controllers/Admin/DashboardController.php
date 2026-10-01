<?php

namespace App\Http\Controllers\Admin;

use App\Admin\DocRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(DocRepository $repo)
    {
        $stats = [
            'customers' => $repo->count('users', ['role' => 'customer']),
            'vendors' => $repo->count('vendors'),
            'products' => $repo->count('vendor_products'),
            'orders' => $repo->count('vendor_orders') + $repo->count('provider_orders') + $repo->count('parcel_orders') + $repo->count('rental_orders'),
        ];

        $byStatus = DB::table('vendor_orders')->select('status', DB::raw('count(*) as n'))
            ->groupBy('status')->orderByDesc('n')->pluck('n', 'status')->all();

        $recent = $repo->paginate('vendor_orders', null, [], [], 'createdAt:desc', 8)->items();

        return view('admin.dashboard', compact('stats', 'byStatus', 'recent'));
    }
}
