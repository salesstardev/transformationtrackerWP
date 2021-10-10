function load_select_2() {
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
}



function submit_create_tracker_form()
{
    console.log("Submit hit");

    var message = "Processing your request now..."
    $("#response_div").html(message);
    $("#response_div").css("display", "block");
    $("#response_div").css("background-color", "darkgray");
    $("#response_div").css("color", "#FFFFFF");
    $("#response_div").css("padding", "20px");
    $("#response_div").css("width", "70%");
    $("#response_div").css("margin-left", "15%");
    $("#response_div").css("margin-bottom", "2%");



    var fd = new FormData();

    var _id = sessionStorage.getItem("_id");

    fd.append('newTrackerCreated', '1');
    fd.append('company', $("#companyName").val());
    fd.append('trackerName', $("#trackerName").val());
    fd.append('coach', $("#coach").val());
    fd.append('practice', $("#Practice").val());
    fd.append('month', $("#month").val());
    fd.append('year', $("#startYear").val());
    fd.append('_id', _id);
    console.log(_id);
    js_submit(fd, submit_create_tracker_form_callback);
}

function submit_edit_tracker_form()
{
    console.log("Submit hit");

    $(".submit-form-btn").css("background-color", "#BDE5F8");
    $(".submit-form-btn").css("color", "#00529B");
    $(".submit-form-btn").css("border", "none");
    $(".submit-form-btn").html('<i class="fa fa-info-circle"></i> Processing request now...');
    $(".submit-form-btn").prop('disabled', true);


    var fd = new FormData();

    var trackerid = window.location.href;
    trackerid = trackerid.replace("https://salesstartest.wpengine.com/client-services-portal-edit-tracker/?","");

    fd.append('newTrackerEdited', '1');
    fd.append('company', $("#companyName").val());
    fd.append('trackerName', $("#trackerName").val());
    fd.append('practice', $("#Practice").val());
    fd.append('month', $("#month").val());
    console.log($("#month").val());
    fd.append('year', $("#startYear").val());
    console.log($("#startYear").val());
    fd.append('_id', trackerid);
    fd.append('activeStatus', $("#activeStatus").val());
    console.log($("#activeStatus").val());

    var coachUsers = $('#AssignCoachUsers').select2('data');
    var coachUsersString = "";

    for (let i = 0; i < coachUsers.length; i++)
    {
        coachUsersString += coachUsers[i].text;
        coachUsersString += " ";
    }
    console.log(coachUsersString);

    var editUsers = $('#AssignEditUsers').select2('data');
    var editUsersString = "";

    for (let i = 0; i < editUsers.length; i++)
    {
        editUsersString += editUsers[i].text;
        editUsersString += " ";
    }
    console.log(editUsersString);

    var viewUsers = $('#AssignViewUsers').select2('data');
    var viewUsersString = "";

    for (let i = 0; i < viewUsers.length; i++)
    {
        viewUsersString += viewUsers[i].text;
        viewUsersString += " ";
    }
    console.log(viewUsersString);

    fd.append('coachUsers', coachUsersString);
    fd.append('editUsers', editUsersString);
    fd.append('viewUsers', viewUsersString);

    js_submit_edit_tracker(fd, submit_edit_tracker_form_callback);
}

function view_redirect_with_tracker_id()
{
    var _id = sessionStorage.getItem("_id");
    window.location.href = "https://salesstartest.wpengine.com/client-services-portal-view-tracker?" + _id + "";
}

function edit_tracker_form_redirect()
{
    var _id = sessionStorage.getItem("_id");
    window.location.href = "https://salesstartest.wpengine.com/client-services-portal-edit-tracker?" + _id + "";
}


function submit_create_tracker_form_callback(data)
{
    var jdata = JSON.parse(data);

    if (jdata.success == 1)
    {   
            var message = jdata.message;
            var company = "Company Name: " + jdata.company;
            var trackerName = "Tracker Name: " + jdata.trackerName;
            var EngagementStartDate = "Annual Budgets and Quarters Set to Start From: " + jdata.month + ", " + jdata.year;
            var createDate = "Date Created: " + jdata.createDateTime;
            var link = "Link to new tracker: https://salesstartest.wpengine.com/client-services-portal-view-tracker/?" + jdata._id;

            $("#response_div").html(message);
            $("#response_div").css("display", "block");
            $("#response_div").css("background-color", "green");
            $("#response_div").css("color", "#FFFFFF");
            $("#response_div").css("padding", "20px");
            $("#response_div").fadeOut(1000);


            $("#response_info_div").css("display", "block");
            $("#response_info_company").html(company);
            $("#response_info_tracker_name").html(trackerName);
            $("#response_info_engagement_date").html(EngagementStartDate);
            $("#response_info_create_date").html(createDate);
            $("#response_info_link").html(link);

            $("#new-tracker-container").css("display", "none");

            $("#response_btn_div").css("display", "inline");
            sessionStorage.setItem("_id", jdata._id);

            $("response_btn_div").css("display", block);
            $("response_btn_div").css("padding-bottom", "2%");

    }
    else {

        var message = "Something went wrong...?";

        $("#response_div").html(message);
        $("#response_div").css("background-color", "red");
        $("#response_div").css("color", "#FFFFFF");
        $("#response_div").css("padding", "20px");

    }

}

