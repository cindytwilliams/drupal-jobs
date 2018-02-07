<?php

namespace Drupal\vscc_jobs\Plugin\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Provides an 'Acalog' block.
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
    
    /* connect to the MachForm database */
    \Drupal\Core\Database\Database::setActiveConnection('machformdb');
    $connection = \Drupal\Core\Database\Database::getConnection();
        
    /* query the jobs listings from the database */
    $query = $connection->select('jobsTable', 't')->fields('t', array('id','element_1','element_2','element_19','date_created'));
    
    $date30 = date('Y-m-d', strtotime('-30 days'));
    $dateNow = date('Y-m-d');
    $query->condition('date_created', array($date30, $dateNow), 'BETWEEN'); // job was posted within the past 30 days

    $query->condition('element_16', '1', '!=');   // job is active
    $query->orderBy('id', 'DESC');
    
    $output = '<table class="sort">
      <thead>
          <th>Position</th>
          <th>Company Name</th>
          <th>Location</th>
          <th>Date Posted</th>
      </thead>';

    /* loop through and display results */
    $result = $query->execute();
    while($record = $result->fetchAssoc()) 
    { 
      $output = $output . '
        <tr>
            <td><a href="/areajobs/' . $record['id'] . '">' . $record['element_1'] .'</a></td>
            <td>' . $record["element_2"] .'</td>
            <td>' . $record["element_19"] .'</td>
            <td>' . date("n/j/Y", strtotime($record["date_created"])) . '</td>
        </tr>';
    }
    
    $output = $output . '</table>';
    
    // switch back to Drupal database
    \Drupal\Core\Database\Database::setActiveConnection();
          
    // return output
    return array(
      '#type' => 'markup',
      '#markup' => $output,
      '#title' => 'Area Jobs',
    );
    
   } // end function
}