<?php
    session_start();
    include_once "config.php";
    // function in PHP that is used to escape special characters in a string to make it safe for use in a MySQL query.
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password)){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
            // mysqli_num_rows() -> function used with the MySQLi extension to retrieve the number of rows in a result set.
            if(mysqli_num_rows($sql) > 0){
                echo "$email - This email already exist!";
            }else{

                //isset() is a PHP function used to check if a variable is set and is not null. It returns true if the variable exists and has a value other than null, and false otherwise.
                if(isset($_FILES['image'])){
                    //$_FILES is a PHP superglobal variable that is used to collect and interact with file upload information that is sent to the server through a form with the attribute enctype="multipart/form-data". When a user submits a form with a file input field, the information about the uploaded file is made available in the $_FILES array.
                    $img_name = $_FILES['image']['name'];
                    $img_type = $_FILES['image']['type'];
                    $tmp_name = $_FILES['image']['tmp_name'];
                    

                    //The explode() function in PHP is used to split a string into an array of substrings based on a specified delimiter. This function is helpful when you have a string containing multiple values separated by a common character, and you want to break it down into individual pieces
                    $img_explode = explode('.',$img_name);
                    // end() This function is particularly useful when you want to access the last element of an array without knowing its key.
                    $img_ext = end($img_explode);
    
                    $extensions = ["jpeg", "png", "jpg"];
                    //The in_array() function in PHP is used to check if a specific value exists in an array. It returns true if the value is found in the array, and false otherwise.
                    if(in_array($img_ext, $extensions) === true){
                        $types = ["image/jpeg", "image/jpg", "image/png"];
                        if(in_array($img_type, $types) === true){
                            //The time() function in PHP is used to get the current Unix timestamp. A Unix timestamp is a way of representing time as the number of seconds that have elapsed since January 1, 1970, at 00:00:00 UTC (Coordinated Universal Time)
                            $time = time();
                            $new_img_name = $time.$img_name;

                            //The move_uploaded_file() function in PHP is used to move an uploaded file to a new location on the server. This function is commonly used when handling file uploads through HTML forms with the enctype="multipart/form-data" attribute.
                            if(move_uploaded_file($tmp_name,"images/".$new_img_name)){
                                
                                //The rand() function in PHP is used to generate a random integer. It takes two optional parameters: rand(min, max);
                                $ran_id = rand(time(), 100000000);
                                $status = "Active now";


                                //The md5() function in PHP is a one-way hashing function that is commonly used to generate a 32-character hexadecimal hash value (128 bits) from a given input string. It produces a fixed-length hash, which makes it useful for storing passwords or creating checksums for data integrity checks.
                                $encrypt_pass = md5($password);
                                $insert_query = mysqli_query($conn, "INSERT INTO users (unique_id, fname, lname, email, password, img, status)
                                VALUES ({$ran_id}, '{$fname}','{$lname}', '{$email}', '{$encrypt_pass}', '{$new_img_name}', '{$status}')");

                                //The mysqli_query() function in PHP is used to execute a query on a MySQL database. It returns a result set for SELECT, SHOW, DESCRIBE, and EXPLAIN queries, or true or false for other types of SQL statements.
                                if($insert_query){
                                    $select_sql2 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                                    if(mysqli_num_rows($select_sql2) > 0){
                                        $result = mysqli_fetch_assoc($select_sql2);
                                        //$_SESSION is a superglobal variable in PHP that is used to store session variables across multiple pages. Sessions provide a way to persist data on the server between requests from the same user. They are often used to store user-specific information, such as login status, user preferences, or shopping cart contents.
                                        $_SESSION['unique_id'] = $result['unique_id'];
                                        echo "success";
                                    }else{
                                        echo "This email address not Exist!";
                                    }
                                }else{
                                    echo "Something went wrong. Please try again!";
                                }
                            }
                        }else{
                            echo "Please upload an image file - jpeg, png, jpg";
                        }
                    }else{
                        echo "Please upload an image file - jpeg, png, jpg";
                    }
                }

                
            }
        }else{
            echo "$email is not a valid email!";
        }
    }else{
        echo "All input fields are required!";
    }
    // echo "hii rao this is message from signup php"
?>