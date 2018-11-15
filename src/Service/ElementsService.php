<?php


namespace App\Service;


use App\Entity\Element;
use Doctrine\ORM\EntityManagerInterface;

class ElementsService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ElementsService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createOrUpdateElement(int $id, $elementType, $roomId, int $parentId)
    {
        $element = $this->em->createQueryBuilder()
            ->select('e')
            ->from(Element::class, 'e')
            ->where('e.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
        if (\count($element) === 0) {
            $element = new Element($id, $elementType, $roomId, $parentId);
            $this->em->persist($element);
        } else {
            $this->em->createQueryBuilder()
                ->update(Element::class, 'op')
                ->where('op.id = \'' . $id . '\'')
                ->set('op.room_id', "'$roomId'")
                ->set('op.parent_element_id', "'$parentId'")
                ->getQuery()
                ->execute();
        }
    }
}