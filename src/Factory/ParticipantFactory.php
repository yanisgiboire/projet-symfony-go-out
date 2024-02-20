<?php

namespace App\Factory;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Participant>
 *
 * @method        Participant|Proxy                     create(array|callable $attributes = [])
 * @method static Participant|Proxy                     createOne(array $attributes = [])
 * @method static Participant|Proxy                     find(object|array|mixed $criteria)
 * @method static Participant|Proxy                     findOrCreate(array $attributes)
 * @method static Participant|Proxy                     first(string $sortedField = 'id')
 * @method static Participant|Proxy                     last(string $sortedField = 'id')
 * @method static Participant|Proxy                     random(array $attributes = [])
 * @method static Participant|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ParticipantRepository|RepositoryProxy repository()
 * @method static Participant[]|Proxy[]                 all()
 * @method static Participant[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Participant[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Participant[]|Proxy[]                 findBy(array $attributes)
 * @method static Participant[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Participant[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ParticipantFactory extends ModelFactory
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
            'active' => self::faker()->boolean(),
            'email' => self::faker()->email(),
            'firstName' => self::faker()->firstName(),
            'phoneNumber' => self::faker()->phoneNumber(1),
            'surname' => self::faker()->name(50),
            'site' => SiteFactory::random(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Participant $participant): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Participant::class;
    }
}
