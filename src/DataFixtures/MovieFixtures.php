<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $movie=new Movie();
        $movie->setTitle('The Dark Knight');
        $movie->setReleaseYear(2008);
        $movie->setDescription('The plot follows the vigilante Batman, police lieutenant James Gordon, and district attorney Harvey Dent, 
                                    who form an alliance to dismantle organized
                                     crime in Gotham City');
        $movie->setImagePath('https://cdn.pixabay.com/photo/2023/05/23/05/47/ai-generated-8011931_1280.png');

        // Add data to  pivot table
        $movie->addActor($this->getReference('actor_1'));
        $movie->addActor($this->getReference('actor_2'));
        

        $manager->persist($movie);

        $movie2=new Movie();
        $movie2->setTitle('Casablanca');
        $movie2->setReleaseYear(1942);
        $movie2->setDescription('Filmed and set during World War II, it focuses on an American expatriate (Bogart) who must choose between his love for a woman (Bergman) and helping her husband (Henreid).');
        $movie2->setImagePath('https://cdn.pixabay.com/photo/2018/04/17/20/53/casablanca-3328692_1280.jpg');
        
        // Add data to  pivot table
        $movie2->addActor($this->getReference('actor_3'));
        $movie2->addActor($this->getReference('actor_4'));

        $manager->persist($movie2);

        $manager->flush();

    }

}
