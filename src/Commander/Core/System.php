<?php

namespace Commander\Core;

use Commander\Exception\MissingDependencyException;
use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\FileCacheReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Mesa\Config\Config;
use Monolog\Logger;

/**
 * Class App
 *
 * @package Commander\System
 */
class System
{
    /**
     * @var array
     */
    protected $commands = array();
    private $basePath;
    private $loggerName = "Logger";
    private $defaultControllerPattern = '*Controller.php';
    private $defaultMethodPattern = '/.*/';
    /**
     * @Inject
     * @var \AltoRouter
     */
    private $router;
    /**
     * @var
     */
    private $container;
    /**
     * @Inject("Config")
     */
    private $config;
    /**
     * @Inject("Logger")
     */
    private $logger;
    /**
     * @var
     */
    private $bundles;
    private $entityDirs = array();
    private $entityManager;
    private $containerBuilder;
    private $configFiles;
    private $prepared = false;
    private $controllerBag = array();

    public function __construct()
    {
        $this->config           = new Config();
        $this->containerBuilder = new ContainerBuilder();
        $this->containerBuilder->useAutowiring(true);
    }

    /**
     * @param array $dir
     */
    public function addEntityDir($dir)
    {
        $this->entityDirs[] = $dir;
    }

    /**
     * @return array
     */
    public function getEntityDirs()
    {
        return $this->entityDirs;
    }

    public function addServiceFile($path)
    {
        $this->containerBuilder->addDefinitions($path);
    }

    /**
     *
     */
    public function run()
    {
        if (!$this->prepared) {
            $this->prepare();
        }

        $container = $this->getContainer();
        $router    = $this->getRouter();

        /**
         * @var \AltoRouter $router
         */
        $router->setBasePath($this->basePath);

        if (!isset($_SERVER["REQUEST_URI"])) {
            array_pop($_SERVER["argv"]);
            $requestPath = implode("/", $_SERVER["argv"]);
        } else {
            $requestPath = $_SERVER["REQUEST_URI"];
        }

        $match = $router->match($requestPath);
        if ($match) {

            foreach ($match['target']['args'] as $key => $obj) {
                if (!is_object($obj)) {
                    continue;
                }
                $this->getContainer()->set(get_class($obj), $obj);
            }

            if (isset($match['target']['args']->role)) {
                $role          = $match['target']['args']->role;
                $accessManager = $this->getContainer()->get("AccessManager");
                $accessManager->check($role);
            }

            $controller = $container->get($match['target']['c']);
            $wrapper    = new ClassWrapper($controller);

            foreach ($match["params"] as $key => $value) {
                $wrapper->addParam($key, $value);
            }

            ob_start();
            $data   = $wrapper->call($match['target']['a']);
            $output = ob_get_clean();

            if (!empty($output)) {
                echo $output;
            }

            if (!is_array($data)) {
                /**
                 * @todo add error reporting
                 */
                echo "Return Type has to be an array";
            }

            $response = $container->get("Response");

            $response->setData($data, $match['target']['args']->view->path);
        } else {
            if ($container->has("RouteNotFoundResponse")) {
                echo $container->get("RouteNotFoundResponse");
            }
            $response = "Something went wrong. This wasn't the plan.";
        }

        return $response;
    }

    protected function createDoctrine()
    {
        $isDevMode = $this->getConfig()->get("doctrine.devMode", false);
        $dbParams  = $this->getConfig()->get("doctrine.params", []);
        $config    = Setup::createAnnotationMetadataConfiguration($this->getEntityDirs(), $isDevMode);
        $config->setQueryCacheImpl($this->getContainer()->get("DoctrineCache"));
        $config->setAutoGenerateProxyClasses(true);
        $manager = EntityManager::create($dbParams, $config);
        $this->getContainer()->set("EntityManager", $manager);

        return $manager;
    }

    public function getEntityManager()
    {
        if (empty($this->entityManager)) {
            $this->entityManager = $this->createDoctrine();
        }

        return $this->entityManager;
    }

    /**
     * @return \DI\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param $dic
     */
    public function setContainer($dic)
    {
        $this->container = $dic;
    }

    /**
     * @return \AltoRouter
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param \AltoRouter $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }

    /**
     * @param \Exception $message
     *
     * @return void
     */
    public function createErrorResponse(\Exception $message)
    {
        echo $message;
    }

    /**
     *
     */
    public function prepare()
    {
        $this->prepared = true;

        $this->setBasePath();

        foreach ($this->configFiles as $file) {
            $this->getConfig()->load($file->getRealPath());
        }

        $bundles = $this->getConfig()->get("bundles", []);

        foreach ($bundles as $namespace => $path) {
            $this->addBundle($namespace, $path);
        }

        $this->setContainer($this->containerBuilder->build());
        $this->getContainer()->set("Config", $this->getConfig());
        $this->getContainer()->set("template.path", $this->getConfig()->get("path.templates"));
        $this->getContainer()->set("twig.options", $this->getConfig()->get("twig.options", ['cache' => 'cache/twig']));
        $this->getContainer()->injectOn($this);

        $this->loadControllerFromPath();
    }

    protected function setBasePath()
    {
        $basePath = dirname($_SERVER["PHP_SELF"]);
        if ("/" == $basePath) {
            $basePath = "";
        }

        $this->basePath = $basePath;
    }

