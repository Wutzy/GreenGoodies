<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        foreach (['Kit d\'hygiène recyclable', 'Shot Tropical', 'Gourde en bois', 'Disques Démaquillant x3', 'Bougie Lavande & Patchouli', 'Brosse à dent', 'Kit couvert en bois', 'Nécessaire, déodorant Bio', 'Savon Bio'] as $i => $name) {
            $product = new Product();
            $product->setName($name);
            $product->setPrice(mt_rand(5, 15));
            $product->setShortDescription('Produit artisanal');
            $product->setDescription('Description longue du produit…');
            $product->setImageFilename('produit'.($i+1).'.jpg');
            $manager->persist($product);
        }
        $manager->flush();
    }
}
