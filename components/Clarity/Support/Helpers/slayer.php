<?php
/**
 * PhalconSlayer\Framework.
 *
 * @copyright 2015-2016 Daison Carino <daison12006013@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://docs.phalconslayer.com
 */
if (! function_exists('di')) {

    /**
     * This calls our default dependency injection.
     *
     * @param string|mixed $alias The service provider alias
     * @return mixed
     */
    function di($alias = null)
    {
        $default = Phalcon\DI::getDefault();

        if (is_string($alias)) {
            return $default->get($alias);
        }

        # if the alias is array then we must check the array
        # passed in
        if (is_array($alias)) {
            if (
                ! isset($alias[0]) ||
                ! isset($alias[1])
            ) {
                throw new InvalidArgumentException('Provider alias or callback not found');
            }

            $default->set(
                $alias[0],
                $alias[1],
                isset($alias[2]) ? $alias[2] : false
            );

            return $default->get($alias[0]);
        }

        # or just return the default thing
        return $default;
    }
}
