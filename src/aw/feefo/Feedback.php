<?php

namespace aw\feefo;

/**
 * This class represents a feefo feedback.
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
 * @method string getId()                 Returns the feedback ID
 * @method string getComment()            Returns the customer comment
 * @method string getFacebookShareLink()  Returns the facebook sharelink
 * @method string getReviewDate()         Returns the review date
 * @method string getReviewRating()       Returns the review rating
 * @method string getLink()               Returns the Product link
 * @method string getProductCode()        Returns the product code
 * @method string getProductDescription() Returns the product description
 * @method string getServiceRating()      Returns the service rating
 * @method string getReadMoreUrl()        Returns the read more url
 *
 * @method \aw\feefo\Feedback setId(integer)                Set the feedback id
 * @method \aw\feefo\Feedback setComment(string)            Set the customer comment
 * @method \aw\feefo\Feedback setProductDescription(string) Set the product description
 * @method \aw\feefo\Feedback setFacebookShareLink(string)  Set the facebook sharelink
 * @method \aw\feefo\Feedback setReviewDate(string)         Set the review date
 * @method \aw\feefo\Feedback setReviewRating(string)       Set the review rating
 * @method \aw\feefo\Feedback setLink(string)               Set the Product link
 * @method \aw\feefo\Feedback setProductCode(string)        Set the product code
 * @method \aw\feefo\Feedback setReadMoreUrl(string)        Set the read more url
 */
class Feedback extends FeefoBase
{
    /**
     * Feedback ID
     *
     * @var integer
     */
	protected $id = 0;
    
    /**
     * Customer Comment
     *
     * @var string
     */
	protected $comment = '';
    
    /**
     * Product Description
     *
     * @var string
     */
	protected $productDescription = '';
    
    /**
     * Sharelink
     *
     * @var string
     */
	protected $facebookShareLink = '';
    
    /**
     * Review Date
     *
     * @var string
     */
	protected $reviewDate = '';
    
    /**
     * Review Rating
     *
     * @var string
     */
	protected $reviewRating = '';
    
    /**
     * Link
     *
     * @var string
     */
	protected $link = '';
    
    /**
     * Product code
     *
     * @var string
     */
	protected $productCode = '';
    
    /**
     * Product Rating.
     *
     * @var string
     */
	protected $productRating = '';
    
    /**
     * Read More Url
     *
     * @var string
     */
	protected $readMoreUrl = '';
    
    /**
     * Service Rating.
     *
     * @var string
     */
	protected $serviceRating = '';
    
    /**
     * Factory method
     *
     * @param object $object JSON Object
     *
     * @return \aw\feefo\Feedback
     */
    public static function factory($object)
    {
        $feedback = new \aw\feefo\Feedback();
        $feedback->setId($object->FEEDBACKID);
        $feedback->setComment($object->CUSTOMERCOMMENT);
        $feedback->setProductDescription($object->DESCRIPTION);
        $feedback->setReviewDate(new \DateTime($object->HREVIEWDATE));
        $feedback->setReviewRating($object->HREVIEWRATING);
        $feedback->setLink($object->LINK);
        $feedback->setProductCode($object->PRODUCTCODE);
        $feedback->setProductRating(
            new \aw\feefo\Rating($object->PRODUCTRATING)
        );
        $feedback->setReadMoreUrl($object->READMOREURL);
        $feedback->setServiceRating(
            new \aw\feefo\Rating($object->SERVICERATING)
        );
        $feedback->setFacebookShareLink($object->FACEBOOKSHARELINK);
        
        return $feedback;
    }
}