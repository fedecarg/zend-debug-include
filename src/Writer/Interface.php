<?php
/**
 * Zend Framework
 *
 * @category   Zend
 * @package    Zend_Debug
 * @author     Federico Cargnelutti <fedecarg@gmail.com>
 * @copyright  Copyright (c) 2009 Federico Cargnelutti <fedecarg@gmail.com>
 * @license    New BSD License
 * @version    $Id: $
 */

/**
 * @category   Zend
 * @package    Zend_Debug
 * @author     Federico Cargnelutti <fedecarg@gmail.com>
 * @copyright  Copyright (c) 2009 Federico Cargnelutti <fedecarg@gmail.com>
 * @license    New BSD License
 * @version    $Id: $
 */
interface Zend_Debug_Include_Writer_Interface
{
    /**
     * Write data to a file.
     *
     * @param string $data
     * @param string $file
     * @return boolean Returns true on success, or false on failure
     */
    public function write($data, $file);
}
