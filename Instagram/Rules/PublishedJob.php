<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules;


use App\Models\v2\PublishJob as PublishJobModel;

class PublishedJob extends AbstractSingleRule implements RuleInterface
{
    public function setSettingKey(): void
    {
        $this->settingKey = 'suspension_failed_publish_job_count';
    }

    /**
     * @return bool
     */
    public function isViolated(): bool
    {
        if ($this->integration->publish_jobs()->count() < 6) {
            return false;
        }

        if ($this->integration->failed_deals()->count() <= $this->integration->success_deals()->count()) {
            return false;
        }

        $publishJobs = $this->integration->publish_jobs()
            ->orderByDesc('start')
            ->get('status')
            ->toArray();

        foreach ($publishJobs as $key => $publishJob) {
            if (isset($publishJobs[$key + 1])) {
                if ($publishJob['status'] == PublishJobModel::FAILED
                    && $publishJobs[$key + 1]['status'] == PublishJobModel::FAILED) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getDescription(): string
    {
        return 'Failed to Publish Closed Deals 2 times back to back.';
    }
}
