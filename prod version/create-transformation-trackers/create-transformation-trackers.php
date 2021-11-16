<?php

$siteURL = "https://app.salesstar.com";

/**
 * Plugin Name: Transformation Tracker Wordpress Integration
 * Description: This plugin integrates the SalesStar Transformation Trackers into Wordpress, allowing you to create, edit and view your Trackers easily.
 */

$path = preg_replace('/wp-content.*$/','',__DIR__);

require_once($path."wp-load.php");

function redir_logged_out_users() {

    if (!is_user_logged_in())
    {
        wp_redirect( 'https://app.salesstar.com/portal-login/');
    }
    
    $user_id = get_current_user_id();
    $key = 'practice_';
    $single = true;
    $user_practice = get_user_meta( $user_id, $key, $single ); 

    if ($user_practice == "" || $user_practice == null || $user_practice = "The Don")
    {
        ?>
        <script>
         jQuery(document).ready(function() {jQuery(".cs-only").addClass("display-none"); 
         jQuery("#cs-only-create-btn").addClass("display-none"); 
         jQuery("#cs-only-manage-btn").addClass("display-none"); });
         
         console.log("Hello");
        </script>
        <?php
    }
    else if ($user_practice = "The Don")
    {
        ?>
        <script> 
         jQuery("#cs-only-create-btn").addClass("display-none"); 
          });
         
         console.log("Hello");
        </script>
        <?php
    }
}
add_shortcode('redir_logged_out_users', 'redir_logged_out_users');

function redir_logged_in_users() {

    if (is_user_logged_in())
    {
        wp_redirect( 'https://app.salesstar.com/home-page/');
    }
}
add_shortcode('redir_logged_in_users', 'redir_logged_in_users');


 function create_transformation_tracker_form()
 {
     
    //'<div id="unauth-user-warning">
     //<h3><i class="fa fa-exclamation-triangle"></i>Unauthorized Access</h3>'

    $content = '';

    $content .= '<div id="response_div"></div>';
    $content .= '<div id="response_info_div">';
    $content .= '<div id="response_btn_div">
                    <div id="response_info_title">
                        <p class="response-heading">You Have Successfully Created The New Tracker.</p>
                        <p class="response-sub-heading">See below for the details of the new tracker:</p>
                    </div>
                    <div id="response_info_link"></div>
                    <div id="response_info_company"></div>
                    <div id="response_info_tracker_name"></div>
                    <div id="response_info_engagement_date"></div>
                    <div id="response_info_create_date"></div><br><br>
                    <div id="response_info_instructions">
                        <p class"response_text">Click below to edit the tracker you just created, view the tracker or create a new tracker.</p>
                    </div>';
    $content .= '<button id="response_edit_btn" class="response_btn" onclick="edit_tracker_form_redirect()">Edit Tracker</button>';
    $content .= '<button id="response_view_btn" class="response_btn" onclick="view_redirect_with_tracker_id()">Open Tracker</button>';
    $content .= '<button id="response_new_btn" class="response_btn" onclick="reset_tracker_form()">Create Another Tracker</button></div></div>';

    $content .= '<div id="new-tracker-container">';
    $content .= '<label for="CompanyName" class="input-label" id="companyNameLabel">Company Name</label>';
    $content .= '<input type="text" id="companyName" name="CompanyName" placeholder="SalesStar">';

    $content .= '<label for="TrackerName" class="input-label" id="trackerNameLabel">Tracker Name</label>';
    $content .= '<input type="text" id="trackerName" name="TrackerName" placeholder="SalesStar ANZ Practice">';

    $content .= '<label for="Practice" class="input-label" id="practiceLabel">Practice</label><br>';
                $content .= '<select id="Practice" class="select-btm-border" name="Practice">
                                <option hidden selected value="error">Select Practice</option>
                                <option value="ANZ">ANZ</option>
                                <option value="USA: Ohio">USA/Ohio</option>
                                <option value="UK">UK</option>
                                <option value="Scandinavia">Scandinavia</option>
                                <option value="Mexico">Mexico</option>
                                <option value="SLP">SLP</option>
                            </select><br>';

    $content .= '<label for="StartMonth" class="input-label" id="monthLabel">Month Started With SalesStar</label>
                 <select id="month" name="StartMonth">
                    <option hidden selected value="error"> Select Month </option>
                    <option value="January">January</option>
                    <option value="Feburary">Feburary</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
                <label for="StartYear" class="input-label" id="yearLabel">Year Started With SalesStar</label><br>
                <select id="startYear" name="StartYear">
                    <option hidden selected value="error"> Select Year </option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select><br>
                <input type="submit" id="create-form-submit-btn" name="create-form-submit-btn" onclick="submit_create_tracker_form()" value="Create Transformation Tracker"></div>';

    return $content;
 }
