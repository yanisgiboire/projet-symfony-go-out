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
        SiteFactory::createOne();
        UserFactory::createOne(['username' => 'admin', 'password' => 'admin', 'roles' => ['ROLE_ADMIN']]);
        UserFactory::createMany(50);
        ParticipantFactory::createMany(51);
        CityFactory::createMany(10);
        PlaceFactory::createMany(10);
        StatusFactory::createOne(['libelle' => 'Ouverte']);
        StatusFactory::createOne(['libelle' => 'Clôturée']);
        StatusFactory::createOne(['libelle' => 'Activité en cours']);
        StatusFactory::createOne(['libelle' => 'passée']);
        StatusFactory::createOne(['libelle' => 'Annulée']);

        PlaceFactory::createMany(10);

        GoOutFactory::createMany(50);
        ParticipantGoOutFactory::createMany(100);
    }

}
