<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    // injecter la classe de cryptage dans le service
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * Ajouter 2 admins :
     * boss@mmi.edu / boss en ROLE_ADMIN
     * marcel@mmi.edu / bouzigue en ROLE_WRITER
     * * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        // un boss@gmail.com / boss en ROLE_ADMIN
        $user = new User();
        $user->setUsername('boss@mmi.edu')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordEncoder->encodePassword($user, 'boss'));
        $manager->persist($user);
        // un writer@gmail.com / writer en ROLE_WRITER
        $user = new User();
        $user->setUsername('marcel@mmi.edu')
            ->setRoles(['ROLE_EDITOR'])
            ->setPassword($this->passwordEncoder->encodePassword($user, 'bouzigue'));
        $manager->persist($user);

        $manager->flush();
    }
}