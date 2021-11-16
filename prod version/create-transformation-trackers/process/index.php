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

        $date = date_create($date);
        $date = date_format($date, 'd-M-y');

        $return = [];
        $return['company_success'] = 0;
        $return['tracker_name_success'] = 0;
        $return['company_success'] = 0;
        $return['practice_success'] = 0;
        $return['month_success'] = 0;
        $return['year_success'] = 0;

        if ($_POST['company'] == "")
        {
          $return['success'] = 2;
          $return['company_success'] = 2;
        }

        if ($_POST['trackerName'] == "")
        {
          $return['success'] = 2;
          $return['tracker_name_success'] = 2;
        }

        if ($_POST['practice'] == "" || $_POST['practice'] == "error")
        {
          $return['success'] = 2;
          $return['practice_success'] = 2;
        }

        if ($_POST['month'] == "" || $_POST['month'] == "error")
        {
          $return['success'] = 2;
          $return['month_success'] = 2;
        }

        if ($_POST['year'] == "" || $_POST['year'] == "error")
        {
          $return['success'] = 2;
          $return['year_success'] = 2;
        }

         $curl = curl_init();

          curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://forms.salesstar.com/mfvwhoxdasuyhoh/transformationtracker20/submission?select=data.trackerName&limit=200',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 100,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
              'x-token: gw4fwt3ydMnHz9o39WvYx8Edp3txTN'
          ),
          ));

      $trackerNameCheck = curl_exec($curl);

      curl_close($curl);

      $pos = array_search($trackerName, $trackerNameCheck);

      if ($pos === false)
      {
        $return['success'] = 2;
        $return['tracker_unique'] = 2;
      }



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
              $return['success'] = 2;

              echo json_encode($return);

        }

       
    }

?>