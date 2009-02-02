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
class Zend_Debug_Include_Manager
{
    /**
     * Included and required files.
     *
     * @var array
     */
    protected $_includedFiles;
    
    /**
     * File output name.
     *
     * @var string
     */
    protected $_filename;
    
    /**
     * Output directory.
     *
     * @var string
     */
    protected $_outputDir = '/tmp';
    
    /**
     * @var Zend_Debug_Include_Writer_Abstract|null
     */
    protected $_writer = null;
    
    /**
     * @var Zend_Debug_Include_Adapter_Abstract|null
     */
    protected $_adapter = null;
    
    /**
     * Class constructor.
     *
     * @param string $filename
     * @return void
     */
    public function __construct($filename = null)
    {
        $this->setFilename($filename);
        $this->registerShutdownFunction($this, 'shutdown');
    }
    
    /**
     * Set the directory where the output will be written to. Make sure this directory
     * has write permissions.
     *
     * @param string $dir Output directory
     * @return Zend_Debug_Include_Manager
     * @throws Zend_Debug_Include_Exception
     */
    public function setOutputDir($dir)
    {
        if (!is_writable($dir)) {
            throw new Zend_Debug_Include_Exception('Output directory is not writable: ' . $dir);
        }
        
        $this->_outputDir = $dir;
        return $this;
    }

    /**
     * Return the output directory.
     *
     * @return string
     */
    public function getOutputDir()
    {
        return $this->_outputDir;
    }
    
    /**
     * Set the name of the output file.
     *
     * @param string $filename
     * @return Zend_Debug_Include_File
     */
    public function setFilename($filename)
    {
        $this->_filename = $filename;
        return $this;
    }

    /**
     * Return the file output name.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->_filename;
    }
    
    /**
     * Set the adapter object.
     *
     * @param Zend_Debug_Include_Adapter_Abstract
     * @return Zend_Debug_Include_Manager
     */
    public function setAdapter(Zend_Debug_Include_Adapter_Abstract $adapter)
    {
        $this->_adapter = $adapter;
        return $this;
    }

    /**
     * Return the adapter object.
     *
     * @return Zend_Debug_Include_Adapter_Abstract
     * @throws Zend_Debug_Include_Exception
     */
    public function getAdapter()
    {
        if (null === $this->_adapter) {
            throw new Zend_Debug_Include_Exception('Invalid adapter object.');
        }
        return $this->_adapter;
    }
    
    /**
     * Set a writer object.
     *
     * @param Zend_Debug_Include_Writer_Interface
     * @return Zend_Debug_Include_Manager
     */
    public function setWriter(Zend_Debug_Include_Writer_Interface $writer)
    {
        $this->_writer = $writer;
        return $this;
    }

    /**
     * Return the writer object.
     *
     * @return Zend_Debug_Include_Writer_Interface
     */
    public function getWriter()
    {
        return $this->_writer;
    }
    
    /**
     * Set an array with the names of included and required files.
     *
     * @return void
     */
    public function setIncludedFiles()
    {
        $this->_includedFiles = get_included_files();
    }

    /**
     * Return the included and required files.
     *
     * @return array
     */
    public function getIncludedFiles()
    {
        if (!is_array($this->_includedFiles)) {
            $this->setIncludedFiles();
        }
        return $this->_includedFiles;
    }
    
    /**
     * Register the method to be executed when script processing is complete. Multiple calls 
     * to register_shutdown_function() can be made, and each will be called in the same order 
     * as they were registered. 
     *
     * @param string $class
     * @param string $method
     * @return void
     */
    public function registerShutdownFunction($class, $method)
    {
        register_shutdown_function(array($class, $method));
    }
    
    /**
     * Execute last operation before the script is complete.
     * 
     * @return void
     */
    public function shutdown() 
    {
        $adapter = $this->getAdapter();
        $filename = $this->getFilename();
        if (null === $filename) {
            $filename = $adapter->getOption('filename');
        }
        
        $file = $this->getOutputDir() . DIRECTORY_SEPARATOR . $filename;
        $files = $this->load($file);
        
        $data = $adapter->search($files, $this->getIncludedFiles());
        if (null !== $this->getWriter()) {
            $this->getWriter()->write($data, $file);
        } else {
            $this->write($data, $file);
        }
    }
    
    /**
     * Load entire file into an array or string.
     *
     * @param string $file
     * @return mixed array|string
     */
    public function load($file)
    {
        if (true === (boolean) $this->getAdapter()->getOption('return_array')) {
            $contents = array();
            if (file_exists($file)) {
                $contents = file($file);
            }            
        } else {
            $contents = file_get_contents($file);
        }
        
        return $contents;
    }
    
    /**
     * Write data to a file.
     *
     * @param string $data
     * @param string $file
     * @return boolean Returns true on success, or false on failure
     */
    public function write($data, $file)
    {
        $mode = 'a';
        if (false === (boolean) $this->getAdapter()->getOption('append_mode')) {
            $mode = 'w';
        }
        
        $result = false;
        if (!file_exists($file)) {
            $result = file_put_contents($file, $data);
        } elseif (!empty($data) && $handle = @fopen($file, $mode)) {
            $result = @fwrite($handle, $data);
            fclose($handle);
        }
        
        return $result;
    }
}