<?php


namespace App\Fixture;


use App\Entity\Actuator;
use App\Entity\ActuatorType;
use App\Entity\Controller;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ActuatorFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $controller1 = $manager->find(Controller::class, 1);
        $controller2 = $manager->find(Controller::class, 2);
        $releType = new ActuatorType(1, 'Реле');
        $manager->persist($releType);
        $motorType = new ActuatorType(2, 'Мотор');
        $manager->persist($motorType);
        $manager->flush();
        $actuator = new Actuator($releType, $controller1);
        $manager->persist($actuator);
        $actuator = new Actuator($motorType, $controller1);
        $manager->persist($actuator);
        $actuator = new Actuator($releType, $controller2);
        $manager->persist($actuator);
        $actuator = new Actuator($releType, $controller2);
        $manager->persist($actuator);
        $manager->flush();
        $manager->clear();
    }

    public function getDependencies(): array
    {
        return [
            ControllerFixture::class,
        ];
    }
}