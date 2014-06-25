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
class Feefo
{
    /**
     * Feefo username. Normally a domain name address
     *
     * @var string
     */
	private $website;
    
    /**
     * Feefo password. Does what it says on the tin.
     *
     * @var string
     */
	private $password;
    
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
			$this->getFeefoUrlEncoded(),
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
        /* return sprintf(
            '%s?website=%s&password=%s&orderref=%s&name=%s&email=%s&description=%s&servicerating=%s&servicecomment=%s&category=%s',
			$this->getFeefoUrlEncoded(),
			$this->getWebsiteEncoded(),
			$this->getPasswordEncoded(),
			$this->getOrderRefEncoded(),
			$this->getNameEncoded(),
			$this->getEmail(),
			$this->getDescriptionEncoded(),
			$this->getServiceRatingEncoded(),
			$this->getServiceCommentEncoded(),
			$this->getCategoryEncoded()
		); */
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

            switch (substr($name, 0, 3)) {
            case 'set':
                if (property_exists($this, $property)) {
                
                    // Only allow protected variables to be set
                    $reflector = new \ReflectionClass(get_class($this));
                    $prop = $reflector->getProperty($property);
                    if ($prop->isPrivate()) {
                        throw new \Exception(
                            'Unable to set, property is private:' . __CLASS__ . ':' . $property
                        );
                    }
                
                    $this->$property = $args[0];
                    return $this;
                } else {
                    throw new \Exception(
                        'Unknown method called:' . __CLASS__ . ':' . $name
                    );
                }
                break;
            case 'get':
                if (substr($property, -7) == 'Encoded') {
                    $property = substr($property, 0, -7);
                    return urlencode($this->$property);
                } else if (property_exists($this, $property)) {
                    return $this->$property;
                } else {
                    throw new \Exception(
                        'Unknown method called:' . __CLASS__ . ':' . $name
                    );
                }
                break;
            }
        }
    }
}