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
class Zend_Debug_Include_Adapter_Url extends Zend_Debug_Include_Adapter_Abstract
{
    /**
     * Class constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $filename = str_replace('/', '_', ltrim($_SERVER['PHP_SELF'], '/')) . '.txt';
        $this->setOption('filename', $filename);
    }
    
    /**
     * Search array of files.
     *
     * @param mixed array|string $files Files included in previous requests
     * @param array $includedFiles Files included in current request
     * @return string 
     */
    public function search($files, array $includedFiles)
    {
        $data  = 'URI:   http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . PHP_EOL;
        $data .= 'Date:  ' . date('D M j G:i:s T Y') . PHP_EOL . PHP_EOL;
        
        foreach ($includedFiles as $key => $value) {
            if (!in_array($value, $files)) {
                $data .= $value . PHP_EOL;
            }
        }
        
        return $data;
    }
}
