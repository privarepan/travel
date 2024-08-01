<?php

namespace App\Packages\Schema;

use App\Models\User;
use Illuminate\Support\Str;

class Role
{
    protected $retry = 0;
    public static $roles = [
        [
            'lv' => TeamLeader::LV,
            'schema' => TeamLeader::class,
            ],
        [
            'lv' => DepartmentLeader::LV,
            'schema' => DepartmentLeader::class,
        ],
        [
            'lv' => RegionLeader::LV,
            'schema' => RegionLeader::class,
        ],
        [
            'lv' => RegionBoss::LV,
            'schema' => RegionBoss::class,
        ],
        [
            'lv' => Boss::LV,
            'schema' => Boss::class,
        ],

    ];

    public function upgrade(User $user)
    {
        $missing = true;
        collect(static::$roles)
            ->where('lv', '>', $user->role_lv)
            ->sortByDesc('lv')
            ->pluck('schema','lv')
            ->each(function ($item)use($user,&$missing){
                $schema = $item::make($user);
                $upgraded = $schema->upgrade();
                if ($upgraded) {
                    $this->resetRetry();
                    $missing = false;
                    return false;
                }
            });

        if ($missing) {
            $this->retry++;
        }

        if ($this->canNotUpgrade()) {
            return !$missing;
        }

        if (!$user->parent) {
            return false;
        }

        return $this->upgrade($user->parent);
    }

    public function resetRetry($num = 0)
    {
        $this->retry = $num;
        return $this;
    }

    public function canUpgrade()
    {
        return $this->retry < 2;
    }

    public function canNotUpgrade()
    {
        return !$this->canUpgrade();
    }


    public static function make(...$args)
    {
        return new static(...$args);
    }

    /**
     * @param $role_lv
     * @return Repository |null
     */
    public function schema(User $user)
    {
        $schema = collect(static::$roles)->where('lv',$user->role_lv)->value('schema');
        if ($schema) {
            return $schema::make($user);
        }
        return null;
    }

    public function give(User $original,$amount = 2999)
    {
        //直推奖励
        if ($original->parent->isMember()) {
            $original->parent->directReward($original);
        }
        //间推奖励
        if ($parent = $original->parent->parent) {
            if ($parent->isMember()) {
                $parent->directReward($original);
            }
        }

        $paths = Str::of($original->parent->path)->trim('-')->explode('-');
        $current = null;
        User::whereIn('id',$paths)
            ->orderByDesc('level')
            ->get()
            ->each(function (User $user)use(&$current,$original,$amount){
                $schema = $this->schema($user);
                if (!$schema) {
                    return ;
                }
                if (!$current) {
                    $current = $schema;
                    if ($schema->shouldGive($original)) {
                        $schema->give($original,$amount);
                    }
                    return ;
                }

                if ($current::LV < $schema::LV) {
                    if ($schema->shouldGive($original)) {
                        $schema->give($original,$amount,$current);
                    }
                    $current = $schema;
                }

            });
        return $this;
    }


}
