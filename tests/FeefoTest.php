<?php

require_once '../autoload.php';

/**
 * Feefo Integration PHPUnit Test case
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
class FeefoTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test image button object
     * 
     * @return void
     */
    public function testNewFeefoObject()
    {
        $feefo = new aw\feefo\Feefo('www.feefouserdomain.com', 'feefopassword');

        // Accessor unit tests
        
        // Website
        $this->assertEquals('www.feefouserdomain.com', $feefo->getWebsite());
        
        // Password
        $this->assertEquals('feefopassword', $feefo->getPassword());
        
        // Set the other accessors
        $accessors = array(
            'name' => 'customer name',
            'orderRef' => 'orderReference',
            'email' => 'email@emailaddress.com',
            'description' => 'This is a description of the customers order',
            'category' => 'Feefo Category',
            'serviceRating' => '+',
            'serviceComment' => 'This is a comment that the customer has supplied',
        );
        foreach ($accessors as $accessor => $value) {
            $property = ucfirst($accessor);
            $setter = 'set' . $property;
            $getter = 'get' . $property;
            
            $feefo->$setter($value);
            $this->assertEquals($value, $feefo->$getter());
        }
        
        // Test the submission url
        $this->assertEquals(
            'https%3A%2F%2Fwww.feefo.com%2Ffeefo%2Fentersaleremotely.jsp?website=www.feefouserdomain.com&password=feefopassword&orderref=orderReference&name=customer+name&email=email%40emailaddress.com&description=This+is+a+description+of+the+customers+order&servicerating=%2B&servicecomment=This+is+a+comment+that+the+customer+has+supplied&category=Feefo+Category',
            $feefo->getCommentUrl()
        );
    }
    
    /**
     * Test an email exception
     *
     * @expectedException Exception
     */
    public function testEmailException()
    {
        $feefo = new aw\feefo\Feefo('www.feefouserdomain.com', 'feefopassword');
        $feefo->setEmail('invalidemail');
    }
    
    /**
     * Test an invalid service exception
     *
     * @expectedException Exception
     */
    public function testServiceRatingException()
    {
        $feefo = new aw\feefo\Feefo('www.feefouserdomain.com', 'feefopassword');
        $feefo->setServiceRating('+++');
    }
    
    /**
     * Test an invalid service exception
     *
     * @dataProvider getServiceRatings
     */
    public function testServiceRating($rating)
    {
        $feefo = new aw\feefo\Feefo('www.feefouserdomain.com', 'feefopassword');
        $feefo->setServiceRating($rating);
        
        $this->assertEquals($rating, $feefo->getServiceRating());
    }
    
    /**
     * Return valid service ratings
     *
     * @return array
     */
    public function getServiceRatings()
    {
        return array(
            array(
                'rating' => '+'
            ),
            array(
                'rating' => '++'
            ),
            array(
                'rating' => '-'
            ),
            array(
                'rating' => '--'
            )
        );
    }
}