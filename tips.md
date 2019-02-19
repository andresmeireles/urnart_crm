# Algumas simples funções pouco usada.

## Serialização

É nescessário de tempos em tempos serializar o resultado das consultas ao banco de dados. Maioria das classes tem o objeto `serialize` na metaclasse `model`.

##### Exemplo.

`$this->serializer->serialize($returnList, 'json');`
_$returnList_ é um objeto qualquer.

#### Exceções

Em casos fora classes `model` podemos intanciar o objeto para fazer a serialização.

##### Exemplos

`
<?php 

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Metadata\ClassMetadata;

$serializator = SerializerBuilder::create()->build();

[ codigo omitido com logica e consultas... ]

$serializator->serialize($returnList, 'json');
`
_$returnList_ é um objeto qualquer.