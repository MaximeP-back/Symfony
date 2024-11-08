<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Conference;
use App\Entity\Admin;
use App\Entity\Comment;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{
    public function __construct(private PasswordHasherFactoryInterface $passwordHasherFactory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $chicago = new Conference();
        $chicago->setCity('Chicago');
        $chicago->setYear('2020');
        $chicago->setIsInternational(true);
        $manager->persist($chicago);

        $admin = new Admin();
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setUsername("admin");
        $admin->setPassword($this->passwordHasherFactory->getPasswordHasher(Admin::class)->hash('admin'));
        $manager->persist($admin);

        $comment = new Comment();
        $comment->setAuthor('Testerboy');
        $comment->setText('TestComment');
        $comment->setEmail('test@test.fr');
        $comment->setPhotoFilename('test');
        $comment->setConference($chicago);
        $comment->setState('published');
        $comment->setCreatedAt(new \DateTime());
        $manager->persist($comment);

        $manager->flush();
    }
}