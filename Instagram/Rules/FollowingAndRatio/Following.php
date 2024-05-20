<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules\FollowingAndRatio;


use App\Modules\AutomaticSuspension\Instagram\Rules\AbstractSingleRule;
use App\Modules\AutomaticSuspension\Instagram\Rules\RuleInterface;

class Following extends AbstractSingleRule implements RuleInterface
{

    public function setSettingKey(): void
    {
        $this->settingKey = 'suspension_following_and_ratio-following';
    }

    /**
     * @return bool
     */
    public function isViolated(): bool
    {
        if ($this->setting->operator == '>') {
            return $this->integration->followings > $this->setting->value;
        }

        return false;
    }

    public function getDescription(): string
    {
        return 'The number of following is too high.';
    }
}
