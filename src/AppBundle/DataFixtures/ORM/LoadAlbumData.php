<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Album;
use AppBundle\Entity\Image;

/**
 * Class LoadAlbumData
 * Fills Image storage and related Album storage from the Internet
 * @author Igor Cherkashin aka JiSoft <jisoft.dn@gmail.com>
 */
class LoadAlbumData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {


    }
}