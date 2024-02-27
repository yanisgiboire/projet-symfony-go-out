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
        // Générer une date de début aléatoire
        $startDateTime = self::faker()->dateTimeBetween('2023-01-01', '+1 years');
        // Générer une date de clôture un mois avant la date de début
        $limitDateInscription = (clone $startDateTime)->modify('-'.self::faker()->numberBetween(10, 60).' day');
        // génération de l'état
        $defaultState = StatusFactory::find(['libelle' => 'Ouverte']);
        $state = self::faker()->optional(0.7, StatusFactory::find(['libelle' => 'Annulée']))->passthrough($defaultState);

        return [
            'description' => self::faker()->text(255),
            'limitDateInscription' => $limitDateInscription,
            'startDateTime' => $startDateTime,
            'duration' => self::faker()->numberBetween(30, 700),
            'maxNbInscriptions' => self::faker()->numberBetween(2, 50),
            'name' => self::faker()->text(50),
            'status' => $state,
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
