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
class Zend_Debug_Include_Adapter_Package extends Zend_Debug_Include_Adapter_Abstract
{
    protected $_libraries = array('Zend');
    
    /**
     * Class constructor.
     *
     * @return void
     */
    public function __construct(array $libraries = null)
    {
        $this->setOption('filename', 'zf-packages.txt');
        $this->setOption('append_mode', true);
        if (null !== $libraries) {
            $this->_libraries = $libraries;
        }
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
        $pattern = '/(' . implode('|', $this->_libraries) . ')/';
        $data = '';
        
        foreach ($includedFiles as $key => $value) {
            preg_match($pattern, $value, $matches);
            if (!array_key_exists(0, $matches)) {
                continue;
            }
            if (!$filename = strrchr($value, $matches[0])) {
                continue;
            }
            $package = explode(DIRECTORY_SEPARATOR, $filename);
            if (!isset($package[1])) {
                continue;
            }
            $packageName = $package[0] . DIRECTORY_SEPARATOR . $package[1] . PHP_EOL;
            if (!in_array($packageName, $files)) {
                $files[] = $packageName;
                $data .= $packageName;
            }
        }
        
        return $data;
    }
}
