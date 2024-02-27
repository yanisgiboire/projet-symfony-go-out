<?php

namespace App\DataFixtures;

use App\Command\CheckExpiredGoOutCommand;
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

    public function __construct(private CheckGoOutStatusService $checkGoOutStatusService)
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        SiteFactory::createMany(5);

        StatusFactory::createOne(['libelle' => 'Ouverte']);
        StatusFactory::createOne(['libelle' => 'Clôturée']);
        StatusFactory::createOne(['libelle' => 'Activité en cours']);
        StatusFactory::createOne(['libelle' => 'passée']);
        StatusFactory::createOne(['libelle' => 'Annulée']);
        StatusFactory::createOne(['libelle' => 'Archivée']);

        CityFactory::createMany(10);
        PlaceFactory::createMany(10);

        $admin = UserFactory::createOne(['username' => 'admin', 'password' => 'admin', 'roles' => ['ROLE_ADMIN']]);
        $user = UserFactory::createOne(['username' => 'user', 'password' => 'user', 'roles' => ['ROLE_USER']]);
        UserFactory::createMany(30);

//        GoOutFactory::createMany(50);
        // sortie archivée
        $date = GoOutFactory::faker()->dateTimeBetween('-3month', '-2month');
        GoOutFactory::createOne([
            'startDateTime' => $date,
            'limitDateInscription' => (clone $date)->modify('-2 weeks'),
            'duration' => 50,
        ]);
        // sortie en cours
        $date = new \DateTime();
        GoOutFactory::createOne([
            'startDateTime' => $date,
            'limitDateInscription' => (clone $date)->modify('-2 weeks'),
            'duration' => 9999,
        ]);
        // sortie passé
        $date = GoOutFactory::faker()->dateTimeBetween('-1month', '-1day');
        GoOutFactory::createOne([
            'startDateTime' => $date,
            'limitDateInscription' => (clone $date)->modify('-2 weeks'),
            'duration' => 1,
        ]);
        // sortie annulée
        $date = new \DateTime();
        GoOutFactory::createOne([
            'startDateTime' => $date,
            'limitDateInscription' => (clone $date)->modify('-2 weeks'),
            'duration' => 60,
            'status' => StatusFactory::find(['libelle' => 'Annulée']),
        ]);
        // sortie ouverte
        $date = GoOutFactory::faker()->dateTimeBetween('+2week', '+2month');
        GoOutFactory::createOne([
            'startDateTime' => $date,
            'limitDateInscription' => (clone $date)->modify('-2 weeks'),
            'duration' => 120,
        ]);

        // sortie ouverte dont je suis l'organisateur
        $date = GoOutFactory::faker()->dateTimeBetween('+2week', '+2month');
        GoOutFactory::createOne([
            'name' => 'je suis organisateur',
            'startDateTime' => $date,
            'limitDateInscription' => (clone $date)->modify('-1 weeks'),
            'duration' => 120,
            'participant' => $user->getParticipant()
        ]);

        // Création d'inscription
        ParticipantGoOutFactory::createMany(10);

        // Sortie qui arrive soon et inscription rempli
        $date = GoOutFactory::faker()->dateTimeBetween('+2week', '+2month');
        $sortiePleine = GoOutFactory::createOne([
            'name' => 'Rempli à craqué',
            'startDateTime' => $date,
            'limitDateInscription' => (clone $date)->modify('-1 weeks'),
            'duration' => 60,
            'status' => StatusFactory::find(['libelle' => 'Ouverte']),
            'maxNbInscriptions' => 2
        ]);
        ParticipantGoOutFactory::createOne([
            'goOut' => $sortiePleine,
            'participant' => ParticipantFactory::random(),
        ]);
        ParticipantGoOutFactory::createOne([
            'goOut' => $sortiePleine,
            'participant' => ParticipantFactory::random(),
        ]);

        $this->checkGoOutStatusService->checkGoOutStatus();
    }

}
