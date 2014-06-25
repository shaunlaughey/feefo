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
 * @method integer getBad()       Bad service rating
 * @method integer getPoor()      Poor service rating
 * @method integer getGood()      Good service rating
 * @method integer getExcellent() Excellent service rating
 *
 * @method \aw\feefo\SummaryTotal setBad(integer)       Set the Bad service rating
 * @method \aw\feefo\SummaryTotal setPoor(integer)      Set the Poor service rating
 * @method \aw\feefo\SummaryTotal setGood(integer)      Set the Good service rating
 * @method \aw\feefo\SummaryTotal setExcellent(integer) Set the Excellent service rating
 */
class SummaryTotal extends FeefoBase
{
    /**
     * Bad rating
     *
     * @var integer
     */
    protected $bad = 0;
    
    /**
     * Poor rating
     *
     * @var integer
     */
    protected $poor = 0;
    
    /**
     * Good rating
     *
     * @var integer
     */
    protected $good = 0;
    
    /**
     * Excellent rating
     *
     * @var integer
     */
    protected $excellent = 0;
    
    /**
     * Return total amount of ratings
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->getBad()
            + $this->getPoor()
            + $this->getGood()
            + $this->getExcellent();
    }
}