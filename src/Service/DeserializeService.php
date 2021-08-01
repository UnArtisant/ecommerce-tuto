<?php


namespace App\Service;


use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DeserializeService
{

    /**
     * @return Serializer
     */
    public function generateSerializer() : Serializer
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer($classMetadataFactory)];

        return new Serializer($normalizers, $encoders);
    }

    /**
     * @param $data
     * @param array|null $groups
     * @param string|null $format
     * @return array
     */
    public function deserialiseObject($data, ?array $groups = [], ?string $format = "json"): array
    {

        $serializer = $this->generateSerializer();
        $result = $serializer->serialize($data, $format, $groups);
        return json_decode($result, true);
    }


}