<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules;


use App\Modules\AutomaticSuspension\Instagram\Rules\BioCharAndFollowers\BioChar;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;

class BioCharAndFollowers implements RuleInterface
{
    private BioChar $bioCharRule;
    private Followers $followers;

    /**
     * BioCharAndFollowers constructor.
     * @param $integration
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws InvalidArgumentException
     */
    public function __construct($integration)
    {
        $this->bioCharRule = new BioChar($integration);
        $this->followers = new Followers($integration);
    }

    public function isViolated(): bool
    {
       return $this->followers->isViolated() && $this->bioCharRule->isViolated();
    }

    public function getDescription(): string
    {
        return $this->bioCharRule->getDescription()." And, ".$this->followers->getDescription();
    }
}
