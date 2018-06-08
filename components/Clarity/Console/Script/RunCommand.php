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
namespace Components\Clarity\Console\Script;

use Components\Clarity\Console\CLI;
use Components\Clarity\Console\Brood;
use Symfony\Component\Console\Input\InputArgument;

/**
 * A console command that executes a script.
 */
class RunCommand extends Brood
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'run';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Automated scripts to be run.';

    /**
     * {@inheritdoc}
     */
    public function slash()
    {
        $script = $this->input->getArgument('script');

        $lists = config()->script->toArray();

        if (isset($lists[$script]) === false) {
            $this->error("\nWe can't find `".$script.'` in the lists of script.'."\n");

            return;
        }

        foreach ($lists[$script] as $selected) {
            if (is_callable($selected)) {
                CLI::handleCallback($selected);
                continue;
            }

            if (is_array($selected)) {
                CLI::bash($selected);
                continue;
            }

            CLI::process($selected);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function arguments()
    {
        return [
            ['script', InputArgument::REQUIRED, 'Automated script to be use'],
        ];
    }
}
