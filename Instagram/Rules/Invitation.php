<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules;

use App\Models\v2\Invitation as InvitationModel;
use App\Models\v2\Offer;

class Invitation extends AbstractSingleRule implements RuleInterface
{
    public function setSettingKey(): void
    {
        $this->settingKey = 'suspension_ignored_invitation_count';
    }

    /**
     * find identity of the integration in identity_integration table
     * invitation->dm_status or invitation->email_status = success
     * offers where ad_id == invitation->ad_id and identity_id == invitation->target_id
     * @return bool
     */
    public function isViolated(): bool
    {
        $identity = $this->integration->identities()->first();

        if (is_null($identity)) {
            return false;
        }

        $notRespondedCounter = 0;

        $Offers = Offer::query()->where('identity_id', $identity->id)->get();

        $invitations = InvitationModel::query()->where('target_id', $identity->id)->where(function ($query) {
            $query->orWhere('dm_status', InvitationModel::SUCCESS)
                ->orWhere('email_status', InvitationModel::SUCCESS);
        })->get();

        if ($invitations->count() < $this->setting->value) {
            return false;
        }

        foreach ($invitations as $invitation) {
            $offer = $Offers->where('ad_id', $invitation->ad_id)->first();

            if (is_null($offer)) {
                $notRespondedCounter++;
            } else {
                $notRespondedCounter = 0;
            }

            if ($notRespondedCounter >= $this->setting->value) {
                return true;
            }
        }

        return false;
    }

    public function getDescription(): string
    {
        return 'Response rate to brand invitations is low';
    }
}
