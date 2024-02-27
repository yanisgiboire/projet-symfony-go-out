<?php

namespace App\DataFixtures;

use App\Entity\GoOut;
use App\Entity\Site;
use App\Entity\Status;
use App\Factory\CityFactory;
use App\Factory\GoOutFactory;
use App\Factory\ParticipantFactory;
use App\Factory\ParticipantGoOutFactory;
use App\Factory\PlaceFactory;
use App\Factory\SiteFactory;
use App\Factory\StatusFactory;
use App\Factory\UserFactory;
use Faker\Factory;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        SiteFactory::createMany(5);
        UserFactory::createOne(['username' => 'admin', 'password' => 'admin', 'roles' => ['ROLE_ADMIN']]);
        UserFactory::createOne(['username' => 'user', 'password' => 'user', 'roles' => ['ROLE_USER']]);
        UserFactory::createMany(48);

        CityFactory::createMany(10);
        PlaceFactory::createMany(10);

        StatusFactory::createOne(['libelle' => Status::class::STATUS_CREATED]);
        StatusFactory::createOne(['libelle' => Status::class::STATUS_OPENED]);
        StatusFactory::createOne(['libelle' => Status::class::STATUS_CLOSED]);
        StatusFactory::createOne(['libelle' => Status::class::STATUS_ACTIVITY_IN_PROGRESS]);
        StatusFactory::createOne(['libelle' => Status::class::STATUS_PASSED]);
        StatusFactory::createOne(['libelle' => Status::class::STATUS_CANCELED]);

        $date = GoOutFactory::faker()->dateTimeBetween('-3month', '-2month');
        GoOutFactory::createOne([
            'startDateTime' => $date,
            'limitDateInscription' => (clone $date)->modify('-2 weeks'),
            'duration' => 50,
        ]);
        $date = new \DateTime();
        GoOutFactory::createOne([
            'startDateTime' => $date,
            'limitDateInscription' => (clone $date)->modify('-2 weeks'),
            'duration' => 9999,
        ]);
        $date = GoOutFactory::faker()->dateTimeBetween('-1month', '-1day');
        GoOutFactory::createOne([
            'startDateTime' => $date,
            'limitDateInscription' => (clone $date)->modify('-2 weeks'),
            'duration' => 1,
        ]);
        GoOutFactory::createOne([
            'startDateTime' => $date,
            'limitDateInscription' => (clone $date)->modify('-2 weeks'),
            'duration' => 60,
            'status' => StatusFactory::find(['libelle' => 'Annulée']),
        ]);

        GoOutFactory::createMany(20);

        ParticipantGoOutFactory::createMany(10);
    }

}
