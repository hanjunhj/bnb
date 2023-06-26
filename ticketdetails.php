<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Ticket Details</title>
</head>
<body>
<?php
  include "checksession.php";
  checkUser();
  loginStatus();
include "config.php";
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

if (mysqli_connect_errno()) {
    echo "Error:Unable to connect to MySql." . mysqli_connect_error();
    exit; //stop processing the page further.
}

//check if id exists
if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid ticket id</h2>";
        exit;
    }
}

$query = 'SELECT ticket.ticketID, flight.flightname, flight.departure_location,flight.destination_location, 
ticket.departureDate, ticket.arrivalDate, ticket.price, ticket.seat_options FROM `ticket`
INNER JOIN `flight` ON ticket.flightcode=flight.flightcode WHERE ticketID=' . $id;

$result = mysqli_query($DBC, $query);
$rowcount = mysqli_num_rows($result);
?>

<!-- We can add a menu bar here to go back -->
<h1>Ticket Details View</h1>
<h2><a href="ticketslisting.php">[Return to the ticket listing]</a>
    <a href="/bookticket/index.php">[Return to the main page]</a>
</h2>
<?php
if ($rowcount > 0) {
    echo "<fieldset><legend>Ticket Detail #$id</legend><dl>";
    $row = mysqli_fetch_assoc($result);
    
    echo "<dt>Flight name: </dt><dd>" . $row['flightname'] . "</dd>" . PHP_EOL;
    echo "<dt>Departure Location: </dt><dd>" . $row['departure_location'] . "</dd>" . PHP_EOL;
    echo "<dt>Destination Location: </dt><dd>" . $row['destination_location'] . "</dd>" . PHP_EOL;

    echo "<dt>Departure Date: </dt><dd>" . $row['departureDate'] . "</dd>" . PHP_EOL;
    echo "<dt>Arrival Date: </dt><dd>" . $row['arrivalDate'] . "</dd>" . PHP_EOL;
    echo "<dt>Price: </dt><dd>" ."$". $row['price'] . "</dd>" . PHP_EOL;
    echo "<dt>Seats Options: </dt><dd>" . $row['seat_options'] . "</dd>" . PHP_EOL;
    echo '</dl></fieldset>' . PHP_EOL;

} else echo "<h5>No ticket found! Possbily deleted!</h5>";
mysqli_free_result($result);
mysqli_close($DBC);
?>


</body>

</html>