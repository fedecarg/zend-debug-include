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
class Zend_Debug_Include_Adapter_File extends Zend_Debug_Include_Adapter_Abstract
{
    /**
     * Class constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setOption('filename', 'zf-files.txt');
        $this->setOption('append_mode', true);
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
        $data = '';
        foreach ($includedFiles as $key => $value) {
            $zfFile = $value . PHP_EOL;
            if (!in_array($zfFile, $files)) {
                $data .= $zfFile;
            }
        }
        
        return $data;
    }
}
