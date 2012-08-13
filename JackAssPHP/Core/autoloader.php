<?php

/**
 * This is the work of the author stated below, i added only a few comments to match
 * the PEAR coding standard.
 *
 * @category JackAssPHP
 * @package  JackAssPHP
 */

namespace JackAssPHP\Core;


/**
 * SplClassLoader implementation that implements the technical interoperability
 * standards for PHP 5.3 namespaces and class names.
 *
 * http://groups.google.com/group/php-standards/web/final-proposal
 * https://gist.github.com/221634
 *     Example which loads classes for the Doctrine Common package in the
 *     Doctrine\Common namespace.
 *     $classLoader = new SplClassLoader('Doctrine\Common', '/path/to/doctrine');
 *     $classLoader->register();
 *
 * @author Jonathan H. Wage <jonwage@gmail.com>
 * @author Roman S. Borschel <roman@code-factory.org>
 * @author Matthew Weier O'Phinney <matthew@zend.com>
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 * @author Fabien Potencier <fabien.potencier@symfony-project.org>
 */
class SplClassLoader
{
    private $_fileExtension = '.php';
    private $_namespace;
    private $_includePath;
    private $_namespaceSeparator = '\\';

    /**
     * Creates a new <tt>SplClassLoader</tt> that loads classes of the
     * specified namespace.
     *
     * @param string $ns          The namespace to use.
     * @param string $includePath Path to files in selected Namespace
     */
    public function __construct ( $ns = null, $includePath = null )
    {
        $this->_namespace = $ns;
        $this->_includePath = $includePath;
    }

    /**
     * Sets the namespace separator used by classes in the namespace of
     * this class loader.
     *
     * @param string $sep The separator to use.
     *
     * @return void
     */
    public function setNamespaceSeparator ( $sep )
    {
        $this->_namespaceSeparator = $sep;
    }

    /**
     * Gets the namespace seperator used by classes in the namespace of this
     * class loader.
     *
     * @return string Namespace separator
     */
    public function getNamespaceSeparator ()
    {
        return $this->_namespaceSeparator;
    }

    /**
     * Sets the base include path for all class files in the namespace of this
     * class loader.
     *
     * @param string $includePath
     *
     * @return void
     */
    public function setIncludePath ( $includePath )
    {
        $this->_includePath = $includePath;
    }

    /**
     * Gets the base include path for all class files in the namespace of this class
     * loader.
     *
     * @return string $includePath
     */
    public function getIncludePath ()
    {
        return $this->_includePath;
    }

    /**
     * Sets the file extension of class files in the namespace of this class loader.
     *
     * @param string $fileExtension
     *
     * @return void
     */
    public function setFileExtension ( $fileExtension )
    {
        $this->_fileExtension = $fileExtension;
    }

    /**
     * Gets the file extension of class files in the namespace of this class loader.
     *
     * @return string $fileExtension
     */
    public function getFileExtension ()
    {
        return $this->_fileExtension;
    }

    /**
     * Installs this class loader on the SPL autoload stack.
     *
     * @return void
     */
    public function register ()
    {
        spl_autoload_register(array( $this, 'loadClass' ), true);
    }

    /**
     * Uninstalls this class loader from the SPL autoloader stack.
     *
     * @return void
     */
    public function unregister ()
    {
        spl_autoload_unregister(array( $this, 'loadClass' ));
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $className The name of the class to load.
     *
     * @return void
     */
    public function loadClass ( $className )
    {
        if ( null === $this->_namespace || $this->_namespace . $this->_namespaceSeparator === substr($className, 0, strlen($this->_namespace . $this->_namespaceSeparator)) ) {
            $fileName = '';
            $namespace = '';
            if ( false !== ($lastNsPos = strripos($className, $this->_namespaceSeparator)) ) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName = str_replace($this->_namespaceSeparator, DS, $namespace) . DS;
            }
            $fileName .= str_replace('_', DS, $className) . $this->_fileExtension;
            if ( $this->_includePath !== null ) {
                require $this->_includePath . DS . $fileName;
            } else {
                require $fileName;
            }
        }
    }

}