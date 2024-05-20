<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules;

use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class Status
 * @package App\Modules\AutomaticSuspension\Instagram\Rules
 *
 * at the time this class was created,
 * it is for checking status whether it is public or private
 * in the integration table.
 */
class Status extends AbstractSingleRule implements RuleInterface
{
    public function setSettingKey(): void
    {
        $this->settingKey = 'suspension_account_status';
    }

    /**
     * @return bool
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public function isViolated(): bool
    {
        if ($this->setting->operator == '=') {
            return strtolower($this->integration->status) == strtolower($this->setting->value);
        }

        return false;
    }

    public function getDescription(): string
    {
        return 'The account status must be public.';
    }
}
