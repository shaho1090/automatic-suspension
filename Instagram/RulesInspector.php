<?php


namespace App\Modules\AutomaticSuspension\Instagram;


use App\Integration as V0Integration;
use App\Models\Integration as V1Integration;
use App\Models\v2\Integration;
use App\Models\v2\Integration as V2Integration;
use App\Models\v2\Setting;
use App\Modules\AutomaticSuspension\Instagram\Rules\Status;
use App\Traits\ElasticUpdater;

class RulesInspector
{
    use ElasticUpdater;

    const CACHE_SETTING_KEY = 'instagram_suspension_settings';

    /**
     * @var string[]
     */
    private array $rules;

    public function __construct()
    {
        $this->rules = (new Rules())->list();
    }

    /**
     * @param V0Integration|V1Integration|V2Integration $integration
     * @throws \Exception
     */
    public function handle($integration)
    {
        $settings = Setting::query()->where('integration_platform', 'instagram')->get();

        cache()->add(self::CACHE_SETTING_KEY, $settings, 1800);

        foreach ($this->rules as $rule) {
            $ruleObject = new $rule($integration);

            if ($ruleObject->isViolated()) {
                $this->updateRelatedFields($integration, $ruleObject->getDescription());

                if ($ruleObject instanceof Status){
                    $this->changeQCStatusToTemporarySuspend($integration);
                }

                break;
            }
        }
    }

    private function changeQCStatusToTemporarySuspend($integration)
    {
        $integration->update([
            'qc_status' => Integration::QC_TEMPORARY_SUSPEND,
        ]);

        $this->updateElasticDoc($integration, [
            'qc_status' => Integration::QC_TEMPORARY_SUSPEND
        ]);
    }

    private function updateRelatedFields($integration, $description)
    {
        $integration->update([
            'suspended_by_automation' => 1,
            'automatic_suspension_reason' => $description
        ]);

        $this->updateElasticDoc($integration, [
            'suspended_by_automation' => 1,
            'automatic_suspension_reason' => $description
        ]);
    }
}
