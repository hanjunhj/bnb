<?php
//Our booking search/filtering engine
include "config.php"; //load in any variables

include "ChromePhp.php";
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE) or die();
 
//do some simple validation to check if sq contains a string
$sqa = $_POST['sqa'];
$sqb = $_POST['sqb'];

ChromePhp::log("$sqa");
ChromePhp::log("$sqb");


$searchresult = '';
if (true) {
    //prepare a query and send it to the server using our search string as a wildcard on roomID
    $query = "SELECT flightcode,flightname,departure_location,
	destination_location 
	FROM flight WHERE flightcode 
	NOT IN (SELECT flightcode FROM ticket 
	WHERE departureDate >= '$sqa' AND arrivalDate <='$sqb')";

    $result = mysqli_query($DBC,$query);
    $rowcount = mysqli_num_rows($result); 

    //makes sure we have bookings
    if ($rowcount > 0) {  
        $rows=[]; //start an empty array      
        //append each row in the query result to our empty array until there are no more results                    
        while ($row = mysqli_fetch_assoc($result)) {            
            $rows[] = $row; 
        }

        ChromePhp::log($rows);
        //take the array of our 1 or more bookings and turn it into a JSON text
        $searchresult = json_encode($rows);
        //this line is cruicial for the browser to understand what data is being sent
        header('Content-Type: text/json; charset=utf-8');

    } else echo "<tr><td colspan=3><h5>No Bookings found!</h5></td></tr>";

} else echo "<tr><td colspan=3> <h6>Invalid search query</h6></td></tr>";

mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done

echo  $searchresult;
?>