<?php  
/**
 * Functions page
 * 
 * All functions that are used within the entire document are stored and access here
 * 
 * @Author  Tom Zielinski <tom.zielinski@dcmail.ca>
 * @version 4.0 (dec, 18th 2020)
 */
    //redirect to new location
    function redirect($url){
        header("location:". $url);
        ob_flush();
    }

    //data dump
    function printData($data){
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    //logout functinon that destroys the session and cookies
    //and restarts the session for a new user
    function logout(){
        session_unset();
        session_destroy();
        session_start();
    }

    //log user email into todays date_log.txt
    function logUserLogin($email, $case){
        $today = date("Ymd");
        $date_time = getDateEST();
    
        $handle = fopen("./logs/" . $today . "_log.txt", "a");

        switch ($case) {
            case 'success':
                fwrite($handle, "Sign in successful on " . $date_time . ". User " . $email . " signed in.\n");
                break;
            case 'fail':
                fwrite($handle, "Sign in attempt on " . $date_time . ". User " . $email . ".\n");
                break;
            default:
                fwrite($handle, "Sign out successful at " . $date_time . ". User " . $email . " signed out.\n");
                break;
        }

        fclose($handle);
    
    }

    //reset password email, that stores the data into a log file
    function logPasswordReset($message){
        $today = date("Ymd");
        $date_time = getDateEST();
    
        $handle = fopen("./logs/" . $today . "_PasswordReset.txt", "a");


        fwrite($handle, $date_time . "- " . $message. "\n================================================\n");
            

        fclose($handle);
    
    }

    //get the todays date and time from toronto
    function getDateEST(){
        date_default_timezone_set('America/Toronto');
    
        return date("Y-m-d G:i:s");
    }

    //a function that displays the form
    function displayForm($displayForm){
        foreach($displayForm as $form){
            echo
            '<label for="' . $form["name"] . '" class="sr-only"> '. $form['label']. '</label>
            <input 
            type="' . $form['type'] . '" 
            name="' . $form['name'] . '" 
            id="' . $form['name'] . '" 
            class="form-control" 
            placeholder="' . $form['label'] . '" 
            value = "' . $form['value'] . '"
            >';
        };
    }
    //required

    //display table function with pagination implemented
    function displayTable($displayTable, $page){
        $header = $displayTable[0];

        //echo table headers
        echo '<table class="table table-striped table-sm">
            <thead>
                <tr>';
                foreach($header as $th){
                    echo '<th>' . $th . '</th>';
                }
            echo'</tr>
            </thead>
            <tbody>';

        $tableData = $displayTable[1];
        //printdata(array_keys($tableData));
        
        //echo table contents/rows
        foreach($tableData as $userId => $data){
            //printdata($userId);
            echo'<tr>';
            foreach($data as $key => $row){
                //printdata($data['id']);
                if($key == "logo_path"){
                    $tmp = '<img src= "./uploads/' . $row . '-' . $data['email'] . '" height="100px" alt="Logo" />';
                }else if($key == "enable"){
                    //printdata($row);
                    if($row == "t"){
                        $tmp = '<form method="POST" action="./salespeople.php">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="active-' . $data['id'] . '" name="active[' . $data['id'] . ']" value="Active" checked>
                                        <label class="form-check-label" for="active-' . $data['id'] . '">Active</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="inactive-' . $data['id'] . '" name="active[' . $data['id'] . ']" value="Inactive">
                                        <label class="form-check-label" for="inactive-' . $data['id'] . '">Inactive</label>
                                    </div>
                                    <button class="btn btn-sm btn-primary btn-block" type="submit">Update</button>
                                </form>';
                    }else if($row == "f"){
                        $tmp = '<form method="POST" action="./salespeople.php">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="active-' . $data['id'] . '" name="active[' . $data['id'] . ']" value="Active">
                                        <label class="form-check-label" for="active-' . $data['id'] . '">Active</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="inactive-' . $data['id'] . '" name="active[' . $data['id'] . ']" value="Inactive" checked>
                                        <label class="form-check-label" for="inactive-' . $data['id'] . '">Inactive</label>
                                    </div>
                                    <button class="btn btn-sm btn-primary btn-block" type="submit">Update</button>
                                </form>';

                    }
                }else{
                    $tmp = $row;
                }
                echo '<td>'. $tmp . '</td>';
            }
            echo '</tr>';
        }

        echo '</tbody>
        </table>';

        //pagination for the table
        echo '<nav aria-label="Page navigation example">
        <ul class="pagination">';
        for ($i=1; $i <= $displayTable[2]; $i++) { 
            echo '<li class="page-item"><a class="page-link" href="./' . $page . '?page=' . $i .'">' . $i . '</a></li>';

        }
        echo '</ul>
        </nav>';
    }

    //send email, doesnt have SMTP so log the email instead
    function sendEmail($to, $subject, $message, $headers = ""){
        //mail($to, $subject, $message, implode("\r\n", $headers));
        $concat = "To: " . $to . "\nSubject: " . $subject . "\nMessage: " . $message;
        logPasswordReset($concat);
    }

    //validate first name, not empty or null, not numeric and has a maax length
    function validateFirstName($firstName){

        global $error;
        if(!isset($firstName) || $firstName == ""){
    
            $error .= "First name cannot be empty<br>";
            
             //returns false if the input  does not match the username. 
            return false;
        }
        else if(strlen($firstName) > MAX_FIRST_NAME_LENGTH )
        {
            $error .= "First name cannot be more then " . MAX_FIRST_NAME_LENGTH ." characters<br>";
            
             //returns false if the input  does not match the username. 
            return false;
        }
        else if(is_numeric($firstName))
        {
            $error .= "First name cannot be a number<br>";
            
             //returns false if the input  does not match the username. 
            return false;
        }
    
        //returns true if the input (in this case the username) does not comply with the regular expression.
    
        return true;
    
    }
    
    //validate last name, not empty or null, not numeric and has a maax length
    function validateLastName($lastName){
    
        global $error;
        if(!isset($lastName) || $lastName == ""){
    
            $error .= "Last name cannot be empty<br>";
            
             //returns false if the input  does not match the username. 
            return false;
        }
        else if(strlen($lastName) > MAX_LAST_NAME_LENGTH )
        {
            $error .= "Last name cannot be more then " . MAX_LAST_NAME_LENGTH ." characters<br>";
            
             //returns false if the input  does not match the username. 
            return false;
        }
        else if(is_numeric($lastName))
        {
            $error .= "Last name cannot be a number<br>";
            
             //returns false if the input  does not match the username. 
            return false;
        }
    
        //returns true if the input (in this case the username) does not comply with the regular expression.
    
        return true;
    
    }
    
    //validateEmail function which takes in one fromal parameter ($email).
    function validateEmail($email){
    
        global $error;
    
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    
             $error .= "Invalid Email <br>";
    
            return false;
        }
    
        return true;
    
    }
    
    //validatePassword function which takes in one formal parameter (password). 
    function validatePassword($password){
    
        global $error;	
    
        if(!isset($password) || $password == ""){
    
            $error .= "password cannot be empty<br>";
            
             //returns false if the input  does not match the username. 
            return false;
        }
        else if(strlen($password) < MINIMUM_PASSWORD_LENGTH )
        {
            $error .= "Password must be greater then " . MINIMUM_PASSWORD_LENGTH ." characters<br>";
            
             //returns false if the input  does not match the username. 
            return false;
        }
        else if(strlen($password) > MAXIMUM_PASSWORD_LENGTH )
        {
            $error .= "Password must be less then " . MAXIMUM_PASSWORD_LENGTH ." character<br>";
            
             //returns false if the input  does not match the username. 
            return false;
        }
    
        return true;
    }

    //compares password with confirm passowrd
    function validateConfirmPassword($password, $confirmPassword){

        if(strcmp($password, $confirmPassword) !== 0)
        {
            //$error .="Confirm password is not the same";
            return false;
        }
        
        return true;
    }

    //validates if the file exists, has no errors, is the correct file type and correct size, also checks if file exists so it doesnt rewrite data
    function validateFile($file, $email){
        global $error;	

        if($file['filePath']['name'] == ""){
            $error .= "Please select a logo file";
            return false;
        }else if($file['filePath']['error'] != 0){
            $error .= "Error uploading file";
            return false;
        }else if($file['filePath']['type'] != "image/jpeg" 
            && $file['filePath']['type'] != "image/pjpeg"
            && $file['filePath']['type'] != "image/png"
            && $file['filePath']['type'] != "image/gif"){
            $error .= "Error file must be a jpeg, pjpeg, gif or png";
            return false;
        }else if($file['filePath']['size'] > MAXIMUM_FILE_SIZE){
            $error .= "Error the file you have selected is too large, must be smaller than " . MAXIMUM_FILE_SIZE_MB;
            return false;
        }

        $fileName = basename($file['filePath']['name']);
        if(file_exists("./uploads/$fileName-$email")){
            $error .= "Error this file already exists";
            return false;
        }

        return true;
    }

    //validate phone number, cannot be null or empty and is between a min and max, for quick validation
    function validatePhoneNumber($PhoneNumber){
    
        global $error;	
    
        if(!isset($PhoneNumber) || $PhoneNumber == ""){
    
            $error .= "Phone number cannot be empty<br>";
            
             //returns false if the input  does not match the username. 
            return false;
        }
        else if($PhoneNumber < MINIMUM_PHONENUMBER_LENGTH )
        {
            $error .= "Phone number must be greater then " . MINIMUM_PHONENUMBER_LENGTH ."<br>";
            
             //returns false if the input  does not match the username. 
            return false;
        }
        else if($PhoneNumber > MAXIMUM_PHONENUMBER_LENGTH )
        {
            $error .= "Phone number must be less then " . MAXIMUM_PHONENUMBER_LENGTH ."<br>";
            
             //returns false if the input  does not match the username. 
            return false;
        }
    
        return true;
    }
    
?>