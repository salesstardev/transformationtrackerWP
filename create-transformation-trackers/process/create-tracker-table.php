<?php

  $path = preg_replace('/wp-content.*$/','',__DIR__);

  require_once($path."wp-load.php");

$searchTerm = sanitize_text_field($_POST['searchTerm']);

$curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://forms.salesstar.com/mfvwhoxdasuyhoh/transformationtracker20/submission?select=data.company,data.trackerName,data.TrackerPractice,data.createDateTime,data.CoachAccessUser,data.EditAccessUser,ViewAccessUser&limit=200',
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

    $response = curl_exec($curl);

    curl_close($curl);

    $responseArray = json_decode($response);

    $current_user = wp_get_current_user();
    $current_email = $current_user->user_email;
    

    foreach ($responseArray as $tracker)
    {
        $editUsers .= $tracker->data->{'CoachAccessUser'};
        $editUsers .= " ";
        $editUsers .= $tracker->data->{'EditAccessUser'};

        if(strpos($editUsers, $current_email) !== false)
        {   
            $coach = $tracker->data->{'CoachAccessUser'};
            $companyName = $tracker->data->{'company'};
            $trackerName = $tracker->data->{'trackerName'};
            $practice = $tracker->data->{'TrackerPractice'};
            $date = $tracker->data->{'createDateTime'};
            $linkID = $tracker->{'_id'};
            $viewLink = "https://salesstartest.wpengine.com/client-services-portal-view-tracker/?{$linkID}";
            $editLink = "https://salesstartest.wpengine.com/client-services-portal-edit-tracker/?{$linkID}";
    
            if (stripos($companyName, $searchTerm) !== false || stripos($trackerName, $searchTerm) !== false || stripos($practice, $searchTerm) !== false)
            {
                $trackerTable .= '<tr class="submission-row">
                    <td>' .  $trackerName . '</td>
                    <td>' .  $companyName . '</td>
                    <td>' .  $coach . '</td>
                    <td>' .  $practice . '</td>
                    <td>' .  $date . '</td>
                    <td><a href="' .  $viewLink . '" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a></td>
                    </tr>';       
            }
        }

    }

    $trackerTable .= '</table>';

    $return = [];
    $return['tableRows'] = $trackerTable;
    $return['success'] = 1;

    echo $trackerTable;



