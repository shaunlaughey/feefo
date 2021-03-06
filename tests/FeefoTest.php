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
        $this->assertEquals('www.feefouserdomain.com', $feefo->getLogon());
        
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
            'serviceComment' => 'This is a comment that the customer has supplied about the service',
            'productRating' => '+',
            'productComment' => 'This is a comment that the customer has supplied about the product',
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
            'http://www.feefo.com/feefo/entersaleremotely.jsp?logon=www.feefouserdomain.com&password=feefopassword&email=email%40emailaddress.com&name=customer+name&description=This+is+a+description+of+the+customers+order&orderref=orderReference&servicecomment=This+is+a+comment+that+the+customer+has+supplied+about+the+service&productcomment=This+is+a+comment+that+the+customer+has+supplied+about+the+product&category=Feefo+Category',
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
        
        $this->assertEquals($rating, $feefo->getServiceRating()->getRating());
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