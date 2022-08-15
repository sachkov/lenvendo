<?php
namespace App;
use App\Http;
use Psr\Container as Psrcontainer;

/**
 * Simple DI container for getting dependencies through reflection
 * Possible to rewrite dependencies in $instances array
 * or add specific implementation of interface.
 */

class Container implements Psrcontainer\ContainerInterface
{
	protected array $instances = [];

	/**
     * Set array $instances 
	 * @param array of instanses
     * @return Container
	 */
	public function set(array $instances):Container
	{
		if (!empty($instances)) $this->instances = $instances;
        return $this;
	}

    /**
	 * Add new entries in $instances, old entries will be rewritten
     */
    public function add(array $instances):Container
    {
        foreach($instances as $key=>$instance){
            $this->instances[$key] = $instance;
        }
        return $this;
    }

	/**
     * Do we have specific implementation of class $id or not
	 * @param string $id - class name, or interface name
	 * @return bool
	 */
	public function has(string $id):bool
	{
		if (isset($this->instances[$id])) return true;
		return false;
	}
	
	/**
	 * Get class by it's name or interface
	 * @param string $id - class name, or interface name
     */
	public function get(string $id)
	{
        if($this->has($id)) $concrete = $this->instances[$id];
        else $concrete = $id;
        
		$reflector = new \ReflectionClass($concrete);
		// check if class is instantiable
		if (!$reflector->isInstantiable()) {
			throw new \Exception("Class {$concrete} is not instantiable.");
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
	 * Get all dependencies resolved
	 * @param array $parameters - class dependencies
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