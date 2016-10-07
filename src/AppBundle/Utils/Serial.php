<?php

namespace AppBundle\Utils;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;

/**
 * Class Serial
 * Share the Serializer component
 * @author Igor Cherkashin aka JiSoft <jisoft.dn@gmail.com>
 */
class Serial
{
    /**
     * Serializes entities to JSON
     *
     * @param mixed $content   entities
     * @return string
     */
    public function toJson($content)
    {
        $serializer = new Serializer(
            array(new DateTimeNormalizer('Y-m-d H:i:s'), new ObjectNormalizer),
            array(new JsonEncoder())
        );
        return $serializer->serialize($content, 'json');
    }

    /**
     * Serializes entitites to list of images for browser
     *
     * @param mixed $content  entities
     * @return string|\Symfony\Component\Serializer\Encoder\scalar
     */
    public function toList($content)
    {
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(['id', 'albumId', 'path', 'md5', 'mime', 'data', 'content']);

        $serializer = new Serializer(
            array(new DateTimeNormalizer('Y-m-d H:i:s'), $normalizer),
            array(new JsonEncoder())
        );
        return $serializer->serialize($content, 'json');

    }
}