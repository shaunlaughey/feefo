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
 * @method string           getId()                 Returns the feedback ID
 * @method string           getComment()            Returns the customer comment
 * @method string           getFacebookShareLink()  Returns the facebook sharelink
 * @method \DateTime        getReviewDate()         Returns the review date
 * @method string           getReviewRating()       Returns the review rating
 * @method string           getLink()               Returns the Product link
 * @method string           getProductCode()        Returns the product code
 * @method string           getProductDescription() Returns the product description
 * @method \aw\feefo\Rating getProductRating()      Returns the product rating
 * @method \aw\feefo\Rating getServiceRating()      Returns the service rating
 * @method string           getReadMoreUrl()        Returns the read more url
 * @method string           getCategory()           Returns the category
 *
 * @method \aw\feefo\Feedback setId(integer)                Set the feedback id
 * @method \aw\feefo\Feedback setComment(string)            Set the customer comment
 * @method \aw\feefo\Feedback setProductDescription(string) Set the product description
 * @method \aw\feefo\Feedback setFacebookShareLink(string)  Set the facebook sharelink
 * @method \aw\feefo\Feedback setReviewDate(\DateTime)      Set the review date
 * @method \aw\feefo\Feedback setReviewRating(string)       Set the review rating
 * @method \aw\feefo\Feedback setLink(string)               Set the Product link
 * @method \aw\feefo\Feedback setProductCode(string)        Set the product code
 * @method \aw\feefo\Feedback setReadMoreUrl(string)        Set the read more url
 * @method \aw\feefo\Feedback setCategory(string)           Set the product category
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
     * @var \DateTime
     */
    protected $reviewDate;
    
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
     * @var \aw\feefo\Rating
     */
    protected $productRating;
    
    /**
     * Service Rating.
     *
     * @var \aw\feefo\Rating
     */
    protected $serviceRating;
    
    /**
     * Read More Url
     *
     * @var string
     */
    protected $readMoreUrl = '';
    
    /**
     * Feefo category.
     *
     * @var string
     */
    protected $category = '';
    
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
    
    /**
     * Set the service rating
     *
     * @param string $rating Service rating, should be --, -, + or ++
     *
     * @throws \Exception if service rating is incorrect
     * 
     * @return Feefo
     */
    public function setServiceRating($rating)
    {
        $this->serviceRating = new \aw\feefo\Rating($rating);
        
        return $this;
    }
    
    /**
     * Set the service comment
     * 
     * @param string $comment Service Comment
     * 
     * @return \aw\feefo\Feedback
     * 
     * @throws Exception
     */
    public function setServiceComment($comment)
    {
        if ($this->getServiceRating()) {
            $this->getServiceRating()->setComment($comment);
            
            return $this;
        }
        
        throw new \Exception('Service rating not set');
    }
    
    /**
     * Return service comment
     * 
     * @return string
     */
    public function getServiceComment()
    {
        if ($this->getServiceRating()) {
            return $this->getServiceRating()->getComment();
        }
        
        return '';
    }
    
    /**
     * Set the product rating
     *
     * @param string $rating Service rating, should be --, -, + or ++
     *
     * @throws \Exception if service rating is incorrect
     * 
     * @return Feefo
     */
    public function setProductRating($rating)
    {
        $this->productRating = new \aw\feefo\Rating($rating);
        
        return $this;
    }
    
    /**
     * Set the product comment
     * 
     * @param string $comment Product Comment
     * 
     * @return \aw\feefo\Feedback
     * 
     * @throws Exception
     */
    public function setProductComment($comment)
    {
        if ($this->getProductRating()) {
            $this->getProductRating()->setComment($comment);
            
            return $this;
        }
        
        throw new \Exception('Product rating not set');
    }
    
    /**
     * Return product comment
     * 
     * @return string
     */
    public function getProductComment()
    {
        if ($this->getProductRating()) {
            return $this->getProductRating()->getComment();
        }
        
        return '';
    }
}