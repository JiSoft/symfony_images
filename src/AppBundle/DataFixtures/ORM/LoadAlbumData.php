<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Album;
use AppBundle\Entity\Image;
use AppBundle\Utils\Grabber;

/**
 * Class LoadAlbumData
 * Fills Image storage and related Album storage from the Internet
 * @author Igor Cherkashin aka JiSoft <jisoft.dn@gmail.com>
 */
class LoadAlbumData extends AbstractFixture implements FixtureInterface
{
    /** @const How many images should be at the first album */
    const START_COUNT = 5;
    /** @const Increment images count for the each next album */
    const DOWN_COUNT = 20;
    /** @const relative path to the folder for downloaded images from the app root folder */
    const IMAGE_FOLDER = 'storage';

    /** @var  string  path to the folder for downloaded images */
    private $targetFolder;

    /**
     * LoadAlbumData constructor.
     */
    public function __construct()
    {
        $rootDir = dirname(dirname(dirname(dirname(__DIR__))));
        $this->targetFolder = $rootDir . '/' . self::IMAGE_FOLDER;
    }

    /**
     * Loads fixtures to the local disk and to the local DB
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $albums = [
            ['title'=>'People', 'description'=>'Interesting photos of people around us'],
            ['title'=>'Nature', 'description'=>'Amazing photos of the nature'],
            ['title'=>'Food', 'description'=>'It is delicious'],
            ['title'=>'Technology', 'description'=>'The best images about science and technologies'],
            ['title'=>'Sport', 'description'=>'Motivated sport photos'],
        ];


        $count = self::START_COUNT;
        $grabber = new Grabber();
        $this->clean();

        foreach ($albums as $album) {

            $images = $grabber->getImages(strtolower($album['title']), $count, $this->targetFolder);
            echo 'Progress: ',$album['title'], '  goal=',$count, ' got=',count($images),PHP_EOL;
            if ($images) {
                $newAlbum = new Album();
                $newAlbum->setCreatedAt(new \DateTime());
                $newAlbum->setTitle($album['title']);
                $newAlbum->setDescription($album['description']);
                $newAlbum->setImagesCount(count($images));
                $manager->persist($newAlbum);
                $manager->flush();

                $this->setReference('album', $newAlbum);

                foreach ($images as $file) {
                    $info = getimagesize($file);
                    $newImage = new Image($newAlbum);
                    $newImage->setCreatedAt(new \DateTime());
                    $newImage->setAlbum($this->getReference('album'));
                    $newImage->setPath($file);
                    $newImage->setMd5(md5(file_get_contents($file)));
                    $newImage->setWidth($info[0]);
                    $newImage->setHeight($info[1]);
                    $newImage->setMime($info['mime']);
                    $manager->persist($newImage);
                    $manager->flush();
                }

                $count += self::DOWN_COUNT;
                sleep(mt_rand(3,10));
            }
        }
    }

    private function clean()
    {
        foreach (scandir($this->targetFolder) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            unlink($this->targetFolder.'/'.$item);
        }
    }
}