add_shortcode('create_transformation_tracker_form', 'create_transformation_tracker_form');

function edit_transformation_tracker_form()
 {      
                $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

                $escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );

                $_id = substr($escaped_url, strpos($escaped_url, "?") + 1);    
                $response = retreive_formio_submission($_id);
                

                $decode = json_decode($response);
                $company = $decode->data->{'company'};
                $trackerName = $decode->data->{'trackerName'};
                $practice = str_replace(' ', '', $decode->data->{'TrackerPractice'});
                $month = str_replace(' ', '', $decode->data->{'firstMonthOfBusinessYear'});
                $year = str_replace(' ', '', $decode->data->{'startOfEngagementYear'});
                $coachUsers = $decode->data->{'CoachAccessUser'};
                $editUsers = $decode->data->{'EditUserAccess'};
                $viewUsers = $decode->data->{'ViewUserAccess'};
                $activeStatus = $decode->data->{'activeStatus'};

                $month_names = array("January","February","March","April","May","June","July","August","September","October","November","December");
                $year_array = array("2020", "2021", "2022", "2023", "2024", "2025");
                $practice_array = array("ANZ", "UK", "USA:Ohio", "Scandinavia", "Mexico", "SLP");


                $coachUsersArray = explode(' ', $coachUsers);
                $editUsersArray = explode(' ', $editUsers);
                $viewUsersArray = explode(' ', $viewUsers);

                $users = get_users( array());

                $content .= '<h2 class="section-heading">Update Transformation Tracker Settings Test</h2>
                             <div id="edit-tracker-form-div">';
                $content .= '<label for="CompanyName">Company Name (Currently: ' . $company . ')</label>';
                $content .= '<input type="text" id="companyName" name="CompanyName" placeholder="SalesStar" value="' . $company . '">';
                $content .= '<label for="TrackerName">Tracker Name (Currently: '. $trackerName .')</label>';
                $content .= '<input type="text" id="trackerName" name="TrackerName" placeholder="SalesStar ANZ Practice" value="' . $trackerName . '" >';

                $content .= '<label for="Practice">Practice (Currently: ' . $practice . ')</label>';
                $content .= '<select id="Practice" class="select-btm-border" name="Practice">';
                                foreach($practice_array as $current_practice)
                                {
                                    $content .= '<option value="'.$current_practice.'" '; if($practice == $current_practice){$content .= ' selected';}
                                    $content .= '>'.$current_practice.'</option>';
                                }
                            $content .= '</select>';
                $content .= '<label for="StartMonth">Month Started With SalesStar (Currently: ' . $month . ')</label>
                             <select id="month" name="StartMonth">';
                             foreach($month_names as $current_month)
                             {
                                 $content .= '<option value="'.$current_month.'" '; if($month == $current_month){$content .= ' selected';}
                                 $content .= '>'.$current_month.'</option>';
                             }
                             $content .= '</select>';
                $content .='<label for="StartYear">Year Started With SalesStar (Currently: ' . $year . ')</label><br>
                             <select id="startYear" name="StartYear">';
                             foreach($year_array as $current_year)
                             {
                                 $content .= '<option value="'.$current_year.'" ';if($year == $current_year){$content .= ' selected';}
                                 $content .= '>'.$current_year.'</option>';
                             }
                $content .= '</select>';
                $content .= '<label for="activeStatus">Active Status</label><br>';
                $content .= '<label class="switch">
                             <input type="checkbox" id="activeStatus" '; if($activeStatus == true){$content .= 'checked="checked"';}$content .='>
                             <span class="slider round"></span>
                             </label><br><br>
                             <button type="submit" class="submit-form-btn" id="edit-tracker-submit-btn" name="create-form-submit-btn" onclick="submit_edit_tracker_form()" value="Update Transformation Tracker">Update Transformation Tracker</button>
                             </div>';
                $content .= '<h2 class="section-heading">Update Transformation Tracker Access</h2>';
                $content .= '<div id="edit-assignee-div"><label for="AssignCoachUsers">Assign Tracker Coach Access</label><br>';
                $content .= '<select id="AssignCoachUsers" name="AssignCoachUsers[]" style="width: 90%; padding: 0px; height: 200px;" multiple>
                            </div>';
               
                foreach ($users as $user)
                {
                    //Adds all currently registered users as an option to the select field
                    $content .='<option value="' . $user->user_email . '"';

                    //Checks if the current user being iterated through already has access. If so, pre-select them in the form.
                    if (in_array($user->user_email, $coachUsersArray))
                    {
                        $content .= 'selected'; 
                    }
                    $content .= '>' . $user->user_email . '</option>';
                }
                $content .= '</select><br><br>';
                $content .= '<label for="AssignEditUsers">Assign Tracker Edit Access</label><br><select id="AssignEditUsers" name="AssignEditUsers[]" style="width: 90%; padding: 0px; height: 200px;" multiple>
                </div>';
   
                foreach ($users as $user)
                {
                    //Adds all currently registered users as an option to the select field
                    $content .='<option value="' . $user->user_email . '"';
                    if (in_array($user->user_email, $editUsersArray))
                    {
                        $content .= 'selected'; 
                    }
                    $content .= '>' . $user->user_email . '</option>';
                }
                $content .= '</select><br><br>';
                $content .= '<label for="AssignViewUsers">View Only Users (Coming Soon...)</label><br><select id="AssignViewUsers" name="AssignViewUsers[]" style="width: 90%; padding: 0px; height: 200px;" multiple disabled>
                </div>';

                foreach ($users as $user)
                {
                //Adds all currently registered users as an option to the select field
                $content .='<option value="' . $user->user_email . '"';
                if (in_array($user->user_email, $viewUsersArray))
                    {
                        $content .= 'selected'; 
                    }
                $content .= '>' . $user->user_email . '</option>';
                }
                $content .= '</select><br><br>';
                
                $content .= '<button type="submit" class="submit-form-btn" id="edit-access-submit-btn" name="create-form-submit-btn" onclick="submit_edit_tracker_form()" value="Update Transformation Tracker">Update Transformation Tracker</button>';
                $content .= '</div>';

                return $content;


 }

