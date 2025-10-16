<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today   = Carbon::today();
        $weekAgo = Carbon::today()->subDays(6);

        $kpis = [
            'newLeads7d'  => 0,
            'dialsToday'  => 0,
            'apptsNext7d' => 0,
            'closeRate7d' => null,
        ];

        $calls7 = ['labels' => [], 'values' => []];
        $upcomingAppts  = collect();
        $recentContacts = collect();

        try {
            // Contacts
            if (Schema::hasTable('contacts')) {
                $kpis['newLeads7d'] = DB::table('contacts')
                    ->whereBetween('created_at', [$weekAgo, $today->copy()->endOfDay()])
                    ->count();

                $recentContacts = DB::table('contacts')
                    ->select('id','first_name','last_name','phone','city','state','created_at')
                    ->orderByDesc('created_at')->limit(10)->get();
            }

            // Calls
            if (Schema::hasTable('calls')) {
                $kpis['dialsToday'] = DB::table('calls')->whereDate('started_at', $today)->count();

                // Zero-filled 7-day series
                $series = [];
                for ($i = 6; $i >= 0; $i--) {
                    $d = Carbon::today()->subDays($i)->toDateString();
                    $series[$d] = 0;
                }

                $raw = DB::table('calls')
                    ->selectRaw('DATE(started_at) as d, COUNT(*) as c')
                    ->whereBetween('started_at', [$weekAgo->copy()->startOfDay(), $today->copy()->endOfDay()])
                    ->groupBy('d')
                    ->get();

                foreach ($raw as $row) {
                    $series[$row->d] = (int) $row->c;
                }
                $calls7 = ['labels' => array_keys($series), 'values' => array_values($series)];
            }

            // Appointments
            if (Schema::hasTable('appointments')) {
                $kpis['apptsNext7d'] = DB::table('appointments')
                    ->whereBetween('scheduled_for', [$today, $today->copy()->addDays(7)->endOfDay()])
                    ->count();

                // Close rate (only if is_sale column exists)
                $total7 = DB::table('appointments')
                    ->whereBetween('scheduled_for', [$weekAgo->copy()->startOfDay(), $today->copy()->endOfDay()])
                    ->count();

                $sold7 = 0;
                if (Schema::hasColumn('appointments', 'is_sale')) {
                    $sold7 = DB::table('appointments')
                        ->whereBetween('scheduled_for', [$weekAgo->copy()->startOfDay(), $today->copy()->endOfDay()])
                        ->where('is_sale', 1)->count();
                }
                $kpis['closeRate7d'] = $total7 > 0 ? round($sold7 * 100 / $total7, 1) : null;

                $upcomingAppts = DB::table('appointments as a')
                    ->leftJoin('contacts as c','c.id','=','a.contact_id')
                    ->leftJoin('users as u','u.id','=','a.set_by')
                    ->selectRaw('a.id, a.scheduled_for, a.duration_min, c.first_name, c.last_name, c.phone, u.name as set_by')
                    ->where('scheduled_for', '>=', $today)
                    ->orderBy('scheduled_for')
                    ->limit(10)->get();
            }
        } catch (\Throwable $e) {
            // If anything unexpected happens, don't 500 — render with what we have.
            // You can log it for later inspection:
            \Log::warning('Dashboard partial failure: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
        }

        return view('dashboard', compact('kpis','calls7','upcomingAppts','recentContacts'));
    }
}