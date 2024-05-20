<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules\QualityScoreAndFollowers;


use App\Modules\AutomaticSuspension\Instagram\Rules\AbstractSingleRule;
use App\Modules\AutomaticSuspension\Instagram\Rules\RuleInterface;

class Followers extends AbstractSingleRule implements RuleInterface
{
    public function setSettingKey(): void
    {
        $this->settingKey = 'suspension_quality_score_and_followers-followers';
    }

    /**
     * @return bool
     */
    public function isViolated(): bool
    {
        if ($this->setting->operator == '<') {
            return $this->integration->followers < $this->setting->value;
        }

        return false;
    }

    public function getDescription(): string
    {
        return 'The number of followers is less than '.$this->setting->value.'.';
    }
}
