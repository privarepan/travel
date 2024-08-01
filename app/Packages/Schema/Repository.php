<?php

namespace App\Packages\Schema;

use App\Models\User;

abstract class Repository
{
    public function __construct(public User $user)
    {
    }

    abstract public function name();

    abstract function pass():bool;
    public function upgrade()
    {
        if ($this->pass()) {
            $this->user->parent->role_lv = static::LV;
            $this->user->parent->save();
            return true;
        }
        return false;
    }

    public function give(User $user,$total,Repository $oldRepository = null)
    {
        $oldRate = $oldRepository ? $oldRepository::RATE : 0;
        if ($this instanceof Boss){
            $oldRate = 0;
        }
        $original_rate = static::RATE;
        $rate = $original_rate - $oldRate;
        $amount = bcmul($rate, $total, 2);

        $this->user->giveReward([
            'phone' => $this->user->phone,
            'original_id' => $user->getKey(),
            'original_phone' => $user->phone,
            'amount' => $amount,
            'role_rate' => $original_rate,
            'role_lv' => static::LV,
            'rate' => $rate,
            'remark' => "$user->name 成功加入会员 您当前的角色为 {$this->name()} 当前分成比例为 [$original_rate] 实际分成比例为 [$rate]"
        ]);

    }

    public static function make(...$args)
    {
        return new static(...$args);
    }

    public function shouldGive(User $user)
    {
        return !$this->user->reward()->where('original_id', $user->getKey())->first();
    }
}
