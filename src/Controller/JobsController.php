<?php

namespace Drupal\vscc_jobs\Controller;

use Drupal\Core\Controller\ControllerBase;

class JobsController extends ControllerBase {
    
  /**
   * Display one area jobs listing from MachForm
   * URL: /areajobs/{jobID}
   *
   * @return array
  */
  public function display_job(String $jobID) {
    
    $output = '<p>Information on this page comes from a variety of sources. Volunteer State Community College provides this page as an unaffiliated resource for use by the communities we serve. When conducting a job search, it is the responsibility of each individual to research the integrity of the organizations to which one is applying. Individuals are advised to use caution and common sense when applying for any position with an organization and when supplying personal information through the Internet. Volunteer State Community College does not author, edit or monitor this page and therefore does not assume responsibility for its content or veracity.</p><p><a href="/areajobs">< Return to Jobs Listings</a></p><hr>';
  
    /* connect to the MachForm database */
    \Drupal\Core\Database\Database::setActiveConnection('machformdb');
    $connection = \Drupal\Core\Database\Database::getConnection();
    
    /* query the jobs listings from the database */
    $query = $connection->select('ap_form_21491', 't')->fields('t', array('id','element_1','element_2','element_4','element_5','element_8','element_9','element_10','element_12','element_13_1','element_13_2','element_14','element_18','element_19','element_20','element_21','element_22','date_created'));
    $query->condition('id', $jobID, '=');   // requested jobID
    $query->condition('element_16', '1', '!=');   // job is active
    $query->orderBy('id', 'DESC');

    /* display result */
    $result = $query->execute();
    while($record = $result->fetchAssoc()) 
    { 
      $output = $output . '<div id="areaJobs">
          <h2>' . $record["element_1"] . '</h2>
          
          <h3>Company Information</h3>
          <p><strong>Company: </strong>'.$record["element_2"].'</span>
          <p><strong>Location: </strong>'.$record["element_19"].'</span>';
          if ($record["element_18"] != "") {
            $output = $output . '<p><a href="'.$record['element_18'].'" target="_blank" title="'.$record['element_2'].'">Company Website</a></p>';
         	}
         	$output = $output . '<p>'.nl2br($record["element_12"]).'</p>';
          
          
          $output = $output . '<h3>Job Description</h3>
            <p>'.nl2br($record['element_4']).'</p>
            
            <p><strong>Job Type: </strong> ';
            If ($record['element_20'] == 1) {
              $output = $output . 'Full time';
  			    } elseif ($record['element_20'] == 2) {
              $output = $output . 'Part time';
  			    } elseif ($record['element_20'] == 3) {
              $output = $output . 'Contract';
  			    } elseif ($record['element_20'] == 4) {
              $output = $output . 'Temp';
  			    }
            
            $output = $output . '</p><p><strong>Work Hours: </strong>' . $record['element_8'] . '</p>
            
            <p><strong>Compensation: </strong>' . $record['element_9'] . '</p>
            
            <p><strong>Start Date: </strong>' . date("F j, Y", strtotime($record['element_10'])) . '</p>
              
            <h3>Requirements</h3>
            <p>'.nl2br($record['element_5']).'</p>
           
           <p><strong>Education:</strong> ';
  					If ($record['element_21'] == 1) {
              $output = $output . 'No education required';
  			    } elseif ($record['element_21'] == 2) {
              $output = $output . 'High school';
  			    } elseif ($record['element_21'] == 3) {
              $output = $output . 'Certification';
  			    } elseif ($record['element_21'] == 4) {
              $output = $output . 'Associate\'s degree';
  			    }  elseif ($record['element_21'] == 5) {
              $output = $output . 'Bachelor\'s degree';
  			    }  elseif ($record['element_21'] == 6) {
              $output = $output . 'Master\'s degree or higher';
  			    }  elseif ($record['element_21'] == 7) {
              $output = $output . 'Varies';
  			    }
					
            $output = $output . '</p><p><strong>Experience:</strong> ';
            If ($record['element_22'] == 1) {
              $output = $output . 'No experience required';
  			    } elseif ($record['element_22'] == 2) {
              $output = $output . '1 year';
  			    } elseif ($record['element_22'] == 3) {
              $output = $output . '2 years';
  			    } elseif ($record['element_22'] == 4) {
              $output = $output . '3 years';
  			    }  elseif ($record['element_22'] == 5) {
              $output = $output . '4 years';
  			    }  elseif ($record['element_22'] == 6) {
              $output = $output . '5 or more years';
  			    }  elseif ($record['element_22'] == 7) {
              $output = $output . 'other';
  			    }
            
            $output = $output . '</p><h3>Contact Information</h3>
                <p>' . $record["element_13_1"].' '.$record["element_13_2"] . '<br>
                <a href="mailto:'.$record["element_14"].'">'.$record["element_14"].'</a><br>
                <p><strong>Date Posted: </strong>'.date("F j, Y", strtotime($record["date_created"])).'</p>
            
          </div>';
      
    }  // end while
    
    // switch back to Drupal database
    \Drupal\Core\Database\Database::setActiveConnection();
  
    // output results to the page
    $build = array();
    $build['#title'] = 'Area Jobs';
    $build['#markup'] = $output;
    return $build;
  
  } // end function
  
  
  /**
   * Display one internship listing from MachForm
   * URL: /work-based-learning/internships/{jobID}
   *
   * @return array
  */
  public function display_internship(String $jobID) {
    
    $output = '<p>Information on this page comes from a variety of sources. Volunteer State Community College provides this page as an unaffiliated resource for use by the communities we serve. When conducting a job search, it is the responsibility of each individual to research the integrity of the organizations to which one is applying. Individuals are advised to use caution and common sense when applying for any position with an organization and when supplying personal information through the Internet. Volunteer State Community College does not author, edit or monitor this page and therefore does not assume responsibility for its content or veracity.</p>
    <a href="/work-based-learning/internships">< Return to Internship Listings</a><hr>';
  
    /* connect to the MachForm database */
    \Drupal\Core\Database\Database::setActiveConnection('machformdb');
    $connection = \Drupal\Core\Database\Database::getConnection();
    
    /* query the jobs listings from the database */
    $query = $connection->select('ap_form_79718', 't')->fields('t', array('id','element_1','element_2','element_3_3','element_3_4','element_19','element_5','element_8','element_11','element_13','element_15', 'element_16','element_23','element_9','element_19','element_20','element_18_1','element_18_2','date_created'));
    $query->condition('id', $jobID, '=');   // requested jobID
    $query->orderBy('id', 'DESC');

    /* display result */
    $result = $query->execute();
    while($record = $result->fetchAssoc()) 
    { 
      
      $startDate = strtotime($record['element_16']);
      $startDate = date('F j, Y',$startDate);


      $output = $output . 
        '<h2>'.$record["element_1"].'</h2>
        
        <h3>Company Information</h3> 
        <p><strong>Company Name: </strong>'.$record["element_2"].'</p>
        <p><strong>Location: </strong>'.$record["element_3_3"].', '.$record["element_3_4"].'</p>';
        if ($record["element_5"] != "") {
          $output = $output . '
            <p><a href="'.$record['element_5'].'">Company Website</a></p>'; 
        }
        $output = $output . '<p>'.nl2br($record["element_9"]).'</p>
        
        <h3>Job Description</h3>
        <p>'.nl2br($record['element_8']).'</p>
        
        <p><strong>Desired Skills: </strong>'.nl2br($record['element_11']).'</p>
        <p><strong>Expected Learning Outcomes: </strong>'.nl2br($record['element_13']).'</p>
        <p><strong>Compensation: </strong>'.$record['element_15'].' per hour</p>
        <p><strong>Start Date</strong> '. $startDate .'</p>
          
          
        <h3>Contact Information</h3>
        <p>' . $record["element_18_1"].' '.$record["element_18_2"] . '<br>
        <a href="mailto:'.$record["element_19"].'">'.$record["element_19"].'</a><br>'
        //.preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $record["element_20"])
        .'</p>
        
        <p><strong>Date Posted: </strong>'.date("F j, Y", strtotime($record["date_created"])).'</p>';
      
    } // end while
    
    // switch back to Drupal database
    \Drupal\Core\Database\Database::setActiveConnection();
    
    // output results to the page
    $build = array();
    $build['#title'] = 'Area Internships';
    $build['#markup'] = $output;
    return $build;
    
  } // end function
  
  
}   // end class