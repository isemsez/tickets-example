<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class Tickets
 * @package App\Models
 *
 * @property integer $id
 * @property string $to_do
 * @property string $until
 * @property integer $initiator
 * @property integer $doer
 * @property string $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Ticket extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'to_do',

        'until',

        'initiator',

        'doer',

        'status',

    ];

//    /**
//     * The attributes that should be hidden for arrays.
//     *
//     * @var array
//     */
//    protected $hidden = [
//
//    ];
//
//    /**
//     * The attributes that should be cast to native types.
//     *
//     * @var array
//     */
//    protected $casts = [
//
//    ];
//
//    const RuleList = [
//
//        'id' => [],
//
//        'to_do' => [],
//
//        'until' => [],
//
//        'initiator' => [],
//
//        'doer' => [],
//
//        'status' => [],
//
//    ];


    public function users()
    {
        return $this->belongsTo(User::class, 'initiator', 'id');
    }

}
