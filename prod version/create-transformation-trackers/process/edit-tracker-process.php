<?php

    $path = preg_replace('/wp-content.*$/','',__DIR__);

    require_once($path."wp-load.php");

    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
    
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }

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
        CURLOPT_URL => "https://forms.salesstar.com/mfvwhoxdasuyhoh/transformationtracker20/validate/{$_id}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "data": {
                "firstName": "Joe",
                "email": "joe@example.com",
                "phoneNumber": "123123123"
            }
        }',
        CURLOPT_HTTPHEADER => array(
            'x-token: gw4fwt3ydMnHz9o39WvYx8Edp3txTN',
            'Content-Type: application/json'
        ),
        ));

$response = curl_exec($curl);

curl_close($curl);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://forms.salesstar.com/mfvwhoxdasuyhoh/transformationtracker20/submission/{$_id}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS =>'[
        {
            "op": "replace",
            "path": "/data/company",
            "value": "'. $company .'"
        },
        {
            "op": "replace",
            "path": "/data/trackerName",
            "value": "'. $trackerName .'"
        },
        {
            "op": "replace",
            "path": "/data/TrackerPractice",
            "value": "'. $practice .'"
        },
        {
            "op": "replace",
            "path": "/data/firstMonthOfBusinessYear",
            "value": "'. $month .'"
        },
        {
            "op": "replace",
            "path": "/data/startOfEngagementYear",
            "value": "' . $year . '"
        },
        {
            "op": "replace",
            "path": "/data/CoachAccessUser",
            "value": "'. $coachUsers .'"
        },
        {
            "op": "replace",
            "path": "/data/EditUserAccess",
            "value": "'. $editUsers .'"
        }
        ]',
        CURLOPT_HTTPHEADER => array(
            'x-token: gw4fwt3ydMnHz9o39WvYx8Edp3txTN',
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $return = [];
        $return['response'] = $response;
        $return['success'] = 1;
        $return['message'] = "Success";


        echo json_encode($return);
    }

?>