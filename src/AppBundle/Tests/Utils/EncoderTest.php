<?php

namespace AppBundle\Tests\Utils;

use AppBundle\Utils\Encoder;

class EncoderTest extends \PHPUnit_Framework_TestCase
{
    public function testIds()
    {
        $encoder = new Encoder();

        foreach (range(8500,9000,mt_rand(5,10)) as $id) {
            $encoded_id = $encoder->encodeId($id);
            $decoded_id = $encoder->decodeId($encoded_id);
            $this->assertEquals($decoded_id, $id, 'Ids are not equals');
        }
    }
}