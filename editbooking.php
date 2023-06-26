<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
	
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    
    <script>
        //insert datepicker jQuery

        $(document).ready(function() {
            $.datepicker.setDefaults({
                dateFormat: 'yy-mm-dd'
            });
            $(function() {
                depa = $("#depa").datepicker()
                arr = $("#arr").datepicker()

                function getDate(element) {
                    var date;
                    try {
                        date = $.datepicker.parseDate(dateFormat, element.value);
                    } catch (error) {
                        date = null;
                    }
                    return date;
                }
            });
        });
    </script>
	
	
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

    $flight = cleanInput($_POST['flights']);
    
    $depa = $_POST['depa'];
    $arr = $_POST['arr'];

    $prices = cleanInput($_POST['price']);
    $seats = cleanInput($_POST['seat']);
    $id = cleanInput($_POST['id']);

     
        $upd = "UPDATE `ticket` SET flightcode=?, departureDate=?, arrivalDate=?, price=?, seat_options=? WHERE ticketID=?";

        $stmt = mysqli_prepare($DBC, $upd); //prepare the query
        mysqli_stmt_bind_param($stmt, 'issdsi', $flight, $depa, $arr, $prices, $seats, $id);

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        //print message
        echo "<h5>Booking updated successfully</h5>";
    } 


$query = 'SELECT ticket.ticketID, flight.flightcode, flight.flightname, flight.departure_location,flight.destination_location, ticket.departureDate, ticket.arrivalDate, ticket.price, ticket.seat_options FROM `ticket`
INNER JOIN `flight` ON ticket.flightcode=flight.flightcode WHERE ticketID=' .$id;


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
                <label for="flights">Flights:</label>
                <select name="flights" id="flights">
                    <?php
                    if ($rowcount > 0) {
                       $row = mysqli_fetch_assoc($result);
                    ?>

                    <option value="<?php echo $row['flightcode']; ?>">
                        <?php echo $row['flightname'] . ' '
                        . $row['departure_location'] . ' '
                        . $row['destination_location'] ?>
                    </option>

                    <?php 
                    }else echo "<option>No flights found</option>";
                   ?>
                </select>
            </div>

            <div>
            <input type="hidden" name="id" value="<?php echo $id;?>"> 
            </div>
            
            <br>
            
            <br>
            <div>
                <label for="depa">Departure Date:</label>
                <input type="text" id="depa" name="depa"  value="<?php echo $row['departureDate']?>" required> 
            </div>

            <br>
            <div>
                <label for="arr">Arrival Date:</label>
                <input type="text" id="arr" name="arr" required value="<?php echo $row['arrivalDate']?>"> 
            </div>
            <br>

            <div>
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" required value="<?php echo $row['price']?>">
            </div>

            <br>
            <div>
                <label for="seats">Seat Options:</label>
                <input type="text" id="seat" name="seat" value="<?php echo $row['seat_options']?>"> 
            </div>

            <br>
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