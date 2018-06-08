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
namespace Components\Clarity\Contracts\Flysystem;

/**
 * An adapter interface for flysystem.
 */
interface AdapterInterface
{
    /**
     * Get the adapter.
     *
     * @return mixed
     */
    public function getAdapter();
}
