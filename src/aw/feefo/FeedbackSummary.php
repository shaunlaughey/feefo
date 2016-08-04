<?php

namespace aw\feefo;

/**
 * This class represents a feefo summary.
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
class FeedbackSummary extends FeefoBase
{
    // -------------------------- Object Parameters ------------------------- //
    
    /**
     * Feedback array
     *
     * @var array
     */
    private $feedback = array();
    
    /**
     * Average rating
     *
     * @var integer
     */
    protected $averageRating;
    
    /**
     * Best rating
     *
     * @var integer
     */
    protected $bestRating;
    
    /**
     * Worst rating
     *
     * @var integer
     */
    protected $worstRating;
    
    /**
     * Number of ratings
     *
     * @var integer
     */
    protected $numberOfRatings;

    /**
     * Total number of responses
     *
     * @var integer
     */
    protected $totalResponses;
    
    /**
     * Product rating object
     *
     * @var \aw\feefo\FeedbackRating
     */
    protected $productRating;
    
    /**
     * Service rating object
     *
     * @var \aw\feefo\FeedbackRating
     */
    protected $serviceRating;
    
    /**
     * Supplier Logo
     *
     * @var string
     */
    protected $supplierLogo = '';
    
    /**
     * Supplier Title
     *
     * @var string
     */
    protected $supplierTitle = '';
    
    // --------------------------- Factory Method --------------------------- //
    
    /**
     * Factory Method
     *
     * @param string  $logon              Username for feefo
     * @param string  $mode               Feed mode, service|product|both|productonly
     * @param integer $limit              Number of feedback requests to fetch
     * @param boolean $filterOutNegatives Suppress negative answers submitted 
     * in the last two days that have not had a comment.
     * @param string  $since              day, week, month, 6months, year or all. If not specified,
     * the default for the Feefo account will be used.
     * 
     * @return void
     */
    public static function factory(
        $logon,
        $mode = 'both',
        $limit = 20,
        $filterOutNegatives = true,
        $since = null
    ) {
        $summary = new \aw\feefo\FeedbackSummary($logon, $mode);
        $response = @file_get_contents(
            sprintf(
                'http://www.feefo.com/feefo/xmlfeed.jsp?logon=%s&%s',
                $logon,
                http_build_query(
                    array(
                        'json' => 'true',
                        'limit' => $limit,
                        'mode' => $mode,
                        'negativesanswered' => ($filterOutNegatives) ? 'true' : 'false',
                        'since' => $since
                    )
                )
            )
        );
        
        $json = json_decode($response);
        
        if ($json 
            && property_exists($json, 'FEEDBACKLIST') 
            && property_exists($json->FEEDBACKLIST, 'SUMMARY')
        ) {
            // Feedback object
            $feedback = $json->FEEDBACKLIST;
            
            // Set the service/product ratings
            $st = new \aw\feefo\SummaryTotal();
            $st->setBad($feedback->SUMMARY->PRODUCTBAD);
            $st->setPoor($feedback->SUMMARY->PRODUCTPOOR);
            $st->setGood($feedback->SUMMARY->PRODUCTGOOD);
            $st->setExcellent($feedback->SUMMARY->PRODUCTEXCELLENT);
            $summary->setProductRating($st);
            
            // Set the service/product ratings
            $st = new \aw\feefo\SummaryTotal();
            $st->setBad($feedback->SUMMARY->SERVICEBAD);
            $st->setPoor($feedback->SUMMARY->SERVICEPOOR);
            $st->setGood($feedback->SUMMARY->SERVICEGOOD);
            $st->setExcellent($feedback->SUMMARY->SERVICEXCELLENT);
            $summary->setServiceRating($st);
            
            // Set the totals
            $summary->setAverageRating($feedback->SUMMARY->AVERAGE);
            $summary->setBestRating($feedback->SUMMARY->BEST);
            $summary->setWorstRating($feedback->SUMMARY->WORST);
            $summary->setNumberOfRatings($feedback->SUMMARY->COUNT);
            $summary->setTotalResponses($feedback->SUMMARY->TOTALRESPONSES);
            
            // Set miscelaneous
            $summary->setSupplierLogo($feedback->SUMMARY->SUPPLIERLOGO);
            $summary->setSupplierTitle($feedback->SUMMARY->TITLE);
            
            // Create feedback objects
            $summary->setFeedback(array());
            
            // Loop through feedback
            if (is_array($feedback->FEEDBACK)) {
                foreach ($feedback->FEEDBACK as $fbObj) {
                    $summary->addFeedBack(
                        \aw\feefo\Feedback::factory($fbObj)
                    );
                }
            } else if (is_object($feedback->FEEDBACK)) {
                $summary->addFeedBack(
                    \aw\feefo\Feedback::factory($feedback->FEEDBACK)
                );
            }
            
            return $summary;
            
        } else {
            throw new \Exception('Unable to fetch feefo summary');
        }
    }
    
    // ------------------------------ Accessors ----------------------------- //
    
    /**
     * Return the product rating
     *
     * @return \aw\feefo\FeedbackRating
     */
    public function getProductRating()
    {
        return $this->productRating;
    }
    
    /**
     * Return the service rating
     *
     * @return \aw\feefo\FeedbackRating
     */
    public function getServiceRating()
    {
        return $this->serviceRating;
    }
    
    /**
     * Return the feedback array
     *
     * @return array
     */
    public function getFeedback()
    {
        return $this->feedback;
    }
    
    /**
     * Set the feedback array
     *
     * @param array $feedback Array of feedback elements
     *
     * @return \aw\feefo\FeedbackSummary
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;
        
        return $this;
    }
    
    /**
     * Set the feedback array
     *
     * @param \aw\feefo\Feedback $feedback Feedback object
     *
     * @return \aw\feefo\FeedbackSummary
     */
    public function addFeedback($feedback)
    {
        $this->feedback[$feedback->getId()] = $feedback;
        
        return $this;
    }
}
