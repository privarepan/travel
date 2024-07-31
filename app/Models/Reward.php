<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $original_id
 * @property string|null $phone
 * @property string|null $original_phone
 * @property string|null $amount
 * @property string|null $role_rate 角色分成比例
 * @property int|null $role_lv 角色等级
 * @property string|null $rate 实际分成比例
 * @property string|null $remark
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $original
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Reward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reward query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereOriginalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereOriginalPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereRoleLv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereRoleRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereUserId($value)
 * @mixin \Eloquent
 */
class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','phone','original_id','original_phone','rate','role_lv','
        remark','role_rate','amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->getDateFormat());
    }

    public function original()
    {
        return $this->belongsTo(User::class);
    }
}
