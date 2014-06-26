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
 * 
 * @method string getLogon()    Return the feefo username
 * @method string getPassword() Return the feefo password
 * @method string getName()     Return the customer name
 * @method string getEmail()    Return the customer email
 * @method string getOrderRef() Return the order reference
 * @method string getTestMode() Return Test Mode status
 *
 * @method \aw\feefo\Feefo setLogon(string)     Set the Feefo username
 * @method \aw\feefo\Feefo setPassword(string)  Set the Feefo password
 * @method \aw\feefo\Feefo setName(integer)     Set the customer name
 * @method \aw\feefo\Feefo setOrderRef(integer) Set order reference
 * @method \aw\feefo\Feefo setTestMode(boolean) Set test mode
 */
class Feefo extends Feedback
{
    /**
     * Feefo username. Normally a domain name address
     *
     * @var string
     */
    protected $logon;
    
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
    private $feefoUrl = 'http://www.feefo.com/feefo/entersaleremotely.jsp';
    
    /**
     * Customer Name.
     *
     * @var string
     */
    protected $name = '';
    
    /**
     * Customer Email.
     *
     * @var string
     */
    protected $email = '';
    
    /**
     * Order Ref.
     *
     * @var string
     */
    protected $orderRef = '';
    
    /**
     * Test mode.
     *
     * @var boolean
     */
    protected $testMode = false;
    
    /**
     * Creates a new Feefo object
     *
     * @param string $website  A valid login for the website
     * @param string $password The Feefo password
     *
     * @return void
     */
    function __construct($logon, $password)
    {
        $this->logon = $logon;
        $this->password = $password;
    }
    
    /**
     * Description accessor
     * 
     * @param string $description Product description
     * 
     * @return Feefo
     */
    public function setDescription($description)
    {
        return $this->setProductDescription($description);
    }
    
    /**
     * Description accessor
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->getProductDescription();
    }
    
    /**
     * Return the submission url
     *
     * @return string
     */
    public function getCommentUrl()
    {
        $params = array(
            'logon' => $this->getLogon(),
            'password' => $this->getPassword(),
            'email' => $this->getEmail(),
            'name' => $this->getName(),
            'description' => $this->getProductDescription(),
            'orderref' => $this->getOrderRef()
        );
        
        if ($this->getReviewDate()) {
            $params['date'] = $this->getReviewDate()->format('Y-m-d');
        }
        
        if (strlen($this->getServiceRating()) > 0) {
            $params['servicerating'] = $this->getServiceRating();
        }
        
        if (strlen($this->getServiceComment()) > 0) {
            $params['servicecomment'] = $this->getServiceComment();
        }
        
        if (strlen($this->getProductCode()) > 0) {
            $params['itemref'] = $this->getProductCode();
        }
        
        // Test mode
        if ($this->getTestMode()) {
            $params['testing'] = 'true';
        }
        
        return sprintf(
            '%s?%s',
            $this->feefoUrl,
            http_build_query($params)
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
     * Sends the request to feefo
     *
     * @return string|boolean
     */
    private function _sendRequest()
    {
        $ch = curl_init($this->getCommentUrl());
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux i686; rv:20.0) Gecko/20121230 Firefox/20.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        return curl_exec($ch);
    }
}