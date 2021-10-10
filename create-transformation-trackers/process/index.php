<?php
    
    $path = preg_replace('/wp-content.*$/','',__DIR__);

    require_once($path."wp-load.php");

    if(isset($_POST['newTrackerCreated']) && $_POST['newTrackerCreated'] == "1")
    {       
        $company = sanitize_text_field($_POST['company']);
        $trackerName = sanitize_text_field($_POST['trackerName']);
        $practice = sanitize_text_field($_POST['practice']);
        $month = $_POST['month'];
        $year = $_POST['year'];

        date_default_timezone_set('New Zealand/Auckland');
        $date = date('d-M-y', time());

        if($_POST['company'] !== "" && $_POST['practice'] !== "" && $_POST['practice'] !== "error" && $_POST['month'] !== "" && $_POST['month'] !== "error" && $_POST['year'] !== "" && $_POST['year'] !== "error")
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://forms.salesstar.com/mfvwhoxdasuyhoh/transformationtracker20/submission',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                  "data": {
                      "company": "'. $company .'",
                      "trackerName": "' . $trackerName . '",
                      "TrackerCoach": "' . $coach . '",
                      "TrackerPractice": "' . $practice . '",
                      "createDateTime": "' . $date . '",
                      "firstMonthOfBusinessYear": "' . $month . '",
                      "startOfEngagementYear": "' . $year . '",
                      "activeStatus": "true"
                  }
              }',
                CURLOPT_HTTPHEADER => array(
                  'x-token: gw4fwt3ydMnHz9o39WvYx8Edp3txTN',
                  'Content-Type: application/json'
                ),
              ));
              
              $response = curl_exec($curl);
              
              curl_close($curl);

              $decode = json_decode($response);
              $id = $decode->{'_id'};
    
              $return = [];
              $return['success'] = 1;
              $return['message'] = "The tracker has successfully been created!";
              $return['_id'] = $id;
              $return['company'] = $company;
              $return['trackerName'] = $trackerName;
              $return['TrackerPractice'] = $TrackerPractice;
              $return['createDateTime'] = $date;
              $return['month'] = $month;
              $return['year'] = $year;
    
              echo json_encode($return);
        }
        else
        {
              $return = [];
              $return['success'] = 2;
              $return['message'] = "Please fill in all forms to create the tracker.";

              echo json_encode($return);

        }

       
    }

?>