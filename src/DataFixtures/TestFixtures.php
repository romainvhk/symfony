<?php

namespace App\DataFixtures;

use \DateTime;
use \DateTimeImmutable;
use App\Entity\SchoolYear;
use App\Entity\Tag;
use App\Entity\Student;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class TestFixtures extends Fixture implements FixtureGroupInterface
{
    private $doctrine;
    private $faker;
    private $hasher;
    private $manager;

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $hasher)
    {
        $this->doctrine = $doctrine;
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;

        $this->loadTags();
        $this->loadSchoolYears();
        $this->loadProjects();
        $this->loadStudents();

    }

    public function loadTags(): void {
        
        // données de test statiques
        $datas = [
            [
                'name' => 'HTML',
                'description' => null,
            ],
            [
                'name' => 'CSS',
                'description' => "Langage de programmation pour styliser",
            ],
            [
                'name' => 'JS',
                'description' => 'Langage de programmation pour rendre dynamique',
            ],
        ];

        foreach ($datas as $data) {
            // création d'un nouvel objet
            $tag = new Tag();

            // affectation des valeurs statiques
            $tag->setName($data['name']);
            $tag->setDescription($data['description']);

            // demande d'enregistrement de l'objet
            $this->manager->persist($tag);
        };

        // données de test dynamiques
        for ($i =0; $i < 10; $i++){
            
            // création d'un nouvel objet
            $tag = new Tag();

            $tag->setName(ucfirst($this->faker->word()));
            $tag->setDescription(ucfirst($this->faker->sentence()));

            // demande d'enregistrement de l'objet
            $this->manager->persist($tag);
        };

        // exécution des requêtes SQL
        $this->manager->flush();
    }

    public function loadSchoolYears(): void {

        $datas = [
            [
                'name' => 'Promo Foo Bar Baz',
                'description' => null,
                'startDate' => new DateTime('2022-01-01'), //DateTime::createFromFormat('Y-m-d', '2022-01-01')
                'endDate' => new DateTime('2022-04-30'),
            ],
            [
                'name' => 'Promo Lorem Ipsum',
                'description' => "Une promo formidable",
                'startDate' => new DateTime('2022-06-01'), 
                'endDate' => new DateTime("2022-09-30"),
            ]
        ];

        foreach ($datas as $data) {
            $schoolYear = new SchoolYear();

            $schoolYear->setName($data['name']);
            $schoolYear->setDescription($data['description']);
            $schoolYear->setStartDate($data['startDate']);
            $schoolYear->setEndDate($data['endDate']);
            
            $this->manager->persist($schoolYear);
        };

        for ($i = 0; $i < 10; $i++) {
            $schoolYear = new SchoolYear();

            $schoolYear->setName("Promo ".$this->faker->word(2));
            $schoolYear->setDescription($this->faker->sentence(5));
            $schoolYear->setStartDate($this->faker->dateTimeBetween('-10 week', '-6 week'));
            $schoolYear->setEndDate($this->faker->dateTimeBetween('+8 week', '+12 week'));

            $this->manager->persist($schoolYear);
        };

            $this->manager->flush();
    }

    public function loadProjects(): void {

        $repository = $this->manager->getRepository(Tag::class);
        $tags = $repository->findAll();

        $datas = [
            [
                "name" => "Maquettage",
                "description" => "null",
                "clientName" => "Foo Bar",
                "startDate" => DateTime::createFromFormat('Y-m-d', '2023-02-01'),
                "checkpointDate" => DateTime::createFromFormat('Y-m-d', '2023-03-01'),
                "deliveryDate" => DateTime::createFromFormat('Y-m-d', '2023-04-01'),
                'tag' => [$tags[0], $tags[5]],
            ],
            [
                "name" => "Student",
                "description" => "null",
                "clientName" => "Foo Bar",
                "startDate" => DateTime::createFromFormat('Y-m-d', '2023-02-01'),
                "checkpointDate" => DateTime::createFromFormat('Y-m-d', '2023-03-01'),
                "deliveryDate" => DateTime::createFromFormat('Y-m-d', '2023-04-01'),
                'tag' => [$tags[2], $tags[10]],
            ],
        ];

        foreach($datas as $data) {
            $project = new Project();

            $project->setName($data['name']);
            $project->setDescription($data['description']);
            $project->setClientName($data['clientName']);
            $project->setStartDate($data['startDate']);
            $project->setCheckpointDate($data['checkpointDate']);
            $project->setDeliveryDate($data['deliveryDate']);

            foreach($tags as $tag) {
                $project->addTag($tag);
            };

            $this->manager->persist($project);
        };

        for ($i = 0; $i < 10; $i++) {
            $project = new Project();

            $project->setName($this->faker->word());
            $project->setDescription($this->faker->sentence(5));
            $project->setClientName($this->faker->name());
            $project->setStartDate($this->faker->dateTimeBetween('-10 week', '-6 week'));
            $project->setCheckpointDate($this->faker->dateTimeBetween('+ 2 week', '+ 5 week'));
            $project->setDeliveryDate($this->faker->dateTimeBetween('+ 7 week', '+ 10 week'));

            foreach ($this->faker->randomElements($tags) as $tag) {
                $project->addTag($tag);
            };

            $this->manager->persist($project);
        };

        $this->manager->flush();
    }

    public function loadStudents(): void {

        // permet d'aller récupérer le repo d'une classe afin d'accèder aux infos
        $repository = $this->manager->getRepository(SchoolYear::class);
        $schoolYears = $repository->findAll();

        $repository = $this->manager->getRepository(Tag::class);
        $tags = $repository->findAll();

        $repository = $this->manager->getRepository(Project::class);
        $projects = $repository->findAll();


        $datas = [
            [
                'project' => $projects[0],
                'tag' => [$tags[0], $tags[1], $tags[2]],
                'role' => ['ROLE_USER'],
                "firstname" => 'Foo',
                "lastname" => 'Bar',
                'createdAt' => DateTimeImmutable::createFromFormat('Y-m-d', '2022-01-01'),
                'email' => 'foo.bar@example.com',
                'password' => '123',
                'schoolYear' => $schoolYears[0],
            ],
            [
                'project' => $projects[0],
                'tag' => [$tags[0], $tags[1], $tags[2]],
                'role' => ['ROLE_USER'],
                "firstname" => 'Baz',
                "lastname" => 'Baz',
                'createdAt' => DateTimeImmutable::createFromFormat('Y-m-d', '2022-01-02'),
                'email' => 'baz.baz@example.com',
                'password' => '123',
                'schoolYear' => $schoolYears[0],
            ],
        ];

        foreach ($datas as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $password = $this->hasher->hashPassword($user, $data['password']);
            $user->setPassword($password);
            $user->setRoles($data['role']);

            $student = new Student();
            $student->setFirstname($data['firstname']);
            $student->setLastname($data['lastname']);
            $student->setCreatedAt($data['createdAt']);
            $student->setUser($user);
            $student->setSchoolYear($data['schoolYear']);
            $student->setProject($data['project']);

            foreach ($tags as $tag) {
                $student->addTag($tag);
            }

            $this->manager->persist($student);
        };

        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email());
            $password = $this->hasher->hashPassword($user, $this->faker->sentence(5));
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $student = new Student();
            $student->setFirstname($this->faker->firstname());
            $student->setLastname($this->faker->lastname());
            $student->setCreatedAt(new DateTimeImmutable());
            $student->setUser($user);
            $student->setSchoolYear($this->faker->randomElement($schoolYears));
            $student->setProject($this->faker->randomElement($projects));

            foreach ($this->faker->randomElements($tags) as $tag) {
                $student->addTag($tag);
            };

            $this->manager->persist($student);
        };

        $this->manager->flush();
    }
}
