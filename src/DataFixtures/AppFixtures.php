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
use App\Service\CheckGoOutStatusService;
use App\Service\StatusService;
use Faker\Factory;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{

    public function __construct(private CheckGoOutStatusService $checkGoOutStatusService)
    {
    }

    public function load(ObjectManager $manager): void
    {
        SiteFactory::createMany(5);
        UserFactory::createOne(['username' => 'admin', 'password' => 'admin', 'roles' => ['ROLE_ADMIN']]);
        UserFactory::createOne(['username' => 'user', 'password' => 'user', 'roles' => ['ROLE_USER']]);
        UserFactory::createMany(48);

        CityFactory::createMany(10);
        PlaceFactory::createMany(10);

        StatusFactory::createOne(['libelle' => Status::STATUS_CREATED]);
        StatusFactory::createOne(['libelle' => Status::STATUS_OPENED]);
        StatusFactory::createOne(['libelle' => Status::STATUS_CLOSED]);
        StatusFactory::createOne(['libelle' => Status::STATUS_ACTIVITY_IN_PROGRESS]);
        StatusFactory::createOne(['libelle' => Status::STATUS_PASSED]);
        StatusFactory::createOne(['libelle' => Status::STATUS_CANCELED]);
        StatusFactory::createOne(['libelle' => Status::STATUS_ARCHIVED]);

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
            'status' => StatusFactory::find(['libelle' => Status::STATUS_CANCELED]),
        ]);

        GoOutFactory::createMany(20);

        ParticipantGoOutFactory::createMany(10);

        $this->checkGoOutStatusService->checkGoOutStatus();
    }

}
