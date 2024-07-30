<?php

namespace App\Packages\Schema;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeamLeader extends Repository
{
    public const LV = 1;
    public const RATE = 0.08;

    public function name()
    {
        return '团队主管';
    }
    public function pass():bool
    {
        $parent = $this->user->parent;
        if (!$parent) return false;
        return $this->directPass($parent) && $this->indirectPass($parent);
    }

    public function directPass(User $user,$number = 5)
    {
        return $user->loadCount('memberChildren')->member_children_count >= $number;
    }

    public function indirectPass(User $user,$direct_num = 2,$indirect_number = 5)
    {
        return $user->load(['memberChildren' => function(HasMany $hasMany)use($indirect_number){
                $hasMany->has('memberChildren','>=',$indirect_number);
            }])->memberChildren->count() >= $direct_num;
    }


}
