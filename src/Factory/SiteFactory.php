<?php

namespace App\Factory;

use App\Entity\Site;
use App\Repository\SiteRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Site>
 *
 * @method        Site|Proxy                     create(array|callable $attributes = [])
 * @method static Site|Proxy                     createOne(array $attributes = [])
 * @method static Site|Proxy                     find(object|array|mixed $criteria)
 * @method static Site|Proxy                     findOrCreate(array $attributes)
 * @method static Site|Proxy                     first(string $sortedField = 'id')
 * @method static Site|Proxy                     last(string $sortedField = 'id')
 * @method static Site|Proxy                     random(array $attributes = [])
 * @method static Site|Proxy                     randomOrCreate(array $attributes = [])
 * @method static SiteRepository|RepositoryProxy repository()
 * @method static Site[]|Proxy[]                 all()
 * @method static Site[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Site[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Site[]|Proxy[]                 findBy(array $attributes)
 * @method static Site[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Site[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class SiteFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->text(50),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Site $site): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Site::class;
    }
}
