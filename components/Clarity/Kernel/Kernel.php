<?php
/**
 * PhalconSlayer\Framework.
 *
 * @copyright 2015-2016 Daison Carino <daison12006013@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://docs.phalconslayer.com
 */

/**
 */
namespace Components\Clarity\Kernel;

/**
 * Acts like a manager that initializes all the configurations/environments and module.
 */
class Kernel
{
    use KernelTrait;

    /**
     * The dependency injection.
     *
     * @var mixed
     */
    private $di;

    /**
     * The configured environment.
     *
     * @var string
     */
    private $env;

    /**
     * The path provided.
     *
     * @var mixed
     */
    private $paths;

    /**
     * Set the paths.
     *
     * @param mixed $paths
     */
    public function setPaths($paths)
    {
        $this->paths = $paths;

        return $this;
    }

    /**
     * Set the environment.
     *
     * @param string $env
     */
    public function setEnvironment($env)
    {
        $this->env = $env;

        return $this;
    }

    /**
     * Get the environment.
     *
     * @return string Current environment
     */
    public function getEnvironment()
    {
        return $this->env;
    }

    /**
     * Register modules.
     *
     * @return mixed
     */
    public function modules()
    {
        config(['modules' => di('module')->all()]);

        di('application')->registerModules(config()->modules->toArray());

        return $this;
    }

    /**
     * Render the system content.
     */
    public function render()
    {
        echo di('application')->handle()->getContent();
    }

    /**
     * Here, you will be loading the system by defining the module.
     *
     * @param  string $module_name The module name
     * @return mixed
     */
    public function run($module_name)
    {
        di('application')->setDefaultModule($module_name);

        di($module_name)->afterModuleRun();

        $this->loadServices($after_module = true);

        return $this;
    }
}
