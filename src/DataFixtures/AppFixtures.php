<?php

namespace App\DataFixtures;

use App\Entity\TodoItem;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < 20; $i++) {        
            $todoItem = new TodoItem();
            $todoItem->setCategory($this->getReference("Catégorie n $i"));
            $todoItem->setTitle("Todo n $i");
            $todoItem->setcontent("Logoden biniou degemer. Ken rev diouzh. Askorn santout broustañ. Aradon horolaj eizh. Trubard redek benveg. Karrez e ugent. Chom enor da. Anezhi Pederneg kempenn. Gallek geot padout. Pomper Santez-Anna-Wened asied.i");
            $todoItem->setIsDone(false);
            $todoItem->setCreatedAt(new \DateTimeImmutable());
            $todoItem->setDoneAt(null);
            

            $manager->persist($todoItem);
        }
        $manager->flush();
        
    }
}
