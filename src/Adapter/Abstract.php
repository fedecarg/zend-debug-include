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
abstract class Zend_Debug_Include_Adapter_Abstract
{ 
    /**
     * Options
     *
     * - filename: File output name.
     * - append_mode: Open "filename" in append mode.
     * - return_array: Return the contents of "filename" as an array. 
     *
     * @var array
     */
    protected $_options = array(
        'filename'     => 'zend-framework.txt',
        'append_mode'  => false,
        'return_array' => true,
    );

    /**
     * Set an option value.
     *
     * @param string $name Name of the option
     * @param mixed $value Value of the option
     * @return Zend_Debug_Include_Adapter_Abstract
     * @throws Zend_Debug_Include_Exception
     */
    public function setOption($name, $value)
    {
        if (!array_key_exists($name, $this->_options)) {
            throw new Zend_Debug_Include_Exception('Incorrect option name:' . $name);
        }
        $this->_options[$name] = $value;
        return $this;
    }
    
    /**
     * Get an option value.
     *
     * @param string $name Name of the option
     * @return mixed Option value
     * @throws Zend_Debug_Include_Exception
     */
    public function getOption($name)
    {
        if (!array_key_exists($name, $this->_options)) {
            throw new Zend_Debug_Include_Exception('Incorrect option name:' . $name);
        }
        return $this->_options[$name];
    }
    
    /**
     * Search array of files.
     *
     * @param mixed array|string $files Files included in previous requests
     * @param array $includedFiles Files included in current request
     * @return string 
     */
    abstract public function search($files, array $includedFiles);
}