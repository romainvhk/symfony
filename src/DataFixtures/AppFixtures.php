<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;


class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $doctrine;
    private $faker;
    private $hasher;
    private $manager;

    public static function getGroups(): array
    {
        return ['prod'];
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
        $manager->flush();
        $this->loadUserAdmin();
    }

    public function loadUserAdmin(): void {
        $datas = [
            [
                'email' => 'admin@example.com',
                'role' => ['ROLE_ADMIN'],
                'password' => '123',
            ]
        ];

        foreach ($datas as $data) {
            $user = new User();

            $user->setEmail($data['email']);
            $user->setRoles($data['role']);
            $user->setPassword($this->hasher->hashPassword($user, $data['password']));

            $this->manager->persist($user);
        };

        $this->manager->flush();
    }
}
