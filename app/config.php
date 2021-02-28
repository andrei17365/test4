<?php
if( !session_id() ) @session_start();

require '../vendor/autoload.php';
use DI\ContainerBuilder;
use Delight\Auth\Auth;
use League\Plates\Engine;
use \Tamtamchik\SimpleFlash\Flash;
use Aura\SqlQuery\QueryFactory;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
	Engine::class => function() {
		return new Engine('../app/views');
	},

	PDO::class => function() {
		$driver = "mysql";
		$host = "localhost";
		$database_name = "myapp";
		$charset = "utf8";
		$username = "root";
		$password = "root";

		return new PDO("$driver:host=$host;dbname=$database_name;charset=$charset;","$username","$password");
	},

	QueryFactory::class => function () {
       return new QueryFactory ('mysql');
    },

	Auth::class => function($container){
		return new Auth($container->get('PDO'));
	}

]);
$container = $containerBuilder->build();

?>