    /**
     * @param \SplFileInfo $file
     *
     * @return $this
     */
    public function addConfig(\SplFileInfo $file)
    {
        $this->configFiles[] = $file;

        return $this;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Add Modul to autoloader
     *
     * @param $namespace
     * @param $includePath
     *
     * @throws \InvalidArgumentException
     */
    public function addBundle($namespace, $includePath)
    {
        if (!file_exists($includePath) && !is_dir($includePath)) {
            throw new \InvalidArgumentException("IncludePath not found $includePath");
        }

        if (isset($this->bundles[$namespace])) {
            return;
        }

        $this->bundles[$namespace] = $includePath;
        $loader                    = new \SplClassLoader($namespace);
        $loader->setIncludePath($includePath);

        $dir = $includePath . DS . $namespace . DS;

        $configFileEnv = $dir . "Config" . DS . "config_" . strtolower(ENVIRONMENT::current()) . ".php";
        $configFile    = $dir . "Config" . DS . "config.php";

        if (is_dir($dir . "Config")) {
            if (file_exists($configFile)) {
                $this->addConfig(new \SplFileInfo($configFile));
            }
            if (file_exists($configFileEnv)) {
                $this->addConfig(new \SplFileInfo($configFileEnv));
            }
        }

        $entityPath = $dir . "Entity";
        if (is_dir($entityPath)) {
            $this->addEntityDir($entityPath);
        }

        if (is_dir($dir . "Annotation")) {
            $this->addAnnotation('\\' . $namespace . '\Annotation', $includePath);
        }

        if (is_dir($dir . "Controller")) {
            $controller = glob($dir . "Controller/*Controller.php");
            foreach ($controller as $item) {
                $this->controllerBag[$namespace] = str_replace($includePath, "", $item);
            }
        }

        $views = $this->getConfig()->get("path.templates", []);

        if (is_dir($dir . "Views")) {
            $views[] = $dir . "Views" . DS;
            $this->getConfig()->set('path.templates',$views);
        }

        $env      = strtolower(ENVIRONMENT::current());
        $filename = $dir . "Config" . DS . "services_" . $env . ".php";
        if (file_exists($filename)) {
            $this->addServiceFile($filename);
        }

        $loader->register();
    }

    /**
     * @param $namespace
     * @param $path
     */
    protected function addAnnotation($namespace, $path)
    {
        AnnotationRegistry::registerAutoloadNamespace($namespace, $path);
    }

    /**
     * Load Controller from defined folder and add them to service container
     *
     * @return bool
     */
    protected function loadControllerFromPath()
    {
        $config    = $this->getConfig();
        $reader    = $this->getAnnotationReader();
        $container = $this->getContainer();
        $routeBag  = [];

        foreach ($this->controllerBag as $namespace => $relativePath) {

            $methodPattern = $config->get("config.controller.methodPattern", $this->defaultMethodPattern);

            $search[]  = ".php";
            $replace[] = "";
            $search[]  = "/";
            $replace[] = "\\";

            $namespace       = str_replace($search, $replace, $relativePath);
            $classReflection = new \ReflectionClass($namespace);
            $classAnnotation = $reader->getClassAnnotation($classReflection, '\Commander\Annotation\Route');
            $methods         = $classReflection->getMethods(\ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                $arguments = new \stdClass();
                if ($this->defaultMethodPattern == $methodPattern ||
                    !preg_match($methodPattern, $method->name)
                ) {
                    continue;
                }
                $annotations      = $reader->getMethodAnnotations($method);
                $arguments->route = new \stdClass();
                $arguments->view  = new \stdClass();

                foreach ($annotations as $anno) {
                    switch (get_class($anno)) {
                        case 'Commander\Annotation\Route':
                            $arguments->route = $anno;
                            break;
                        case 'Commander\Annotation\View':
                            if (empty($anno->path)) {
                                $anno->path = $classReflection->getName() . '\\' . $method->name . ".twig.php";
                            }
                            $arguments->view = $anno;
                            break;
                        default:
                            $splitNameSpace     = explode('\\', get_class($anno));
                            $name               = strtolower(array_pop($splitNameSpace));
                            $arguments->{$name} = $anno;
                    }
                }

                if (!isset($arguments->route->path)) {
                    continue;
                }

                $arguments->class  = $classReflection->getName();
                $arguments->method = $method->name;

                if (!empty($classAnnotation)) {
                    $arguments->route->path = $classAnnotation->path . $arguments->route->path;
                }
                $routeBag[] = $arguments;
            }

            if (!empty($arguments->route->path)) {
                $container->set($arguments->class, \DI\object($arguments->class));
            }
        }

        $config->set("routes", $routeBag);
        $router = $this->getRouter();

        foreach ($routeBag as $args) {
            $router->map(
                   $args->route->method,
                       $args->route->path,
                       array('c' => $args->class, 'a' => $args->method, 'args' => $args),
                       $args->route->name
            );
        }

        return true;
    }

    /**
     * @return AnnotationReader|FileCacheReader
     */
    public function getAnnotationReader()
    {
        return $this->getContainer()->get("AnnotationReader");
    }

    /**
     * @throws \Commander\Exception\MissingDependencyException
     * @return Logger
     */
    public function getLogger()
    {
        if (null === $this->logger) {
            $this->logger = $this->getConfig()->get($this->loggerName);
            if (!$this->logger) {
                throw new MissingDependencyException($this->loggerName);
            }
        }

        return $this->logger;
    }

    /**
     * @param $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }
}
