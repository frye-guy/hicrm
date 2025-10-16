<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function calls(Request $r)
    {
        $rows = DB::table('calls as c')
            ->join('users as u', 'u.id', '=', 'c.user_id')
            ->selectRaw('u.name, DATE(c.started_at) as day, COUNT(*) as calls, SUM(c.disposition_id IS NOT NULL) as disposed, SUM(TIMESTAMPDIFF(SECOND, c.started_at, c.ended_at)) as talk_seconds')
            ->groupBy('u.name', DB::raw('DATE(c.started_at)'))
            ->orderByDesc('day')
            ->limit(200)
            ->get();

        if ($r->get('format') === 'csv') {
            $csv = "User,Day,Calls,Disposed,TalkSeconds\n";
            foreach ($rows as $x) {
                $csv .= "{$x->name},{$x->day},{$x->calls},{$x->disposed},{$x->talk_seconds}\n";
            }
            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="calls.csv"'
            ]);
        }

        return view('reports.calls', compact('rows'));
    }

    public function appointments(Request $r)
    {
        $rows = DB::table('appointments as a')
            ->join('contacts as c','c.id','=','a.contact_id')
            ->leftJoin('users as u','u.id','=','a.set_by')
            ->selectRaw('c.last_name, c.phone, a.scheduled_for, a.duration_min, a.is_sale, u.name as set_by')
            ->orderByDesc('a.scheduled_for')
            ->limit(200)
            ->get();

        if ($r->get('format') === 'csv') {
            $csv = "LastName,Phone,ScheduledFor,DurationMin,IsSale,SetBy\n";
            foreach ($rows as $x) {
                $csv .= "{$x->last_name},{$x->phone},{$x->scheduled_for},{$x->duration_min},".($x->is_sale?'Yes':'No').",{$x->set_by}\n";
            }
            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="appointments.csv"'
            ]);
        }

        return view('reports.appointments', compact('rows'));
    }
}
