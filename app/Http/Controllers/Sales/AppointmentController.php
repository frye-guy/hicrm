<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppointmentController extends Controller
{
    /**
     * Simple page that renders the Appointments grid (your AlpineJS page).
     * Point a Blade view at resources/views/appointments/index.blade.php
     * (or return your inline HTML if you prefer).
     */
    public function index()
    {
        // If you already have a Blade view for the page, return it:
        // return view('appointments.index');

        // If you don’t have a Blade file yet and want to keep your current inline HTML,
        // you can temporarily return it here. For now assume a Blade exists:
        return view('appointments.index');
    }

    /**
     * JSON endpoint backing the appointments grid.
     * GET /appointments/table
     */
    public function table(Request $r)
    {
        $perPage = (int) ($r->integer('per_page') ?: 50);
        $page    = (int) ($r->integer('page')     ?: 1);
        $search  = trim((string) $r->get('search', ''));
        $from    = $r->get('from'); // YYYY-MM-DD
        $to      = $r->get('to');   // YYYY-MM-DD

        // Keep sort keys to ONLY known columns
        $allowedSort = ['scheduled_for','last_name','city','state','credit','amount_sold'];
        $sort = in_array($r->get('sort'), $allowedSort, true) ? $r->get('sort') : 'scheduled_for';
        $dir  = $r->get('dir') === 'asc' ? 'asc' : 'desc';

        // Feature flags based on your database (guards prevent 500s when tables/columns don’t exist)
        $hasCalls               = Schema::hasTable('calls');
        $hasLeadSources         = Schema::hasTable('lead_sources');
        $hasLeadSourceTypes     = Schema::hasTable('lead_source_types');
        $hasLsShortCode         = $hasLeadSources && Schema::hasColumn('lead_sources','short_code');
        $hasLsTypeId            = $hasLeadSources && Schema::hasColumn('lead_sources','type_id');
        $hasContactLeadSourceId = Schema::hasColumn('contacts','lead_source_id');

        $hasResetBy             = Schema::hasColumn('appointments','reset_by');
        $hasResetAt             = Schema::hasColumn('appointments','reset_at');
        $hasResetReason         = Schema::hasColumn('appointments','reset_reason');

        $hasAmountSold          = Schema::hasColumn('appointments','amount_sold'); // in case you add later

        // Base query
        $q = DB::table('appointments as a')
            ->leftJoin('contacts as c', 'c.id', '=', 'a.contact_id')
            ->leftJoin('users as u', 'u.id', '=', 'a.set_by')               // Set By
            ->leftJoin('sales_reps as sr', 'sr.id', '=', 'a.sales_rep_id')  // Issued To
            ->leftJoin('users as rep', 'rep.id', '=', 'sr.user_id');

        // latest call per contact (optional)
        if ($hasCalls) {
            $q->leftJoin(DB::raw('(SELECT contact_id, MAX(ended_at) AS last_contact_at FROM calls GROUP BY contact_id) lc'),
                    'lc.contact_id', '=', 'a.contact_id');
        }

        // Lead sources (optional) — only join when contacts.lead_source_id exists
        if ($hasLeadSources && $hasContactLeadSourceId) {
            $q->leftJoin('lead_sources as ls', 'ls.id', '=', 'c.lead_source_id');

            if ($hasLeadSourceTypes && $hasLsTypeId) {
                $q->leftJoin('lead_source_types as lst', 'lst.id', '=', 'ls.type_id');
            }
        }

        // Reset info (optional)
        if ($hasResetBy) {
            $q->leftJoin('users as resetter', 'resetter.id', '=', 'a.reset_by');
        }

        // Selects
        $select = [
            'a.id',
            'a.contact_id',
            'a.sales_rep_id',
            'a.scheduled_for',
            'a.duration_min',
            'a.appointment_disposition_id',
            'a.is_sale',
            'a.finance_disposition_id',
            'a.job_status_disposition_id',

            'c.first_name',
            'c.last_name',
            'c.phone',
            'c.city',
            'c.state',
            DB::raw('c.score as credit'),

            DB::raw('u.name  as set_by_name'),
            DB::raw('rep.name as issued_to_name'),
        ];

        if ($hasCalls) {
            $select[] = DB::raw('lc.last_contact_at');
        }

        if ($hasResetBy) {
            $select[] = 'a.reset_by';
            $select[] = DB::raw('resetter.name as reset_by_name');
        }
        if ($hasResetAt)     $select[] = 'a.reset_at';
        if ($hasResetReason) $select[] = 'a.reset_reason';

        if ($hasLeadSources && $hasContactLeadSourceId) {
            $select[] = DB::raw('ls.name as lead_source_name');
            if ($hasLsShortCode) {
                $select[] = DB::raw('ls.short_code as lead_source_code');
            }
            if ($hasLeadSourceTypes && $hasLsTypeId) {
                $select[] = DB::raw('lst.name as lead_source_type');
            }
        }

        if ($hasAmountSold) {
            $select[] = 'a.amount_sold';
        }

        $q->select($select);

        // Search filter
        if ($search !== '') {
            $q->where(function ($w) use ($search) {
                $w->where('c.last_name', 'like', "%{$search}%")
                  ->orWhere('c.phone',     'like', "%{$search}%")
                  ->orWhere('c.city',      'like', "%{$search}%")
                  ->orWhere('c.state',     'like', "%{$search}%");
            });
        }

        // Date range
        if ($from) {
            $q->where('a.scheduled_for', '>=', $from . ' 00:00:00');
        }
        if ($to) {
            $q->where('a.scheduled_for', '<=', $to   . ' 23:59:59');
        }

        // Sorting
        $sortColumn = match ($sort) {
            'last_name'   => 'c.last_name',
            'city'        => 'c.city',
            'state'       => 'c.state',
            'credit'      => 'c.score',
            'amount_sold' => $hasAmountSold ? 'a.amount_sold' : 'a.scheduled_for',
            default       => 'a.scheduled_for',
        };
        $q->orderBy($sortColumn, $dir);

        // Paginate & respond
        $paginator = $q->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data'      => $paginator->items(),
            'next_page' => $paginator->nextPageUrl() ? ($page + 1) : null,
        ]);
    }
}
