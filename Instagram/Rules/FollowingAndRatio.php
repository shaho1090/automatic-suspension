<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules;


use App\Modules\AutomaticSuspension\Instagram\Rules\FollowingAndRatio\Following;
use App\Modules\AutomaticSuspension\Instagram\Rules\FollowingAndRatio\Ratio;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;

class FollowingAndRatio implements RuleInterface
{
    private Following $following;
    private Ratio $ratio;

    /**
     * FollowingAndRatio constructor.
     * @param $integration
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws InvalidArgumentException
     */
    public function __construct($integration)
    {
        $this->following = new following($integration);
        $this->ratio = new Ratio($integration);
    }

    /**
     * @return bool
     */
    public function isViolated(): bool
    {
        return $this->following->isViolated() && $this->ratio->isViolated();
    }

    public function getDescription(): string
    {
        return 'The followers to following ratio does not adhere to our marketplace.';
    }
}
