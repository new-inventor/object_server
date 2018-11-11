<?php
/**
 * Created by IntelliJ IDEA.
 * User: george
 * Date: 11.11.18
 * Time: 12:47
 */

namespace App\Fixture;


use App\Entity\Controller;
use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ControllerFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $room1 = $manager->find(Room::class, 1);
        $controller1 = new Controller($room1);
        $manager->persist($controller1);
        $room2 = $manager->find(Room::class, 2);
        $controller2 = new Controller($room2);
        $manager->persist($controller2);
        $manager->flush();
    }

    public function getDependencies(): array {
        return [
            RoomFixture::class,
        ];
    }
}