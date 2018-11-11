<?php
/**
 * Created by IntelliJ IDEA.
 * User: george
 * Date: 11.11.18
 * Time: 13:29
 */

namespace App\Fixture;


use App\Entity\Controller;
use App\Entity\Sensor;
use App\Entity\SensorType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SensorFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $controller1 = $manager->find(Controller::class, 1);
        $controller2 = $manager->find(Controller::class, 2);
        $temperatureSensorType = new SensorType(1, 'датчик температуры');
        $manager->persist($temperatureSensorType);
        $currentSensorType = new SensorType(2, 'датчик тока');
        $manager->persist($currentSensorType);
        $angleSensorType = new SensorType(3, 'инклинометр');
        $manager->persist($angleSensorType);
        $vibrationSensorType = new SensorType(4, 'датчик вибрации');
        $manager->persist($vibrationSensorType);
        $manager->flush();
        $sensor = new Sensor($temperatureSensorType, $controller1);
        $manager->persist($sensor);
        $sensor = new Sensor($vibrationSensorType, $controller1);
        $manager->persist($sensor);
        $sensor = new Sensor($currentSensorType, $controller1);
        $manager->persist($sensor);
        $sensor = new Sensor($angleSensorType, $controller2);
        $manager->persist($sensor);
        $sensor = new Sensor($vibrationSensorType, $controller2);
        $manager->persist($sensor);
        $sensor = new Sensor($currentSensorType, $controller2);
        $manager->persist($sensor);
        $manager->flush();
        $manager->clear();
    }

    public function getDependencies(): array {
        return [
            ControllerFixture::class,
        ];
    }

}