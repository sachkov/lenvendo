<?
// Здесь у нас интерфейс контейнера, так что мы можем менять реализации локатора служб.
interface Container {

   public function has( string $key ): bool;

   public function get( string $key );
}

// Базовая реализация локатора служб.
class ServiceLocator implements Container {

   protected $services = [];

   public function has( string $key ): bool {
      return array_key_exists( $key, $this->services );
   }

   public function get( string $key ) {
      $service = $this->services[ $key ];
      if ( is_callable( $service ) ) {
         $service = $service();
      }

      return $service;
   }

   public function add( string $key, callable $service ) {
      $this->services[ $key ] = $service;
   }
}

// Здесь у нас интерфейс, так что мы можем работать с несколькими реализациями и корректно 
//имитировать (mock) ради тестирования.
interface DatabaseConnection {

   public function query( ...$args );
}

// Используемая в данный момент реализация.
class MySQLDatabaseConnection implements DatabaseConnection {

   public function query( ...$args ) {
      // Исполняется запрос и возвращается результат.
   }
}

// Инициализирующий код.
$services = new ServiceLocator();
$services->add( 'Database', function () {
   return new MySQLDatabaseConnection();
} );

// Потребляющий код.
$result = $services->get( 'Database' )->query( $query );



class Container2
{
	/**
	 * @var array
	 */
	protected $instances = [];
	/**
	 * @param      $abstract
	 * @param null $concrete
	 */
	public function set($abstract, $concrete = NULL)
	{
		if ($concrete === NULL) {
			$concrete = $abstract;
		}
		$this->instances[$abstract] = $concrete;
	}
	/**
	 * @param       $abstract
	 * @param array $parameters
	 *
	 * @return mixed|null|object
	 * @throws Exception
	 */
	public function get($abstract, $parameters = [])
	{
		// if we don't have it, just register it
		if (!isset($this->instances[$abstract])) {
			$this->set($abstract);
		}
		return $this->resolve($this->instances[$abstract], $parameters);
	}
	/**
	 * resolve single
	 *
	 * @param $concrete
	 * @param $parameters
	 *
	 * @return mixed|object
	 * @throws Exception
	 */
	public function resolve($concrete, $parameters)
	{
		if ($concrete instanceof Closure) {
			return $concrete($this, $parameters);
		}
		$reflector = new ReflectionClass($concrete);
		// check if class is instantiable
		if (!$reflector->isInstantiable()) {
			throw new Exception("Class {$concrete} is not instantiable");
		}
		// get class constructor
		$constructor = $reflector->getConstructor();
		if (is_null($constructor)) {
			// get new instance from class
			return $reflector->newInstance();
		}
		// get constructor params
		$parameters   = $constructor->getParameters(); // тут ошибка! переоприделяется входной масссив
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
	public function getDependencies($parameters)
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
					throw new Exception("Can not resolve class dependency {$parameter->name}");
				}
			} else {
				// get dependency resolved
				$dependencies[] = $this->get($dependency->name);
			}
		}
		return $dependencies;
	}
}    