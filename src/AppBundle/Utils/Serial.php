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
     * @return string  JSON
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
     * Serializes entities to the list (allow hide and transform Entity's attributes)
     *
     * @param mixed $content  entities
     * @param array $hiddenAttributes  which attributes should hide
     * @param array $callbacks         custom callbacks for normalizer to transform specified attribute
     * @return string  JSON
     */
    public function toList($content, array $hiddenAttributes=[], array $callbacks=[])
    {
        $normalizer = new ObjectNormalizer();
        if ($hiddenAttributes)
            $normalizer->setIgnoredAttributes($hiddenAttributes);
        if ($callbacks)
            $normalizer->setCallbacks($callbacks);

        $serializer = new Serializer(
            array(new DateTimeNormalizer('Y-m-d H:i:s'), $normalizer),
            array(new JsonEncoder())
        );
        return $serializer->serialize($content, 'json');

    }
}