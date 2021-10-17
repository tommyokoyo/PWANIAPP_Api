<?php
    
    require_once 'user.php';

    $lecturer_id = "";
    
    $username = "";
    
    $password = "";
    
    $email = "";

    $table_name = "";

    $apicall = "";

    $year_of_study = "";

    $course = "";

    $unique_ID = "";

    $longitude = "";

    $latitude = "";

    if(isset($_POST['lecturer_id'])){ $lecturer_id = $_POST['lecturer_id']; }

    if(isset($_POST['username'])){ $username = $_POST['username']; }
    
    if(isset($_POST['password'])){ $password = $_POST['password']; }
    
    if(isset($_POST['email'])){ $email = $_POST['email']; }

    if(isset($_POST['table_name'])){ $table_name = $_POST['table_name']; }

    if(isset($_POST['apicall'])){ $apicall = $_POST['apicall']; }

    if(isset($_POST['year_of_study'])) { $year_of_study = $_POST['year_of_study']; }

    if(isset($_POST['course'])) { $course = $_POST['course']; }

    if(isset($_POST['unique_ID'])) { $unique_ID = $_POST['unique_ID']; }

    if(isset($_POST['longitude'])) { $longitude = $_POST['longitude']; }

    if(isset($_POST['latitude'])) { $latitude = $_POST['latitude']; }

    
    $userObject = new User();

    switch($apicall){
        case "login": {

            if(!empty($username) && !empty($password)){
        
                $hashed_password = md5($password);
                
                $response = $userObject->loginUsers($username, $hashed_password);
                
                echo json_encode($response);
            } else {
                
                 $response['success'] = 0;
                 $response['message'] = "Details not passed";
                 
                 echo json_encode($response);
            }

        }

        break;

        case "register": {

            if(!empty($username) && !empty($password) && !empty($email)){
                
                $hashed_password = md5($password);
                
                $json_registration = $userObject->createNewRegisterUser($username, $hashed_password, $email);
                
                echo json_encode($json_registration);
                
            }
            
        }
        
        break;

        case "student_checkin": {

            if(!empty($admission_no) && !empty($lecturer_id) && !empty($longitude) && !empty($latitude)){

                $checkin_response = $userObject -> checkIn($admission_no, $lecturer_id, $table_name, $longitude, $latitude);

                echo json_encode($checkin_response);

            } else {

                $response['success'] = 0;
                $response['message'] = "Empty Values";

                echo json_encode($response);

            }

        }
        
        break;

        case "": {

        }
        
        break;
    }
    
    ?>