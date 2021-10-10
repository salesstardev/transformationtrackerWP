<?php

    $path = preg_replace('/wp-content.*$/','',__DIR__);

    require_once($path."wp-load.php");

        if(isset($_POST['newTrackerEdited']))
        {
        $company = sanitize_text_field($_POST['company']);
        $trackerName = sanitize_text_field($_POST['trackerName']);
        $practice = $_POST['practice'];
        $month = $_POST['month'];
        $year = $_POST['year'];
        $coachUsers = $_POST['coachUsers'];
        $editUsers = $_POST['editUsers'];
        $viewUsers = $_POST['viewUsers'];
        $_id = $_POST['_id'];
        $activeStatus = $_POST['activeStatus'];

        if ($activeStatus == "on"){$activeStatus = true;}else{$activeStatus = false;}

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://forms.salesstar.com/mfvwhoxdasuyhoh/transformationtracker20/submission/{$_id}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS =>'{
            "data": {
                "company": " '. $company .' ",
                 "trackerName": " ' . $trackerName . ' ",
                  "TrackerCoach": " ' . $coach . ' ",
                  "TrackerPractice": " ' . $practice . ' ",
                  "firstMonthOfBusinessYear": " ' . $month . ' ",
                  "startOfEngagementYear": " ' . $year . ' ",
                  "CoachAccessUser": " ' . $coachUsers . ' ",
                  "EditUserAccess": " ' . $editUsers . ' ",
                  "ViewUserAccess": " ' . $viewUsers . ' ",
                  "activeStatus": "' . $activeStatus . '"
            }
        }',
        CURLOPT_HTTPHEADER => array(
            'x-token: gw4fwt3ydMnHz9o39WvYx8Edp3txTN',
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
        
        $return = [];
        $return['success'] = 1;
        $return['message'] = "Success";


        echo json_encode($return);
    }

?>