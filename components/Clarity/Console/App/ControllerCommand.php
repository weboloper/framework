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
namespace Components\Clarity\Console\App;

use Components\Clarity\Console\Brood;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * A console command that generates a controller template.
 */
class ControllerCommand extends Brood
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'app:controller';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Generate a new controller';

    /**
     * Get the application path.
     *
     * @return string
     */
    protected function getAppPath()
    {
        return config()->path->app;
    }

    /**
     * Get the module path.
     *
     * @return string
     */
    protected function getModulePath()
    {
        return url_trimmer($this->getAppPath().'/'.$this->getModuleName());
    }

    /**
     * Get the base path.
     *
     * @return string
     */
    protected function getBasePath()
    {
        return getcwd();
    }

    /**
     * Get the namespace to use.
     *
     * @return string
     */
    protected function getNamespace()
    {
        return url_trimmer(
            realpath($this->getAppPath()).'/'.$this->getModuleName().'/Controllers'
        );
    }

    /**
     * Get the controller name.
     *
     * @param $is_path
     * @return string
     */
    protected function getControllerName($is_path = true)
    {
        $ret = '%s';

        if ($is_path) {
            $ret = 'Controllers/%sController.php';
        }

        return sprintf(
            $ret,
            studly_case(
                str_slug($this->input->getArgument('name'), '_')
            )
        );
    }

    /**
     * Get the module name.
     *
     * @return string
     */
    protected function getModuleName($namespace = true)
    {
        $module = $this->input->getArgument('module');

        if (! $namespace) {
            return $module;
        }

        return studly_case(str_slug($module, '_'));
    }

    /**
     * Get the controller content stub.
     *
     * @return string
     */
    protected function getControllerStub()
    {
        return file_get_contents(__DIR__.'/stubs/controller/controller.stub');
    }

    /**
     * Get the functions content stub.
     *
     * @return string
     */
    protected function getFunctionsStub()
    {
        return file_get_contents(__DIR__.'/stubs/controller/functions.stub');
    }

    /**
     * Get the controller functions.
     *
     * @return string
     */
    protected function getControllerFunctions()
    {
        if ($this->input->getOption('emptify')) {
            return '';
        }

        return stubify(
            $this->getFunctionsStub(),
            ['folderName' => $this->input->getArgument('name')]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function slash()
    {
        $app_filesystem = flysystem_manager($this->getAppPath());

        $raw_module = $this->getModuleName(false);
        $module = $this->getModuleName();

        # check if module exists, throw error if it doesn't exists
        if ($app_filesystem->has($module) === false) {
            $this->error("Module [$module] not found");

            return;
        }

        $this->info('Crafting Controller...');

        $controller = $this->getControllerName();
        # check controller file if exists, throw error if exists
        if ($app_filesystem->has($module.'/'.$controller)) {
            $this->error(
                "     Controller [{$this->input->getArgument('name')}] ".
                "already exists in your Module [{$this->input->getArgument('module')}]"
            );

            return;
        }

        # get the controller stub and stubify the {controllerName}
        # based on argument controller name
        $buff = stubify(
            $this->getControllerStub(),
            [
                'namespace' => path_to_namespace(

                    # here, we must trim the $namespace by
                    # getting the base path to be our matching trimmer
                    str_replace($this->getBasePath(), '', $this->getNamespace())
                ),
                'controllerName' => $this->getControllerName(false),
                'controllerFunctions' => $this->getControllerFunctions(),
            ]
        );

        # now write the content based on $controller path
        $module_filesystem = flysystem_manager($this->getModulePath());
        $module_filesystem->put($controller, $buff);

        $this->info('     '.$controller.' created!');
    }

    /**
     * {@inheritdoc}
     */
    protected function arguments()
    {
        $arguments = [
            ['name', InputArgument::REQUIRED, 'The controller name'],
            ['module', InputArgument::REQUIRED, 'The module to link on'],
        ];

        return $arguments;
    }

    /**
     * {@inheritdoc}
     */
    protected function options()
    {
        return [
            [
                'emptify',
                null,
                InputOption::VALUE_NONE,
                'Remove all pre-defined functions',
            ],
        ];
    }
}
