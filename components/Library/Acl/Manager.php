<?php
namespace Components\Library\Acl;

use Phalcon\Config;
use Phalcon\DI\Injectable;
use Phalcon\Acl;
use Phalcon\Mvc\Model;
use Phalcon\Acl\AdapterInterface;


use ReflectionClass;
use DirectoryIterator;


use Components\Model\Roles;
use Components\Model\Access;

 /**
 * Class OAuth
 *
 * @package Phosphorum\Facebook
 */
class Manager extends Injectable
{


    const ADMIN_AREA = 'AdminArea';
	const ADMIN_TEST = 'AdminTest';
    const ADMIN_AREA_DESCRIPTION = 'Admin area';

    /**
     * @var AdapterInterface
     */
    protected $acl;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var AnnotationAdapter
     */
    protected $reader;

    /**
     * Manager constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
     }

    /**
     * Introspect the access objects (, initialize the ACL adapter) and get ACL adapter.
     *
     * @return AdapterInterface
     */
    protected function introspect()
    {
        if (!$this->acl) {
            /** @var \Phalcon\Cache\BackendInterface $cache */
            $cache = di()->get('cache');
            $acl   = $cache->get('acl_data');

            if (!$acl) {
                $save = !$acl  ;

                /** @var \Phalcon\Acl\AdapterInterface $acl */
                $adapter = $this->config->get('adapter');
                $acl = new $adapter();
                $acl->setDefaultAction(Acl::DENY);

                
                foreach (Roles::find() as $role) {
                    $acl->addRole(new Acl\Role($role->name, $role->description));
                    $roleNames[$role->id] = $role->name;
                }

                // $acl->addResource(new Acl\Resource(self::ADMIN_AREA), 'access');
                // $acl->allow('admin', self::ADMIN_AREA, 'access');

                $objects = $this->addResources($acl);
                
                foreach (Access::find() as $access) {
                    
                    /** @var Access $access */
                    if (!isset($objects[$access->getObject()])) {
                        continue;
                    }

                    $object = $objects[$access->getObject()];
                    $value  = $access->getValue();

                    if (in_array($access->getAction(), $object->actions) && ($value == 'allow' || $value == 'deny')) {
                        $acl->$value($roleNames[$access->getRoleId()], $access->getObject(), $access->getAction());
                    }
                }

                if ($save && !empty($objects)) {
                    $cache->save($this->config->get('cacheKey'), $acl, $this->config->get('lifetime'));
                }
            }

            $this->acl = $acl;
        }

        return $this->acl;
    }

    /**
     * Check whether a role is allowed to access an action from a resource
     *
     * @param mixed $roleName
     * @param mixed $resourceName
     * @param mixed $access
     * @param array $parameters
     * @return bool
     */
    public function isAllowed($roleName, $resourceName, $access, array $parameters = null)
    {
        if (!is_array($roleName)) {
            $roleName = [$roleName];
        }

        $acl = $this->introspect();

        foreach ($roleName as $name) {
            if ($acl->isAllowed($name, $resourceName, $access, $parameters)) {
                return true;
            }
        }

        return false;
    }
 
    /**
     * Add resources to ACL.
     *
     * @param  AdapterInterface $acl
     * @return array
     */
    protected function addResources(AdapterInterface $acl)
    {
        $objects = [];
        $base_path = __DIR__.'/../';
        $modelsDir = $base_path.'/components/Model/';


        if (file_exists($modelsDir)) {
            $dir = new DirectoryIterator($modelsDir);

            foreach ($dir as $fileInfo) {
                if ($fileInfo->isDir() || $fileInfo->getBasename()[0] == '.' || $fileInfo->getExtension() !== 'php') {
                    continue;
                }

                if (!$object = $this->getObject($fileInfo->getPathname())) {
                    continue;
                }

                $objects[$object->name] = $object;
            }
        }

        $objects[self::ADMIN_AREA] = (object) [
            'name'        => self::ADMIN_AREA,
            'shortName'   => self::ADMIN_AREA,
            'actions'     => ['access'],
            'description' => self::ADMIN_AREA_DESCRIPTION,
            'options'     => [],
            'path'        => null,
        ];

        $objects[self::ADMIN_TEST] = (object) [
            'name'        => self::ADMIN_TEST,
            'shortName'   => self::ADMIN_TEST,
            'actions'     => ['access'],
            'description' => self::ADMIN_TEST,
            'options'     => [],
            'path'        => null,
        ];


        foreach ($objects as $key => $object) {
            $resource = new Acl\Resource($key, $object->description);

            if (!isset($object->actions) || !is_array($object->actions)) {
                $object->actions = [];
            }

            $acl->addResource($resource, $object->actions);
        }

        return $objects;
    }

}