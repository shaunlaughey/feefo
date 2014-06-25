<?php

namespace aw\feefo;

/**
 * This class represents a feefo summary total rating.
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
 *
 * @method string getRating() Service Rating
 *
 * @method \aw\feefo\Rating setRating(string) Set the Service rating
 */
class Rating extends FeefoBase
{
    /**
     * Service Rating
     *
     * @var string
     */
    protected $rating = '';
    
    /**
     * Constructor
     *
     * @param string $rating Feefor rating
     *
     * @return void
     */
    public function __construct($rating)
    {
        $this->setRating($rating);
    }
    
    /**
     * Return the string representation of the feefo rating
     *
     * @return string
     */
    public function getRatingString()
    {
        switch ($this->getRating()) {
        case '--':
            return 'Bad';
        case '-':
            return 'Poor';
        case '+':
            return 'Good';
        case '++':
            return 'Excellent';
        }
    }
    
    /**
     * Returns the string representation of the feefo rating
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getRatingString();
    }
}