<?php

namespace Drupal\vscc_jobs\Plugin\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Provides an 'Area Jobs' block.
 *
 * @Block(
 *   id = "jobs_block",
 *   admin_label = @Translation("Area Jobs block"),
 *   category = @Translation("Custom Area Jobs block")
 * )
 */
class JobsBlock extends BlockBase {
  
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    // connect to the MachForm database
    \Drupal\Core\Database\Database::setActiveConnection('machformdb');
    $connection = \Drupal\Core\Database\Database::getConnection();
        
    // query the jobs listings from the database
    $query = $connection->select('ap_form_21491', 't')->fields('t', array('id','element_1','element_2','element_19','date_created'));
    
    $date30 = date('Y-m-d', strtotime('-30 days'));
    $dateNow = date('Y-m-d', strtotime('+1 days'));
    $query->condition('date_created', array($date30, $dateNow), 'BETWEEN'); // job was posted within the past 30 days

    $query->condition('element_16', '1', '!=');   // job is active
    $query->orderBy('id', 'DESC');
    
    // execute query
    $resultsArr = array();
    $result = $query->execute();
    
    // loop through results and store in array
    while($record = $result->fetchAssoc()) 
    {
      $resultsArr[] = array(
    		'id' => $record['id'],
    		'title' => $record['element_1'],
    		'company' => $record["element_2"],
    		'location' => $record["element_19"],
    		'date' => date("n/j/Y", strtotime($record["date_created"]))
    	);
    }
    
    // switch back to Drupal database
    \Drupal\Core\Database\Database::setActiveConnection();
          
    // send results to Twig template
    return array(
      '#theme' => 'vscc_jobs_block',
      '#type' => 'markup',
      '#results' => $resultsArr,
      '#cache' => [
        'max-age' => 0,   // disable caching
      ],
    );  
        
   } // end function
}