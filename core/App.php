<?php
    namespace App\Core;

    use Symfony\Component\Yaml\Yaml;

    class App{

        private static $dependancies = [];
        private array $services = [];

        public function __construct() {
            $this->loadServices();
        }

        private function loadServices() {
            $config = Yaml::parseFile(__DIR__ . '/../config/services.yml');
            foreach ($config['services'] as $key => $class) {
                $this->services[$key] = new $class();
            }
        }

        public function get(string $name) {
            return $this->services[$name] ?? null;
        }
        
        public static function init(){
            self::$dependancies = [
                'router' => new \App\Core\Router(),
                'database' => \App\Core\Database::getInstance(),
                'session' => \App\Core\Session::getInstance()
            ];

            self::registerDependency('clientRepository', \App\Repository\ClientRepository::getInstance());
            self::registerDependency('TransactionRepository', \App\Repository\TransactionRepository::getInstance());
            self::registerDependency('clientService', \App\Service\ClientService::getInstance());
            self::registerDependency('clientCompteRepository', \App\Repository\ClientCompteRepository::getInstance());
            self::registerDependency('clientCompteService', \App\Service\ClientCompteService::getInstance());
        }

        public static function getDependancy($name){
            if(array_key_exists($name, self::$dependancies)){
                return self::$dependancies[$name];
            }
            throw new \Exception("Dependancies non trouvé: " . $name);
        }

        public static function registerDependency($name, $instance){
            if(!array_key_exists($name, self::$dependancies)){
                self::$dependancies[$name] = $instance;
            } else {
                throw new \Exception("Dependancie déjà enregistrée: " . $name);
            }
        }
    }