add_shortcode('edit_transformation_tracker_form', 'edit_transformation_tracker_form');

function generate_view_all_trackers_table()
{
    $users = get_users( array());

    $trackers = retreive_all_formio_submissions();
    $decode = json_decode($trackers);

    $content = '';
    //$content .= '<input id="Tracker-Filter-Search" type="text" placeholder="Filter results by search term...">
    //             <button type="submit" class="submit-search-btn" id="submit-submission-search-btn" onclick="filter_submission_table()"><i class="fa fa-search"></i></button>';
    $content .= '<table class="TrackerIndexTable" id="Tracker-Submission-table"><thead>';
    $content .= '<tr class="submission-row-top">
            <td> Tracker Names </td>
            <td> Company </td>
            <td> Coach </td>
            <td> Practice </td>
            <td> Date Created </td>
            <td> Link </td>
            </tr></thead><tbody>';

    $current_user = wp_get_current_user();
    $current_email = $current_user->user_email;

    

    foreach ($decode as $decode)
    {
        $coachUsers = $decode->data->{'CoachAccessUser'};
        $editUsers = $decode->data->{'EditUserAccess'};

        if(stripos($editUsers, $current_email) !== false || stripos($coachUsers, $current_email) !== false)
        {

        $companyName = $decode->data->{'company'};
        $trackerName = $decode->data->{'trackerName'};
        $practice = $decode->data->{'TrackerPractice'};
        $date = $decode->data->{'createDateTime'};
        $date = $decode->data->{'createDateTime'};
                $time = strtotime($date);
                $date = date('d-M-y', $time);
        $linkID = $decode->{'_id'};
        $link = "https://app.salesstar.com/view-tracker/?{$linkID}";
        $coach = $decode->data->{'CoachAccessUser'};

            $content .= '<tr class="submission-row">
            <td>' .  $trackerName . '</td>
            <td>' .  $companyName . '</td>
            <td>' .  $coach . '</td>
            <td>' .  $practice . '</td>
            <td>' .  $date . '</td>
            <td><a href="' .  $link . '"><i class="fa fa-external-link" aria-hidden="true"></i></a></td>
            </tr>';
            
        }
    }
    $content .= '</tbody></table>';

    return $content;
}
add_shortcode('generate_view_all_trackers_table', 'generate_view_all_trackers_table');

