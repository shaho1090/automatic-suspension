<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules;


use App\Integration as V0Integration;
use App\Models\Integration as V1Integration;
use App\Models\v2\Integration as V2Integration;

interface RuleInterface
{
    /**
     * @param V0Integration|V1Integration|V2Integration $integration
     */
    public function __construct($integration);

    /**
     * @return bool
     */
    public function isViolated(): bool;

    /*
     * get description for suspension
     */
    public function getDescription(): string;
}
