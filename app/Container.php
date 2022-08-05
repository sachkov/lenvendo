<?php
namespace App;
use App\Http;
use Psr\Container as Psrcontainer;

/**
 * Простой DI контейнер для получения зависимостей через рефлексию
 * Можно переоприделять зависимости через массив $instances
 * или добавить конкретную реализацию интерфейса
 */

class Container implements Psrcontainer\ContainerInterface
{
	protected array $instances = [];

	/**
     * Задать массив $instances
	 * @param array of instanses
     * @return Container
	 */
	public function set(array $instances):Container
	{
		if (!empty($instances)) $this->instances = $instances;
        return $this;
	}

    /**
     * Добавить в массив $instances новые записи
     * Старые будут переоприделены
     */
    public function add(array $instances):Container
    {
        foreach($instances as $key=>$instance){
            $this->instances[$key] = $instance;
        }
        return $this;
    }

	/**
     * 
	 */
	public function has(string $id):bool
	{
		// if we don't have it, just register it
		if (isset($this->instances[$id])) return true;
		return false;
	}
	/**
	 * resolve single
     */
	public function get(string $id)
	{
        if($this->has($id)) $concrete = $this->instances[$id];
        else $concrete = $id;
        
		$reflector = new \ReflectionClass($concrete);
		// check if class is instantiable
		if (!$reflector->isInstantiable()) {
			throw new \Exception("Class {$concrete} is not instantiable");
		}
		// get class constructor
		$constructor = $reflector->getConstructor();
		if (is_null($constructor)) {
			// get new instance from class
			return $reflector->newInstance();
		}
		// get constructor params
		$parameters = $constructor->getParameters();
		$dependencies = $this->getDependencies($parameters);
		// get new instance with dependencies resolved
		return $reflector->newInstanceArgs($dependencies);
	}

	/**
	 * get all dependencies resolved
	 *
	 * @param $parameters
	 *
	 * @return array
	 * @throws Exception
	 */
	protected function getDependencies(array $parameters):array
	{
		$dependencies = [];
		foreach ($parameters as $parameter) {
			// get the type hinted class
			$dependency = $parameter->getClass();
			if ($dependency === NULL) {
				// check if default value for a parameter is available
				if ($parameter->isDefaultValueAvailable()) {
					// get default value of parameter
					$dependencies[] = $parameter->getDefaultValue();
				} else {
					throw new \Exception("Can not resolve class dependency {$parameter->name}");
				}
			} else {
				// get dependency resolved
				$dependencies[] = $this->get($dependency->name);
			}
		}
		return $dependencies;
	}
}    