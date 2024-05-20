<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules;


class Followers extends AbstractSingleRule implements RuleInterface
{

    public function setSettingKey(): void
    {
        $this->settingKey = 'suspension_account_followers_count';
    }

    public function isViolated(): bool
    {
        if ($this->setting->operator == '<') {
            return $this->integration->followers < $this->setting->value;
        }

        return false;
    }

    public function getDescription(): string
    {
       return 'The number of followers should be more than '.$this->setting->value;
    }
}
