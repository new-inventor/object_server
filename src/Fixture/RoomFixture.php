<?php


namespace App\Fixture;


use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RoomFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $room = new Room();
        $manager->persist($room);
        $room = new Room();
        $manager->persist($room);
        $manager->flush();
    }

}