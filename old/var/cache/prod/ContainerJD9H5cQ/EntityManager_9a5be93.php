<?php

namespace ContainerJD9H5cQ;

include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'doctrine'.\DIRECTORY_SEPARATOR.'persistence'.\DIRECTORY_SEPARATOR.'lib'.\DIRECTORY_SEPARATOR.'Doctrine'.\DIRECTORY_SEPARATOR.'Persistence'.\DIRECTORY_SEPARATOR.'ObjectManager.php';
include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'doctrine'.\DIRECTORY_SEPARATOR.'orm'.\DIRECTORY_SEPARATOR.'lib'.\DIRECTORY_SEPARATOR.'Doctrine'.\DIRECTORY_SEPARATOR.'ORM'.\DIRECTORY_SEPARATOR.'EntityManagerInterface.php';
include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'doctrine'.\DIRECTORY_SEPARATOR.'orm'.\DIRECTORY_SEPARATOR.'lib'.\DIRECTORY_SEPARATOR.'Doctrine'.\DIRECTORY_SEPARATOR.'ORM'.\DIRECTORY_SEPARATOR.'EntityManager.php';
class EntityManager_9a5be93 extends \Doctrine\ORM\EntityManager implements \ProxyManager\Proxy\VirtualProxyInterface
{
    private $valueHoldera07c7 = null;
    private $initializerbbfd1 = null;
    private static $publicPropertiesbc906 = [
        
    ];
    public function getConnection()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'getConnection', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->getConnection();
    }
    public function getMetadataFactory()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'getMetadataFactory', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->getMetadataFactory();
    }
    public function getExpressionBuilder()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'getExpressionBuilder', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->getExpressionBuilder();
    }
    public function beginTransaction()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'beginTransaction', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->beginTransaction();
    }
    public function getCache()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'getCache', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->getCache();
    }
    public function transactional($func)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'transactional', array('func' => $func), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->transactional($func);
    }
    public function commit()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'commit', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->commit();
    }
    public function rollback()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'rollback', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->rollback();
    }
    public function getClassMetadata($className)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'getClassMetadata', array('className' => $className), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->getClassMetadata($className);
    }
    public function createQuery($dql = '')
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'createQuery', array('dql' => $dql), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->createQuery($dql);
    }
    public function createNamedQuery($name)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'createNamedQuery', array('name' => $name), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->createNamedQuery($name);
    }
    public function createNativeQuery($sql, \Doctrine\ORM\Query\ResultSetMapping $rsm)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'createNativeQuery', array('sql' => $sql, 'rsm' => $rsm), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->createNativeQuery($sql, $rsm);
    }
    public function createNamedNativeQuery($name)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'createNamedNativeQuery', array('name' => $name), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->createNamedNativeQuery($name);
    }
    public function createQueryBuilder()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'createQueryBuilder', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->createQueryBuilder();
    }
    public function flush($entity = null)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'flush', array('entity' => $entity), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->flush($entity);
    }
    public function find($className, $id, $lockMode = null, $lockVersion = null)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'find', array('className' => $className, 'id' => $id, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->find($className, $id, $lockMode, $lockVersion);
    }
    public function getReference($entityName, $id)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'getReference', array('entityName' => $entityName, 'id' => $id), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->getReference($entityName, $id);
    }
    public function getPartialReference($entityName, $identifier)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'getPartialReference', array('entityName' => $entityName, 'identifier' => $identifier), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->getPartialReference($entityName, $identifier);
    }
    public function clear($entityName = null)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'clear', array('entityName' => $entityName), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->clear($entityName);
    }
    public function close()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'close', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->close();
    }
    public function persist($entity)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'persist', array('entity' => $entity), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->persist($entity);
    }
    public function remove($entity)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'remove', array('entity' => $entity), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->remove($entity);
    }
    public function refresh($entity)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'refresh', array('entity' => $entity), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->refresh($entity);
    }
    public function detach($entity)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'detach', array('entity' => $entity), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->detach($entity);
    }
    public function merge($entity)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'merge', array('entity' => $entity), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->merge($entity);
    }
    public function copy($entity, $deep = false)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'copy', array('entity' => $entity, 'deep' => $deep), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->copy($entity, $deep);
    }
    public function lock($entity, $lockMode, $lockVersion = null)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'lock', array('entity' => $entity, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->lock($entity, $lockMode, $lockVersion);
    }
    public function getRepository($entityName)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'getRepository', array('entityName' => $entityName), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->getRepository($entityName);
    }
    public function contains($entity)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'contains', array('entity' => $entity), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->contains($entity);
    }
    public function getEventManager()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'getEventManager', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->getEventManager();
    }
    public function getConfiguration()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'getConfiguration', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->getConfiguration();
    }
    public function isOpen()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'isOpen', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->isOpen();
    }
    public function getUnitOfWork()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'getUnitOfWork', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->getUnitOfWork();
    }
    public function getHydrator($hydrationMode)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'getHydrator', array('hydrationMode' => $hydrationMode), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->getHydrator($hydrationMode);
    }
    public function newHydrator($hydrationMode)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'newHydrator', array('hydrationMode' => $hydrationMode), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->newHydrator($hydrationMode);
    }
    public function getProxyFactory()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'getProxyFactory', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->getProxyFactory();
    }
    public function initializeObject($obj)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'initializeObject', array('obj' => $obj), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->initializeObject($obj);
    }
    public function getFilters()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'getFilters', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->getFilters();
    }
    public function isFiltersStateClean()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'isFiltersStateClean', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->isFiltersStateClean();
    }
    public function hasFilters()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'hasFilters', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return $this->valueHoldera07c7->hasFilters();
    }
    public static function staticProxyConstructor($initializer)
    {
        static $reflection;
        $reflection = $reflection ?? new \ReflectionClass(__CLASS__);
        $instance   = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $instance, 'Doctrine\\ORM\\EntityManager')->__invoke($instance);
        $instance->initializerbbfd1 = $initializer;
        return $instance;
    }
    protected function __construct(\Doctrine\DBAL\Connection $conn, \Doctrine\ORM\Configuration $config, \Doctrine\Common\EventManager $eventManager)
    {
        static $reflection;
        if (! $this->valueHoldera07c7) {
            $reflection = $reflection ?? new \ReflectionClass('Doctrine\\ORM\\EntityManager');
            $this->valueHoldera07c7 = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);
        }
        $this->valueHoldera07c7->__construct($conn, $config, $eventManager);
    }
    public function & __get($name)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, '__get', ['name' => $name], $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        if (isset(self::$publicPropertiesbc906[$name])) {
            return $this->valueHoldera07c7->$name;
        }
        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');
        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldera07c7;
            $backtrace = debug_backtrace(false, 1);
            trigger_error(
                sprintf(
                    'Undefined property: %s::$%s in %s on line %s',
                    $realInstanceReflection->getName(),
                    $name,
                    $backtrace[0]['file'],
                    $backtrace[0]['line']
                ),
                \E_USER_NOTICE
            );
            return $targetObject->$name;
        }
        $targetObject = $this->valueHoldera07c7;
        $accessor = function & () use ($targetObject, $name) {
            return $targetObject->$name;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = & $accessor();
        return $returnValue;
    }
    public function __set($name, $value)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, '__set', array('name' => $name, 'value' => $value), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');
        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldera07c7;
            $targetObject->$name = $value;
            return $targetObject->$name;
        }
        $targetObject = $this->valueHoldera07c7;
        $accessor = function & () use ($targetObject, $name, $value) {
            $targetObject->$name = $value;
            return $targetObject->$name;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = & $accessor();
        return $returnValue;
    }
    public function __isset($name)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, '__isset', array('name' => $name), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');
        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldera07c7;
            return isset($targetObject->$name);
        }
        $targetObject = $this->valueHoldera07c7;
        $accessor = function () use ($targetObject, $name) {
            return isset($targetObject->$name);
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = $accessor();
        return $returnValue;
    }
    public function __unset($name)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, '__unset', array('name' => $name), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');
        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldera07c7;
            unset($targetObject->$name);
            return;
        }
        $targetObject = $this->valueHoldera07c7;
        $accessor = function () use ($targetObject, $name) {
            unset($targetObject->$name);
            return;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $accessor();
    }
    public function __clone()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, '__clone', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        $this->valueHoldera07c7 = clone $this->valueHoldera07c7;
    }
    public function __sleep()
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, '__sleep', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        return array('valueHoldera07c7');
    }
    public function __wakeup()
    {
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);
    }
    public function setProxyInitializer(\Closure $initializer = null) : void
    {
        $this->initializerbbfd1 = $initializer;
    }
    public function getProxyInitializer() : ?\Closure
    {
        return $this->initializerbbfd1;
    }
    public function initializeProxy() : bool
    {
        return $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'initializeProxy', array(), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
    }
    public function isProxyInitialized() : bool
    {
        return null !== $this->valueHoldera07c7;
    }
    public function getWrappedValueHolderValue()
    {
        return $this->valueHoldera07c7;
    }
}

if (!\class_exists('EntityManager_9a5be93', false)) {
    \class_alias(__NAMESPACE__.'\\EntityManager_9a5be93', 'EntityManager_9a5be93', false);
}
