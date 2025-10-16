<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields.
     * Keep this in sync with your migration columns.
     */
    protected $fillable = [
        // core
        'contact_id',
        'scheduled_for',
        'interested_in',
        'location',
        'duration_minutes',

        // who set / who with (either user IDs or free-form names)
        'set_by_user_id',
        'set_by_name',
        'set_with_id',
        'set_with_name',

        // status/result
        'result',
        'result_reason',
        'confirmed_at',
        'canceled_at',
        'cancellation_reason',
        'follow_up_at',

        // optional time window
        'window_start',
        'window_end',

        // sales/admin side (only keep the ones you actually added)
        'sales_rep_id',
        'product',
        'price_quoted',
        'price_sold',

        // extra sales metrics (present in your current casts)
        'install_at',
        'measured_at',
        'result_48hr',
        'result_onspot',
        'below_par',
        'got_docs',
        'amount_sold',
        'net',
        'bonus_ovr',
        'bonus_net',
        'par',
        'commission_pct',
        'le_win',
        'tf_win',

        // link to disposition + free-form notes
        'disposition_id',
        'notes',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        // core dates
        'scheduled_for'     => 'datetime',
        'confirmed_at'      => 'datetime',
        'canceled_at'       => 'datetime',
        'follow_up_at'      => 'datetime',
        'window_start'      => 'datetime',
        'window_end'        => 'datetime',

        // core numerics
        'duration_minutes'  => 'integer',

        // prices
        'price_quoted'      => 'decimal:2',
        'price_sold'        => 'decimal:2',

        // extra sales dates/bools/numerics (only if present in your DB)
        'install_at'        => 'datetime',
        'measured_at'       => 'datetime',
        'result_48hr'       => 'bool',
        'result_onspot'     => 'bool',
        'below_par'         => 'bool',
        'got_docs'          => 'bool',
        'amount_sold'       => 'decimal:2',
        'net'               => 'decimal:2',
        'bonus_ovr'         => 'decimal:2',
        'bonus_net'         => 'decimal:2',
        'par'               => 'decimal:2',
        'commission_pct'    => 'decimal:2',
        'le_win'            => 'decimal:2',
        'tf_win'            => 'decimal:2',
    ];

    /* =========================================================================
     |  Relationships
     * ========================================================================= */

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * The user who scheduled (“set by”) the appointment.
     * (Uses the correct FK: set_by_user_id)
     */
    public function setByUser()
    {
        return $this->belongsTo(User::class, 'set_by_user_id');
    }

    /**
     * The user the appointment is with.
     */
    public function setWithUser()
    {
        return $this->belongsTo(User::class, 'set_with_id');
    }

    /**
     * Sales rep associated with the appointment.
     */
    public function salesRep()
    {
        return $this->belongsTo(User::class, 'sales_rep_id');
    }

    /**
     * Linked disposition (if you’re storing it directly on appointment).
     */
    public function disposition()
    {
        return $this->belongsTo(Disposition::class);
    }
}
