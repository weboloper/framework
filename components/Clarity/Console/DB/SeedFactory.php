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
namespace Components\Clarity\Console\DB;

use Components\Clarity\Support\DB\Factory;
use Components\Clarity\Console\Brood;

/**
 * A console command that fills/seeds the database.
 */
class SeedFactory extends Brood
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'seed:factory';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Seed based on the factories';

    /**
     * {@inheritdoc}
     */
    public function slash()
    {
        $factory = new Factory($this);
        $files = folder_files(config()->path->database.'factories');

        if (! empty($files)) {
            foreach ($files as $file) {
                $this->comment('Processing '.basename($file).'...');

                require $file;

                $this->info('Done.'."\n");
            }
        }

        return $this;
    }
}
