<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Packages\Schema\DepartmentLeader;
use App\Packages\Schema\RegionBoss;
use App\Packages\Schema\RegionLeader;
use App\Packages\Schema\Role;
use App\Packages\Schema\TeamLeader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 *
 *
 * @property int $id
 * @property string|null $name 名称
 * @property int $pid
 * @property int $level
 * @property int $role_lv 角色
 * @property string|null $path
 * @property string|null $phone 手机号
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string $invite_code
 * @property int $state 实名认证状态
 * @property int|null $is_member
 * @property string|null $id_card 身份证号
 * @property int $status 状态
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $children
 * @property-read int|null $children_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $order
 * @property-read int|null $order_count
 * @property-read User|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reward> $reward
 * @property-read int|null $reward_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserRoute> $userRoute
 * @property-read int|null $user_route_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Withdraw> $withdraw
 * @property-read int|null $withdraw_count
 * @method static Builder|User childrenByRoleLv($lv)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereIdCard($value)
 * @method static Builder|User whereInviteCode($value)
 * @method static Builder|User whereIsMember($value)
 * @method static Builder|User whereLevel($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePath($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User wherePid($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereRoleLv($value)
 * @method static Builder|User whereState($value)
 * @method static Builder|User whereStatus($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable,InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'balance','state','status','level','role_lv','path',
        'pid','phone','is_member','invite_code','id_card'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->getDateFormat());
    }

    public function children()
    {
        return $this->hasMany(static::class, 'pid');
    }

    public function createChildren($number = 1)
    {
        return Collection::times($number, function () {
            $user = $this->children()->forceCreate([
                'name' => fake()->unique()->name,
                'id_card' => fake()->unique()->creditCardNumber,
                'level' => $this->level+1,
                'role_lv' => 0,
                'is_member' => 0,
                'phone' => fake()->unique()->phoneNumber,
                'email' => fake()->unique()->email,
                'password' => fake()->password,
                'invite_code' => Str::random(),
                'status' => 1,
            ]);
            $user->path = $this->path.$user->id.'-';
            $user->save();
            return $user;
        });
    }

    public function deleteChildren()
    {
        return static::query()->where('path', 'like', "$this->path%")->delete();
    }

    public function up()
    {
        $this->selfUp();
        Role::make()->upgrade($this);
        return $this;
    }

    public function memberChildren()
    {
        return $this->children()->where('is_member', 1);
    }


    public function selfUp()
    {
        $this->is_member = 1;
        $this->save();
        return $this;
    }

    public function parent()
    {
        return $this->belongsTo(static::class,'pid');
    }

    public function scopeChildrenByRoleLv(Builder $builder,$lv)
    {
        $builder->memberChildren()->where('role_lv',$lv);
    }

    public function childrenWithTeam()
    {
        return $this->memberChildren()->where('role_lv',TeamLeader::LV);
    }

    public function childrenWithDepartment()
    {
        return $this->memberChildren()->where('role_lv',DepartmentLeader::LV);
    }

    public function childrenWithRegion()
    {
        return $this->memberChildren()->where('role_lv',RegionLeader::LV);
    }

    public function childrenWithReginBoss()
    {
        return $this->memberChildren()->where('role_lv',RegionBoss::LV);
    }

    public function schema()
    {
        return Role::make()->schema($this->role_lv);
    }

    public function reward()
    {
        return $this->hasMany(Reward::class);
    }

    public function give()
    {
        return Role::make()->give($this);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function orderCompleted()
    {
        return $this->hasMany(Order::class)->where('status',1);
    }

    public function withdraw()
    {
        return $this->hasMany(Withdraw::class);
    }

    public function withdrawing(array $data)
    {
        $withdraw = $this->withdraw()->create($data);
        $this->increment('freeze', $withdraw->amount);
        return $this;
    }

    public function userRoute()
    {
        return $this->hasMany(UserRoute::class);
    }

    public function userRouteFinished()
    {
        return $this->hasMany(UserRoute::class)->where('status', 1);
    }

    public function canWithdraw($amount)
    {
        return bcsub($this->balance, $this->freeze, 2) >= $amount;
    }

    public function freezeRelesea($amount)
    {
        $this->decrement('freeze', $amount);
    }

    public static function getInviteCode()
    {
        $code = random_int(10000000, 99999999);
        if (static::where('invite_code', $code)->exists()) {
            return static::getInviteCode();
        }
        return $code;
    }
}
