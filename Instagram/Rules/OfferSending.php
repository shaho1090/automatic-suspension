<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules;


use Carbon\Carbon;

class OfferSending extends AbstractSingleRule implements RuleInterface
{
    const MONTHS_COUNT_FOR_CHECKING = 3;

    public function setSettingKey(): void
    {
        $this->settingKey = 'suspension_offer_sending_per_month';
    }

    /**
     * @return bool
     */
    public function isViolated(): bool
    {
        $offers = $this->integration->offers()
            ->selectRaw('monthname(created_at) month, count(*) offer_count')
            ->groupBy('month')
            ->orderBy('month')
            ->take(self::MONTHS_COUNT_FOR_CHECKING)
            ->get();

        return (
            Carbon::parse($this->integration->created_at)
                ->isBefore(now()->subMonths(self::MONTHS_COUNT_FOR_CHECKING))
            &&
            ($offers->sum('offer_count') < ($this->setting->value * self::MONTHS_COUNT_FOR_CHECKING)));
    }

    public function getDescription(): string
    {
        return 'The number of offer sent per month is too low.';
    }
}
