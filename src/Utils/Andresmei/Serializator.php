<?php declare(strict_types = 1);

namespace App\Utils\Andresmei;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

/**
 * Class Serializator
 * @package App\Utils\Andresmei
 */
final class Serializator
{
    /**
     * @return SerializerInterface
     */
    private static function createSeralizator(): SerializerInterface
    {
        return SerializerBuilder::create()->build();
    }

    /**
     * @param object $serializeObject
     * @return string
     */
    public static function serializeToJson(object $serializeObject): string
    {
        $serializer = self::createSeralizator();

        return $serializer->serialize($serializeObject, 'json');
    }

    /**
     * @param object $serializableObject
     * @return array
     */
    public static function serializeToArray(object $serializableObject): array
    {
        $jsonString = self::serializeToJson($serializableObject);

        return json_decode($jsonString, true);
    }
}