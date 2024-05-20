<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules\FollowingAndRatio;


use App\Modules\AutomaticSuspension\Instagram\Rules\AbstractSingleRule;
use App\Modules\AutomaticSuspension\Instagram\Rules\RuleInterface;

class Ratio extends AbstractSingleRule implements RuleInterface
{

    /**
     *
     */
    public function setSettingKey(): void
    {
        $this->settingKey = 'suspension_following_and_ratio-ratio';
    }

    /**
     * @return bool
     */
    public function isViolated(): bool
    {
        if ($this->setting->operator == '<') {
            return $this->getRatio() < $this->setting->value;
        }

        return false;
    }

    /**
     * @return float|int
     */
    private function getRatio()
    {
        $followings = $this->integration->followings;

        if($followings == 0){
            $followings = 1;
        }

       return  $this->integration->followers / $followings;
    }

    public function getDescription(): string
    {
        return 'Related to following / followers ratio.';
    }
}
