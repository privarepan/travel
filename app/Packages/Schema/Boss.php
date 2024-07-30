<?php

namespace App\Packages\Schema;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Boss extends Repository
{
    public const LV = 5;
    public const RATE = 0.03;

    public function name()
    {
        return '公司合伙人';
    }
    public function pass():bool
    {
        $parent = $this->user->parent;
        if (!$parent) return false;
        return $this->directPass($parent)
//            && $this->indirectPass($parent)
            ;
    }

    public function directPass(User $user,$number = 5,$direct_num = 3)
    {
        $user->loadCount('memberChildren','childrenWithReginBoss');
        return
//            $user->member_children_count >= $number &&
            $user->children_with_regin_boss_count >= $direct_num;
    }

    public function indirectPass(User $user,$direct_num = 2,$indirect_number = 5)
    {
        return $user->load(['memberChildren' => function(HasMany $hasMany)use($indirect_number){
                $hasMany->has('memberChildren','>=',$indirect_number);
            }])->memberChildren->count() >= $direct_num;
    }
}
