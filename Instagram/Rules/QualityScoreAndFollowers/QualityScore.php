<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules\QualityScoreAndFollowers;


use App\Modules\AutomaticSuspension\Instagram\Rules\AbstractSingleRule;
use App\Modules\AutomaticSuspension\Instagram\Rules\RuleInterface;
use App\Traits\ElasticHttpClient;
use App\Traits\GetInfluencerData;

class QualityScore extends AbstractSingleRule implements RuleInterface
{
//    use ElasticHttpClient;
    use GetInfluencerData;

    public function setSettingKey(): void
    {
        $this->settingKey = 'suspension_quality_score_and_followers-quality-score';
    }

    /**
     * @return bool
     */
    public function isViolated(): bool
    {
        $elasticResult = $this->getInfluencerDataById($this->integration->id,['quality_score']);

        if(is_null($elasticResult)){
            return false;
        }

        if ($this->setting->operator == '<') {
            return (100 * $elasticResult['quality_score'][0]) < $this->setting->value;
        }

        return false;
    }

    public function getDescription(): string
    {
        return 'Low quality score.';
    }
}
