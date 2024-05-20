<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules\BioCharAndFollowers;



use App\Modules\AutomaticSuspension\Instagram\Rules\AbstractSingleRule;
use App\Modules\AutomaticSuspension\Instagram\Rules\RuleInterface;
use App\Traits\ElasticHttpClient;
use App\Traits\GetInfluencerData;

class BioChar extends AbstractSingleRule implements RuleInterface
{
    use ElasticHttpClient;
    use GetInfluencerData;

    public function setSettingKey(): void
    {
        $this->settingKey = 'suspension_bio_char_and_followers-bio-char-count';
    }

    /**
     * @return bool
     */
    public function isViolated(): bool
    {
        $elasticResult = $this->getInfluencerDataById($this->integration->id,['description']);

        if ($this->setting->operator == '<') {
            return strlen($elasticResult['description'][0]) < $this->setting->value;
        }

        return false;
    }

    public function getDescription(): string
    {
        return 'The length of the Bio is less than '.$this->setting->value.'.';
    }
}
