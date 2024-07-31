<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property int $id
 * @property int $user_id
 * @property int $route_id
 * @property string $start_at
 * @property string $mobile
 * @property string $name_first
 * @property string|null $name_second
 * @property string $id_card_first
 * @property int $status
 * @property string|null $id_card_second
 * @property string|null $remark
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $status_label
 * @property-read \App\Models\Route|null $route
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute whereIdCardFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute whereIdCardSecond($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute whereNameFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute whereNameSecond($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRoute withoutTrashed()
 * @mixin \Eloquent
 */
class UserRoute extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id','route_id','start_at','mobile','name_first','name_second','id_card_first',
        'id_card_second','remark','status',
    ];

    protected $appends = [
        'status_label'
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->getDateFormat());
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status){
             1 => '预约成功',
             2 => '行程结束',
             default => '',
         };
    }
}
