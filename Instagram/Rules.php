<?php


namespace App\Modules\AutomaticSuspension\Instagram;


use App\Modules\AutomaticSuspension\Instagram\Rules\BioCharAndFollowers;
use App\Modules\AutomaticSuspension\Instagram\Rules\Followers;
use App\Modules\AutomaticSuspension\Instagram\Rules\FollowingAndRatio;
use App\Modules\AutomaticSuspension\Instagram\Rules\Invitation;
use App\Modules\AutomaticSuspension\Instagram\Rules\LastPublishedPostDate;
use App\Modules\AutomaticSuspension\Instagram\Rules\OfferSending;
use App\Modules\AutomaticSuspension\Instagram\Rules\PublishedJob;
use App\Modules\AutomaticSuspension\Instagram\Rules\QualityScoreAndFollowers;
use App\Modules\AutomaticSuspension\Instagram\Rules\Review;
use App\Modules\AutomaticSuspension\Instagram\Rules\SpammingRate;
use App\Modules\AutomaticSuspension\Instagram\Rules\Status;

class Rules
{
    public function list(): array
    {
        return [
            Status::class,
            Followers::class,
            LastPublishedPostDate::class,
            BioCharAndFollowers::class,
            FollowingAndRatio::class,
            QualityScoreAndFollowers::class,
            PublishedJob::class,
            SpammingRate::class,
            Review::class,
            Invitation::class,
//            OfferSending::class
        ];
    }
}
