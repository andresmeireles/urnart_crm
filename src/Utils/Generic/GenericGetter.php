<?php 
namespace App\Utils\Generic;

use App\Utils\Generic\GenericContainer;
use JMS\Serializer\SerializerBuilder;

class GenericGetter extends GenericContainer
{
	public function getJsonData(string $entity)
	{
		$entityName = ucfirst($entity);
		$className = 'App\Entity\\'.$entityName;
		$serializer = SerializerBuilder::create()->build();

		$data = $this->em->getRepository($className)->findAll();
		$jsonResponse = $serializer->serialize($data, 'json');

		return $jsonResponse;
	}

	public function get(string $entity)
	{
		$entityName = ucfirst($entity);
		$className = 'App\Entity\\'.$entityName;
		$data = $this->em->getRepository($className)->findAll();
		return $data;
	}
}