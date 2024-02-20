<?php

namespace App\Factory;

use App\Entity\ParticipantGoOut;
use App\Repository\ParticipantGoOutRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ParticipantGoOut>
 *
 * @method        ParticipantGoOut|Proxy                     create(array|callable $attributes = [])
 * @method static ParticipantGoOut|Proxy                     createOne(array $attributes = [])
 * @method static ParticipantGoOut|Proxy                     find(object|array|mixed $criteria)
 * @method static ParticipantGoOut|Proxy                     findOrCreate(array $attributes)
 * @method static ParticipantGoOut|Proxy                     first(string $sortedField = 'id')
 * @method static ParticipantGoOut|Proxy                     last(string $sortedField = 'id')
 * @method static ParticipantGoOut|Proxy                     random(array $attributes = [])
 * @method static ParticipantGoOut|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ParticipantGoOutRepository|RepositoryProxy repository()
 * @method static ParticipantGoOut[]|Proxy[]                 all()
 * @method static ParticipantGoOut[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ParticipantGoOut[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ParticipantGoOut[]|Proxy[]                 findBy(array $attributes)
 * @method static ParticipantGoOut[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ParticipantGoOut[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ParticipantGoOutFactory extends ModelFactory
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
            'goOut' => GoOutFactory::random(),
            'participant' => ParticipantFactory::random(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(ParticipantGoOut $participantGoOut): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ParticipantGoOut::class;
    }
}
