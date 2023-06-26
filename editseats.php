<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Seat Options</title>
    
    
</head>
<body>
<?php

include "checksession.php";
checkUser();
loginStatus();
//take the details about server and database
include "config.php"; //load in any variables
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

//insert DB code from here onwards
//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
    exit; //stop processing the page further
}


//function to clean input but not validate type and content
function cleanInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

//check if id exists
if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid ticket id</h2>";
        exit;
    }
}


//on submit check if empty or not string and is submited by POST
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {

    $seats = cleanInput($_POST['seat']);
    $id = cleanInput($_POST['id']);

     
     $upd = "UPDATE `ticket` SET seat_options=? WHERE ticketID=?";

        $stmt = mysqli_prepare($DBC, $upd); //prepare the query
        mysqli_stmt_bind_param($stmt, 'si', $seats, $id);

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        //print message
        echo "<h5>Seats updated </h5>";
    } 


$query = 'SELECT  seat_options FROM `ticket` WHERE ticketID=' .$id;


$result = mysqli_query($DBC, $query);
$rowcount = mysqli_num_rows($result);

?>
<h1>Update ticket</h1>
<h2>
    <a href='ticketslisting.php'>[Return to the Tickets listing]</a>
    <a href="index.php">[Return to main page]</a>
</h2>

<div>
    <div>
        <form method="POST">
            
            <div>
            <input type="hidden" name="id" value="<?php echo $id;?>"> 
            </div>
            <?php
       if ($rowcount > 0){
           $row = mysqli_fetch_assoc($result);
           ?>
           <div>
                <label for="seats">Seat Options:</label>
                <input type="text" id="seat" name="seat" value="<?php echo $row['seat_options']?>"> 
            </div>


        <?php
       } else echo "<h5>No ticket found!</h5>"
       ?>        
                      <br>    <br>         
         
            <div>
                <input type="submit" name="submit" value="Update">
            </div>

        </form>
        <?php
        mysqli_free_result($result);
        mysqli_close($DBC);
        ?>
       
</body>
</html>