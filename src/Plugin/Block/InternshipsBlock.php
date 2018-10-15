<?php

namespace Drupal\vscc_jobs\Plugin\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Provides an 'Internship' block.
 *
 * @Block(
 *   id = "internships_block",
 *   admin_label = @Translation("Internships block"),
 *   category = @Translation("Custom Internships block")
 * )
 */
class InternshipsBlock extends BlockBase {
  
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    // connect to the MachForm database
    \Drupal\Core\Database\Database::setActiveConnection('machformdb');
    $connection = \Drupal\Core\Database\Database::getConnection();
        
    /* query the jobs listings from the database */
    $query = $connection->select('ap_form_79718', 't')->fields('t', array('id','element_1','element_2','element_3_3','element_3_4','date_created'));
    
    $date30 = date('Y-m-d', strtotime('-30 days'));
    $dateNow = date('Y-m-d');
    $query->condition('date_created', array($date30, $dateNow), 'BETWEEN'); // job was posted within the past 30 days

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
    		'location' => $record["element_3_3"] . ', ' . $record["element_3_4"],
    		'date' => date("n/j/Y", strtotime($record["date_created"]))
    	);
    }
    
    // switch back to Drupal database
    \Drupal\Core\Database\Database::setActiveConnection();
          
    // send results to Twig template
    return array(
      '#theme' => 'vscc_internships_block',
      '#type' => 'markup',
      '#results' => $resultsArr,
      '#cache' => [
        'max-age' => 0,   // disable caching
      ],
    );  
        
   } // end function
}