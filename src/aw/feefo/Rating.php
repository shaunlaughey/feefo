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
 * @method string getRating()  Service Rating
 * @method string getComment() Service Comment
 *
 * @method \aw\feefo\Rating setComment(string) Set the Service comment
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
     * Service Comment
     *
     * @var string
     */
    protected $comment = '';
    
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
     * Rating accessor
     * 
     * @param string $rating Attempt to set a new rating
     * 
     * @return \aw\feefo\Rating
     * 
     * @throws \Exception
     */
    public function setRating($rating)
    {
        if (in_array($rating, array('+', '++', '-', '--'))) {
            $this->rating = $rating;
            
            return $this;
        }
        
        throw new \Exception('Invalid rating specified');
    }
    
    /**
     * Returns the string representation of the feefo rating
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getRating();
    }
}