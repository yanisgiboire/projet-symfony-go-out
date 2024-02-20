<?php

namespace App\Factory;

use App\Entity\GoOut;
use App\Entity\Status;
use App\Repository\GoOutRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<GoOut>
 *
 * @method        GoOut|Proxy                     create(array|callable $attributes = [])
 * @method static GoOut|Proxy                     createOne(array $attributes = [])
 * @method static GoOut|Proxy                     find(object|array|mixed $criteria)
 * @method static GoOut|Proxy                     findOrCreate(array $attributes)
 * @method static GoOut|Proxy                     first(string $sortedField = 'id')
 * @method static GoOut|Proxy                     last(string $sortedField = 'id')
 * @method static GoOut|Proxy                     random(array $attributes = [])
 * @method static GoOut|Proxy                     randomOrCreate(array $attributes = [])
 * @method static GoOutRepository|RepositoryProxy repository()
 * @method static GoOut[]|Proxy[]                 all()
 * @method static GoOut[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static GoOut[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static GoOut[]|Proxy[]                 findBy(array $attributes)
 * @method static GoOut[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static GoOut[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class GoOutFactory extends ModelFactory
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
            'description' => self::faker()->text(255),
            'duration' => self::faker()->randomNumber(),
            'limitDateInscription' => self::faker()->dateTime(),
            'maxNbInscriptions' => self::faker()->randomNumber(),
            'name' => self::faker()->text(50),
            'startDateTime' => self::faker()->dateTime(),
            'status' => StatusFactory::random(),
            'place' => PlaceFactory::random(),
            'site' => SiteFactory::random(),
            'participant' => ParticipantFactory::random(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(GoOut $goOut): void {})
        ;
    }

    protected static function getClass(): string
    {
        return GoOut::class;
    }
}