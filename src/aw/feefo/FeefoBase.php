<?php

namespace aw\feefo;

/**
 * This is the base class for the feefo objects.
 *
 * PHP Version 5.3
 * 
 * @category    Feefo
 * @package     AW
 * @author      Alex Wyett
 * @copyright   2014 Alex Wyett
 * @license     http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link        http://www.carltonsoftware.co.uk
 * @link        http://www.feefo.com/feefo/page.jsp?page=T9
 */
class FeefoBase
{
    /**
     * Generic getter/setter
     * 
     * @param string $name Name of property
     * @param array  $args Function arguments
     *
     * @throws \Exception
     * 
     * @return void 
     */
    public function __call($name, $args = array())
    {
        // This call method is only for accessors
        if (strlen($name) > 3) {
            // Get the property
            $property = substr($name, 3, strlen($name));

            // All properties will be camelcase, make first, letter lowercase
            $property[0] = strtolower($property[0]);
            
            // Accessor method
            $accessor = substr($name, 0, 3);
            
            // Only run code for setters and getters
            if (in_array($accessor, array('set', 'get'))) {
            
                // Check if property is publically accessible
                if (property_exists($this, $property)) {
                    // Only allow protected variables to be accessed
                    $reflector = new \ReflectionClass(get_class($this));
                    $prop = $reflector->getProperty($property);
                    if ($prop->isPrivate()) {
                        throw new \Exception(
                            sprintf(
                                'Unable to %s, property is private: %s:%s',
                                $accessor,
                                __CLASS__,
                                $property
                            )
                        );
                    }
                } else {
                    throw new \Exception(
                        'Unknown method called: ' . __CLASS__ . ':' . $name
                    );
                }

                switch ($accessor) {
                case 'set':
                    $this->$property = $args[0];
                    return $this;
                case 'get':
                    return $this->$property;
                }
            }
        }
    }
}