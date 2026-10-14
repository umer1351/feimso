<?php

namespace App\Http\Controllers\Admin;

use App\Admin\DocRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(DocRepository $repo)
    {
        // Feimso 2.0 KPIs — safe even if tables don't exist yet
        $feimso = $this->feimsoStats();

        // Legacy delivery-app stats
        $stats = [
            'customers' => $repo->count('users', ['role' => 'customer']),
            'vendors'   => $repo->count('vendors'),
            'products'  => $repo->count('vendor_products'),
            'orders'    => $repo->count('vendor_orders') + $repo->count('provider_orders')
                         + $repo->count('parcel_orders') + $repo->count('rental_orders'),
        ];

        $byStatus = DB::table('vendor_orders')->select('status', DB::raw('count(*) as n'))
            ->groupBy('status')->orderByDesc('n')->pluck('n', 'status')->all();

        $recent = $repo->paginate('vendor_orders', null, [], [], 'createdAt:desc', 8)->items();

        return view('admin.dashboard', compact('stats', 'byStatus', 'recent', 'feimso'));
    }

    private function feimsoStats(): array
    {
        try {
            return [
                'total_users'       => DB::table('users')->whereNotNull('feimso_role')->count(),
                'pending_verif'     => DB::table('creator_verifications')->where('status', 'pending')->count(),
                'active_listings'   => DB::table('listings')->where('is_published', true)->where('status', 'active')->count(),
                'open_disputes'     => DB::table('disputes')->where('status', 'open')->count(),
                'bookings_today'    => DB::table('bookings')->whereDate('_created', today())->count(),
                'pipeline_approved' => DB::table('creator_pipeline')->where('pipeline_stage', 'approved')->count(),
            ];
        } catch (\Throwable) {
            return [];
        }
    }
}
