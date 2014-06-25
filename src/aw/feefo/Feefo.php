<?php

namespace aw\feefo;

/**
 * This class allows some PHP code to interact with the 
 * {@link http://www.feefo.com Feefo} comment aggregation service.
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
class Feefo extends FeefoBase
{
    /**
     * Feefo username. Normally a domain name address
     *
     * @var string
     */
	protected $website;
    
    /**
     * Feefo password. Does what it says on the tin.
     *
     * @var string
     */
	protected $password;
    
    /**
     * Feefo submission url.
     *
     * @var string
     */
	private $feefoUrl = 'https://www.feefo.com/feefo/entersaleremotely.jsp';
    
    /**
     * Customer Name.
     *
     * @var string
     */
	protected $name = '';
    
    /**
     * Order Ref.
     *
     * @var string
     */
	protected $orderRef = '';
    
    /**
     * Customer Email.
     *
     * @var string
     */
	protected $email = '';
    
    /**
     * Description of sale.
     *
     * @var string
     */
	protected $description = '';
    
    /**
     * Feefo category.
     *
     * @var string
     */
	protected $category = '';
    
    /**
     * Service Rating.
     *
     * @var string
     */
	protected $serviceRating = '';
    
    /**
     * Service Comment.
     *
     * @var string
     */
	protected $serviceComment = '';
	
	/**
	 * Creates a new Feefo object
     *
	 * @param string $website  A valid login for the website
	 * @param string $password The Feefo password
     *
     * @return void
	 */
	function __construct($website, $password)
    {
		$this->website = $website;
		$this->password = $password;
	}
    
    /**
     * Return the submission url
     *
     * @return string
     */
    public function getCommentUrl()
    {
        return sprintf(
            '%s?%s',
			urlencode($this->feefoUrl),
            http_build_query(
                array(
                    'website' => $this->getWebsite(),
                    'password' => $this->getPassword(),
                    'orderref' => $this->getOrderRef(),
                    'name' => $this->getName(),
                    'email' => $this->getEmail(),
                    'description' => $this->getDescription(),
                    'servicerating' => $this->getServiceRating(),
                    'servicecomment' => $this->getServiceComment(),
                    'category' => $this->getCategory()
                )
            )
		);
    }
    
    /**
     * Try sending the comment off to Feefo
     *
     * @throws \Exception
     *
     * @return boolean
     */
    public function submit()
    {
		$res = $this->_sendRequest();
		
		// Check the response
		if ($res === FALSE) {
            throw new \Exception('Unable to connect to Feefo');
		} else if (substr(trim($res), 0, 4) != 'true') {
            throw new \Exception($res);
		} else {
			return true;
		}
    }
    
    /**
     * Set the email address of the customer
     *
     * @param string $email Email address of the customer
     *
     * @throws \Exception if email address is incorrect
     * 
     * @return Feefo
     */
    public function setEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
            return $this;
        }
        throw new \Exception('Invalid email address specified: ' . $email);
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
		$allowedratings = array('+', '++', '-', '--');
		if (!in_array($rating, $allowedratings)) {
			throw new \Exception('Invalid rating specified: ' . $rating);
		}
        $this->serviceRating = $rating;
        
        return $this;
    }
	
	/**
	 * Sends the request to feefo
     *
	 * @return Resource
	 */
	private function _sendRequest()
    {	
		$ch = curl_init($this->getCommentUrl());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return the output into a variable
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //don't check SSL certificates
		$res = curl_exec($ch);
		
		//Return the result as a string
		return $res;
	}
}