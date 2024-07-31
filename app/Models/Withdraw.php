<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $user_id
 * @property string $amount
 * @property int $pay_type
 * @property string $account
 * @property string $name
 * @property string|null $bank_name
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw query()
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw wherePayType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereUserId($value)
 * @mixin \Eloquent
 */
class Withdraw extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','amount','account','pay_type','name','bank_name','status'
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->getDateFormat());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
