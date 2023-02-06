<?php

namespace ContainerJD9H5cQ;

include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'knplabs'.\DIRECTORY_SEPARATOR.'knp-components'.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'Knp'.\DIRECTORY_SEPARATOR.'Component'.\DIRECTORY_SEPARATOR.'Pager'.\DIRECTORY_SEPARATOR.'PaginatorInterface.php';
include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'knplabs'.\DIRECTORY_SEPARATOR.'knp-components'.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'Knp'.\DIRECTORY_SEPARATOR.'Component'.\DIRECTORY_SEPARATOR.'Pager'.\DIRECTORY_SEPARATOR.'Paginator.php';
class PaginatorInterface_82dac15 implements \ProxyManager\Proxy\VirtualProxyInterface, \Knp\Component\Pager\PaginatorInterface
{
    private $valueHoldera07c7 = null;
    private $initializerbbfd1 = null;
    private static $publicPropertiesbc906 = [
        
    ];
    public function paginate($target, int $page = 1, ?int $limit = null, array $options = []) : \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, 'paginate', array('target' => $target, 'page' => $page, 'limit' => $limit, 'options' => $options), $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        if ($this->valueHoldera07c7 === $returnValue = $this->valueHoldera07c7->paginate($target, $page, $limit, $options)) {
            return $this;
        }
        return $returnValue;
    }
    public static function staticProxyConstructor($initializer)
    {
        static $reflection;
        $reflection = $reflection ?? new \ReflectionClass(__CLASS__);
        $instance   = $reflection->newInstanceWithoutConstructor();
        $instance->initializerbbfd1 = $initializer;
        return $instance;
    }
    public function __construct()
    {
        static $reflection;
        if (! $this->valueHoldera07c7) {
            $reflection = $reflection ?? new \ReflectionClass('Knp\\Component\\Pager\\PaginatorInterface');
            $this->valueHoldera07c7 = $reflection->newInstanceWithoutConstructor();
        }
    }
    public function & __get($name)
    {
        $this->initializerbbfd1 && ($this->initializerbbfd1->__invoke($valueHoldera07c7, $this, '__get', ['name' => $name], $this->initializerbbfd1) || 1) && $this->valueHoldera07c7 = $valueHoldera07c7;
        if (isset(self::$publicPropertiesbc906[$name])) {
            return $this->valueHoldera07c7->$name;
        }
        $realInstanceReflection = new \ReflectionClass('Knp\\Component\\Pager\\PaginatorInterface');
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
        $realInstanceReflection = new \ReflectionClass('Knp\\Component\\Pager\\PaginatorInterface');
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
        $realInstanceReflection = new \ReflectionClass('Knp\\Component\\Pager\\PaginatorInterface');
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
        $realInstanceReflection = new \ReflectionClass('Knp\\Component\\Pager\\PaginatorInterface');
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

if (!\class_exists('PaginatorInterface_82dac15', false)) {
    \class_alias(__NAMESPACE__.'\\PaginatorInterface_82dac15', 'PaginatorInterface_82dac15', false);
}
