<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules;


class SpammingRate extends AbstractSingleRule implements RuleInterface
{

    const HIGH_SPAMMING_RATE_ID = 4;

    public function setSettingKey(): void
    {
       $this->settingKey = 'suspension_high_spamming_rate';
    }

    /**
     * @return bool
     */
    public function isViolated(): bool
    {
        $suspensionDetails = $this->integration->suspensionDetails()->withTrashed()->get();

        if(is_null($suspensionDetails)){
            return false;
        }

        $counter = 0;

        foreach($suspensionDetails as $item){
            if(in_array(self::HIGH_SPAMMING_RATE_ID,json_decode($item->reason_ids,true))){
                $counter++;
            }
        }

        return $counter > $this->setting->value;
    }

    public function getDescription(): string
    {
        return 'High spamming rate.';
    }
}
