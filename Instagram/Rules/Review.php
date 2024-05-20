<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules;

use App\Models\v2\Review as ModelReview;

class Review extends AbstractSingleRule implements RuleInterface
{

    public function setSettingKey(): void
    {
        $this->settingKey = 'suspension_all_reviews_count';
    }

    /**
     * @return bool
     */
    public function isViolated(): bool
    {
        $query = $this->integration->reviews()
            ->where('status', ModelReview::APPROVED)
            ->whereNotNull('code_used_at');

        if((clone $query)->count() <= 4){
            return false;
        }

        $rawSql = sprintf(
            "((performance + communication + rules_observation) / 3) < %.1f",
            $this->setting->value ?? 2.5
        );

        $badReviews = (clone $query)->whereRaw($rawSql)->get();

        if (!is_null($badReviews)) {
            return true;
        }

        return false;
    }

    public function getDescription(): string
    {
        return 'Accumulation an abnormal number of bad reviews.';
    }
}
