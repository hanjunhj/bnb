<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register new customer</title>
</head>
<body>
   <?php
    function cleanInput($data){
        return htmlspecialchars(stripslashes(trim($data)));
    }

    if(isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] =='Register')){
        include "config.php";
        $DBC = mysqli_connect(DBHOST,DBUSER,DBPASSWORD,DBDATABASE);
    
        if(mysqli_connect_errno()){
            echo "Error:unable to connect to MYSQL." .mysqli_connect_error();
            exit;
    
        }

        $error = 0;
        $msg = 'Error:';

        if(isset($_POST['firstname']) and !empty($_POST['firstname'])
        and is_string($_POST['firstname'])){
            $fn = cleanInput($_POST['firstname']);

            $firstname = (strlen($fn)>50)?substr($fn,3,50):$fn;
        }else{
            $error++;
            $msg .='Invalid firstname';
            $firstname ='';
        }

        $lastname = cleanInput($_POST['lastname']);
        $email = cleanInput($_POST['email']);
        $password = cleanInput($_POST['password']);

        if($error ==0){
            $query = "INSERT INTO customer (fname, lname, email, password) VALUES(?,?,?,?)";

            $stmt = mysqli_prepare($DBC,$query); //prepare the query

            mysqli_stmt_bind_param($stmt,'ssss',$firstname,$lastname,$email,$password);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "<h2>customer saved </h2>";
        }else{
            
            echo "<h2>$msg</h2>".PHP_EOL;
        }
        mysqli_close($DBC);
    
}
   ?>

<h1> New Customer Registration</h1>
<h2>
    <a href="memberslisting.php">[Return to the Customer listing]</a>
    <a href="/booktickets/index.php">[Return to main page]</a>
</h2>

<form action="registercustomer.php" method="POST">

    <p>
        <label for="">First Name:</label>
        <input type="text" id="firstname" name="firstname" minlength="3" maxlength="50" required>
    </p>

    <p>
        <label for="">Last Name:</label>
        <input type="text" id="lastname" name="lastname" minlength="3" maxlength="50" required>
    </p>

    <p>
        <label for="">Email:</label>
        <input type="email" id="email" name="email"  maxlength="100" required>
    </p>

    <p>
        <label for="">Password:</label>
        <input type="password" id="password" name="password" minlength="8" maxlength="32" required>
    </p>

    <input type="submit" name="submit" value="Register">


</form>
</body>
</html>