function generate_cs_view_all_trackers_table()
{
    
    $user_id = get_current_user_id();
    $key = 'practice_';
    $single = true;
    $user_practice = get_user_meta( $user_id, $key, $single ); 

    if ($user_practice !== "" || $user_practice !== null)
    {
        $users = get_users( array());

        $trackers = retreive_all_formio_submissions();
        $decode = json_decode($trackers);


        $content = '';
        $content .= '<table class="TrackerIndexTable" id="Tracker-Submission-table"><thead>';
        $content .= '<tr class="submission-row-top">
                <th> Tracker Names </th>
                <th> Company </th>
                <th> Coach </th>
                <th> Practice </th>
                <th> Date Created </th>
                <th> Link </th>
                <th> Edit </th>
                </tr>
                </thead><tbody>';

        $current_user = wp_get_current_user();
        $current_email = $current_user->user_email;
        
            foreach ($decode as $decode)
            {
                $full_name = "";
                $coachNames = "";
                $coachArray = "";

                $editUsers .= $decode->data->{'CoachAccessUser'};
                $editUsers .= " ";
                $editUsers .= $decode->data->{'EditAccessUser'};
                $created = $decode->{'Submitted'};

                $content .= '<p>' . $created . '</p>';
        
                $companyName = $decode->data->{'company'};
                $trackerName = $decode->data->{'trackerName'};
                $practice = $decode->data->{'TrackerPractice'};
                $date = $decode->data->{'createDateTime'};
                $time = strtotime($date);
                $date = date('d-M-y', $time);
                $linkID = $decode->{'_id'};
                $link = "https://app.salesstar.com/view-tracker/?{$linkID}";
                $editLink = "https://app.salesstar.com/edit-tracker/?{$linkID}";

                $coach = $decode->data->{'CoachAccessUser'};
                $coachArray = explode(" ", $coach);

                foreach ($coachArray as $coaches)
                {

                $coaches = str_replace(' ', '', $coaches);

                $coachInfo = get_user_by('email', $coaches);

                $user_meta = get_user_meta( $coachInfo->ID );

                $first_name = $coachInfo->first_name;
                $last_name = $coachInfo->last_name;

                $full_name .= $first_name;
                $full_name .= " ";
                $full_name .= $last_name;
                $coachNames .= $full_name;

                if($coaches === array_key_first($coachArray)){}
                else{$coachNames.= ", ";}
                          
             }
        
                    if (stripos($user_practice, $practice) !== false || $user_practice == "global"){
                    $content .= '<tr class="submission-row">
                    <td>' .  $trackerName . '</td>
                    <td>' .  $companyName . '</td>
                    <td>' .  $coach . '</td>
                    <td>' .  $practice . '</td>
                    <td>' .  $date . '</td>
                    <td><a href="' .  $link . '"><i class="fa fa-external-link"></i></a></td>
                    <td><a href="' .  $editLink . '"><i class="fa fa-edit"></i></a></td>
                    </tr>';
                    }
                    else if ($user_practice == "The Don"){
                        $content .= '<tr class="submission-row">
                        <td>' .  $trackerName . '</td>
                        <td>' .  $companyName . '</td>
                        <td>' .  $coach . '</td>
                        <td>' .  $practice . '</td>
                        <td>' .  $date . '</td>
                        <td><a href="' .  $link . '"><i class="fa fa-external-link"></i></a></td>
                        </tr>';
                        }
    

                    
                }
            
            $content .= '</tbody></table>';

            return $content;
    }
    else {
        return "<p>You cannot view this page</p>";
    }


    }
    

add_shortcode('generate_cs_view_all_trackers_table', 'generate_cs_view_all_trackers_table');





function include_create_tracker_ajax()
{
    ?>
    <script src="https://app.salesstar.com/wp-content/plugins/create-transformation-trackers/js/create_new_tracker_ajax.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <?php

}
add_action('wp_footer', 'include_create_tracker_ajax');


function retreive_formio_submission($_id)
{
    $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://forms.salesstar.com/mfvwhoxdasuyhoh/transformationtracker20/submission/{$_id}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'x-token: gw4fwt3ydMnHz9o39WvYx8Edp3txTN',
            'Content-Type: application/json'
        ),
        ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}

function test_retreive_formio_submission()
{
    $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://forms.salesstar.com/mfvwhoxdasuyhoh/transformationtracker20/submission/61676a69dd54c712278c9fa2",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'x-token: gw4fwt3ydMnHz9o39WvYx8Edp3txTN',
            'Content-Type: application/json'
        ),
        ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
}
add_shortcode('test_retreive_formio_submission', 'test_retreive_formio_submission');


function retreive_all_formio_submissions()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://forms.salesstar.com/mfvwhoxdasuyhoh/transformationtracker20/submission?select=data.company,data.trackerName,data.TrackerPractice,data.createDateTime,data.CoachAccessUser,data.EditUserAccess,ViewAccessUser&limit=200',
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

return $response;

}

?>



