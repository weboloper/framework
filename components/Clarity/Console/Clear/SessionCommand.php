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
namespace Components\Clarity\Console\Clear;

use Components\Clarity\Console\Brood;

/**
 * A console command that clears the session storage.
 */
class SessionCommand extends Brood
{
    use ClearTrait;

    /**
     * {@inheritdoc}
     */
    protected $name = 'clear:session';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Clear the storage/session folder';

    /**
     * {@inheritdoc}
     */
    public function slash()
    {
        $this->clear(storage_path('session'));
    }
}