function js_submit(fd, callback)
{
    console.log("Sent to JS Submit");
    var submitUrl = 'https://salesstartest.wpengine.com/wp-content/plugins/create-transformation-trackers/process/index.php';

    $.ajax({url: submitUrl, type:'post',data:fd,enctype: 'multipart/form-data',contentType:false,processData:false,success:function(response){ callback(response); }});
}


function reset_tracker_form()
{
    console.log("reset form");
    $("#new-tracker-container").css("display", "block");
    $("#response_btn_div").css("display", "none");
    $("#response_btn_div").css("display", "none");
    $("#response_info_div").css("display", "none");

    $('#companyName').val("");
    $('#trackerName').val("");
    $('#coach').val("");
    $('#Practice').val("");
    $('#month').val("");
    $('#startYear').val("");
    sessionStorage.setItem("_id", null);
}

function submit_edit_tracker_form_callback(data)
{
    $(".submit-form-btn").css("background-color", "#DFF2BF");
    $(".submit-form-btn").css("color", "#4F8A10");
    $(".submit-form-btn").html("<i class='fa fa-check'></i> Tracker Settings Updated!");
    $(".submit-form-btn").css("border", "none");
    $(".submit-form-btn").prop('disabled', false);

    setTimeout(function() { 
    $(".submit-form-btn").delay(3000).css("color", "#ff290b");
    $(".submit-form-btn").css("border", "1px solid #ff290b");
    $(".submit-form-btn").css("background-color", "#FFFFFF");
    $(".submit-form-btn").html("Update Transformation Tracker");
    
    }, 2000);
    
    console.log("done");

}

function js_submit_edit_tracker(fd, callback)
{
    console.log("Sent to edit tracker process");
    var submitUrl = 'https://salesstartest.wpengine.com/wp-content/plugins/create-transformation-trackers/process/edit-tracker-process.php';

    $.ajax({url: submitUrl, type:'post',data:fd,enctype: 'multipart/form-data',contentType:false,processData:false,success:function(response){ callback(response); }});
}

function filter_submission_table()
{
    console.log("Search hit");
    
    $("tr").remove(".submission-row");

    var fd = new FormData();

    fd.append('newSearchSubmitted', '1');
    fd.append('searchTerm', $("#Tracker-Filter-Search").val());

    console.log($("#Tracker-Filter-Search").val());

    js_submit_filter_submission(fd, filter_submission_table_callback);
}


function js_submit_filter_submission(fd, callback)
{
    console.log("Filtering Submissions");
    var submitUrl = 'https://salesstartest.wpengine.com/wp-content/plugins/create-transformation-trackers/process/create-tracker-table.php';

    $.ajax({url: submitUrl, type:'post',data:fd,enctype: 'multipart/form-data',contentType:false,processData:false,success:function(response){ callback(response); }});
}

function filter_cs_submission_table()
{
    console.log("Search hit");
    
    $("tr").remove(".submission-row");

    var fd = new FormData();

    fd.append('newSearchSubmitted', '1');
    fd.append('searchTerm', $("#Tracker-Filter-Search").val());

    console.log($("#Tracker-Filter-Search").val());

    js_submit_cs_filter_submission(fd, filter_submission_table_callback);
}

function js_submit_cs_filter_submission(fd, callback)
{
    console.log("Filtering Submissions");
    var submitUrl = 'https://salesstartest.wpengine.com/wp-content/plugins/create-transformation-trackers/process/create-cs-tracker-table.php';

    $.ajax({url: submitUrl, type:'post',data:fd,enctype: 'multipart/form-data',contentType:false,processData:false,success:function(response){ callback(response); }});
}

function filter_submission_table_callback(data)
{   
    console.log(data);
    $('#Tracker-Submission-table').append(data);
}