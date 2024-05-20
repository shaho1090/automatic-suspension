<?php


namespace App\Modules\AutomaticSuspension\Instagram\Rules;

use App\Integration as V0Integration;
use App\Models\Integration as V1Integration;
use App\Models\v2\Integration as V2Integration;
use App\Models\v2\Setting;
use App\Modules\AutomaticSuspension\Instagram\RulesInspector;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;

abstract class AbstractSingleRule
{
    protected $integration;
    protected string $settingKey;

    /**
     * @var Model|Setting|null
     */
    protected $setting;

    /**
     * @var Builder
     */
    protected Builder $query;

    /**
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws NotFoundExceptionInterface
     * @param V0Integration|V1Integration|V2Integration $integration
     */
    public function __construct($integration)
    {
        $this->integration = $integration;
        $this->setting = null;
        $this->query = Setting::query()->where('integration_platform', 'instagram');
        $this->setSettingKey();
        $this->setSettingFromCacheOrDatabase();
    }

    abstract public function setSettingKey(): void;

    /**
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws NotFoundExceptionInterface
     * @throws \Exception
     */
    protected function setSettingFromCacheOrDatabase(): void
    {
        if (cache()->has(RulesInspector::CACHE_SETTING_KEY)) {
            $this->setting = cache()->get(RulesInspector::CACHE_SETTING_KEY)->where('name', $this->settingKey)->first();
            return;
        }

        $this->setting = $this->query->where('name', $this->settingKey)->first();
    }
}
