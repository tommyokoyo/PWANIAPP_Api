<?php
    
    include_once 'db-connect.php';
    
    class User{
        
        private $db;
        
        private $db_table = "users";
        private $db_lectuer = "Lectuerer";
        private $db_student = "student";
        
        //constructor ot the class db-connect called when connecting to db
        public function __construct(){
            $this->db = new DbConnect();

            $json = array();
        }
            
       
        
        
        //this fuction checks to see if Lectuerer exists
        public function isLecLoginExist($username, $password){

            $sql = "SELECT `username`, `password`, `email` FROM `$this->db_lectuer` WHERE username = '$username' AND password = '$password'";
            
            $result = mysqli_query($this->db->getDb(), $sql);
            
            if(mysqli_num_rows($result) > 0){
                
                mysqli_close($this->db->getDb());
                        
                return true;
                
            } else {
        
                mysqli_close($this->db->getDb());
                        
                return false;
           
            } 
        
        }

            //this fuction checks to see if student exists
            public function isStudentLoginExist($username, $password){
            
                //check students database
                        
                $sql = "SELECT `username`, `password`, `email` FROM `$this->db_student` WHERE username = '$username' AND password = '$password'";

                $result = mysqli_query($this->db->getDb(), $sql);
                
                if(mysqli_num_rows($result) > 0){
                           
                     mysqli_close($this->db->getDb());
                                        
                     return true;
                             
                    
                    
                } else {

                    mysqli_close($this->db->getDb());
                                        
                    return false;
                  
                }
            
            }
        
        public function isEmailUsernameExist($username, $email){
            
            $sql = "SELECT * from ".$this->db_table." where `username` = `$username` AND email = `$email` ";
            
            $result = mysqli_query($this->db->getDb(), $sql);
            
            if(mysqli_num_rows($result) > 0){
                
                mysqli_close($this->db->getDb());
                
                return true;
                
            }
            
            
            return false;
            
        }
        
        //validates the email
        public function isValidEmail($email){
            return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        }
        
        
        //register new user
        public function createNewRegisterUser($username, $password, $email){
            
            //checks if the email exists
            $isExisting = $this->isEmailUsernameExist($username, $email);
                        
            if($isExisting){
                
                $json['success'] = 0;
                $json['message'] = "Error in registering. Probably the username/email already exists";
            }
            
            else{
                
            $isValid = $this->isValidEmail($email);
                
                if($isValid)
                {
                $sql = "INSERT into `".$this->db_table."` (`username`, `password`, `email`, `created_at`, `updated_at`) VALUES (`$username`, `$password`, `$email`, `NOW()`, `NOW()`)";
                
                $inserted = mysqli_query($this->db->getDb(), $sql);
                
                if($inserted == 1){
                    
                    $json['success'] = 1;
                    $json['message'] = "Successfully registered the user";
                    
                }else{
                    
                    $json['success'] = 0;
                    $json['message'] = "Error in registering. Probably the username/email already exists";
                    
                }
                
                mysqli_close($this->db->getDb());
                }
                else{
                    $json['success'] = 0;
                    $json['message'] = "Error in registering. Email Address is not valid";

                
                }
                
            }
            
            return $json;
            
        }

        public function isStudentCheckedIn($admission){

            $sql ="SELECT FROM `student` (`admission`) VALUES ('$admission')";
            
            $result = mysqli($this->db->getDb2, $sql);

            if(mysqli_num_rows($result) > 0 ){

                mysqli_close($this->db->getDb2());

                return true;

            } else {

                mysqli_close($this->db->getDb2());

                /*
                //add student in the table and return false
                $sql = "INSERT INTO `student` (`username`, `password`, `email`, `admission`) VALUES ('maryanne okoyo', 'student', 'maryokoyo@gmail.com', 'SB30/PU/005')";
                mysqli_close($this->db->getDb());

                */
                return false;

            }

        }

        public function isStudentWithinRange($lecturer_id, $longitude, $latitude){

            $lec_latitude = "";
            $lec_longitude = "";

            $sql = "SELECT `longitude`, `latitude` FROM `$table_name` WHERE `lecturer_id` = `$lecturer_id`";
            $result = mysqli($this->db->getDb2(), $sql);

            if(mysqli_num_rows($result) > 0){

                //fetch latitude and longitude of lecturer
                while ($row = mysqli_fetch_Assoc($result)){

                    $lec_latitude = $row["latitude"];
                    $lec_longitude = $row["longitude"];
                }

                //compare the coordinates
                if(($lec_latitude == $latitude) && ($lec_longitude == $longitude)){

                    return true;

                } else if ((($lec_latitude - $latitude == 0.0001) || ($lec_latitude - $latitude == -0.0001)) && (($lec_longitude - $longitude == 0.0001) || ($lec_longitude - $longitude == -0.0001))){

                    return true;

                } else {

                    return false;
                }


                
            } else {

                return false;

            }

        }

        public function addStudentToTable($admission, $table_name, $longitude, $latitude){

            $sql = "INSERT INTO `$table_name` (`admission`, `longitude`, `latitude`, `time_signed`) VALUES ('$admission', '$longitude', '$latitude', current_timestamp())";
            $result = mysqli($this->db->getDb2(), $sql);

            if(mysqli_query($result)){

                return true;
                
            }else {

                return false;      

            }
        }

        public function checkIn($admission_no, $lecturer_id, $table_name, $longitude, $latitude){

            $response = array();

            //check if student is already signed in
            $canStudentCheckIn = $this->isStudentCheckedIn($admission);

            if($canStudentCheckIn){

                $response['success'] = 0;
                $response['message'] = "Already Checked In";

            } else {

                //check if student is within the class range
                $checkStudentRange = $this -> isStudentWithinRange($lecturer_id, $longitude, $latitude);
                
                if ($checkStudentRange){

                    //add student to table
                    $checkInStudent = $this->addStudentToTable($admission, $table_name, $longitude, $latitude);

                    if($checkInStudent){

                        $response['success'] = 1;
                        $response['message'] = "Successfully Checked in";
                    
                    } else {

                        $response['success'] = 0;
                        $response['message'] = "Error adding to table";

                    }
                    

                } else {

                    //display error message
                    $response['success'] = 0;
                    $response['message'] = "Not within class range";
                }
                
            }
            
            return $response;

        }
        
        public function loginUsers($username, $password){
            
            $response = array();
            
            //$canLecLogin = $this->isLecLoginExist($username, $password);
            $canStudentLogin = $this->isStudentLoginExist($username, $password);
            /*
            if($canLecLogin){
                
                $json['success'] = 2;
                $json['message'] = "Successfully logged in";
                
            }else
            */
            if ($canStudentLogin){
    
                $response['success'] = 1;
                $response['message'] = "Successfully logged in";
    
            } else {
    
                $response['success'] = 0;
                $response['message'] = "Incorrect details";
    
            }
    
            return $response;
        }
    }
    ?>