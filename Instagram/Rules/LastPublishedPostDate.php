<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules;


use App\Traits\ElasticHttpClient;
use App\Traits\GetInfluencerData;
use Carbon\Carbon;

class LastPublishedPostDate extends AbstractSingleRule implements RuleInterface
{
    use ElasticHttpClient;
    use GetInfluencerData;

    public function setSettingKey(): void
    {
        $this->settingKey = 'suspension_last_published_post_date';
    }

    /**
     * @return bool
     */
    public function isViolated(): bool
    {
        $elasticResult = $this->getInfluencerDataById($this->integration->id,['posts.created_at']);

        if(is_null($elasticResult) || !isset($elasticResult['posts.created_at'])){
            return false;
        }

        $spanTime = Carbon::today()->subDays($this->setting->value);

        return Carbon::createFromTimestamp(max($elasticResult['posts.created_at'])/1000)
            ->isBefore($spanTime);
    }

    public function getDescription(): string
    {
        return 'The last content posted on the Instagram page is older than '.$this->setting->value .' days ago';
    }
}
