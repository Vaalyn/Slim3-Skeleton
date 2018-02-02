<?php
	namespace Service\Factory\Eloquent;

	use Illuminate\Database\Capsule\Manager;

	class EloquentFactory {

		/**
		 * @param array $config
		 * 
		 * @return \Illuminate\Database\Capsule\Manager
		 */
		public static function create(array $config): Manager {
			$capsule = new Manager;
			$capsule->addConnection($config);
			$capsule->setAsGlobal();
			$capsule->bootEloquent();

			return $capsule;
		}
	}

?>
