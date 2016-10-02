<?php

namespace AppBundle\Tests\Utils;

use AppBundle\Utils\Grabber;

class GrabberTest extends \PHPUnit_Framework_TestCase
{
    public function testImages()
    {
        $dir = '/tmp/grabber_test_imgeezzz';
        $topic = 'sport';
        $howMany = 50;

        if (!is_dir($dir))
            mkdir($dir);

        $grabber = new Grabber();
        $result = $grabber->getImages($topic, $howMany, $dir);

        $this->assertInternalType('array',$result);

        $realCount = 0;
        foreach ($result as $img)  {
            $this->assertTrue(is_file($img), "Should exist a file $img");
            $realCount++;
        }

        $this->assertEquals($howMany, $realCount, "Expected $howMany files, but in fact $realCount");

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
           unlink($dir.'/'.$item);
        }
        rmdir($dir);
    }
}