<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current tickets</title>
</head>
<body>

<?php
     include "checksession.php";
     checkUser();
     loginStatus();
    include "config.php";
    $DBC = mysqli_connect(DBHOST,DBUSER,DBPASSWORD,DBDATABASE);

    if(mysqli_connect_errno()){
        echo "Error:unable to connect to MYSQL." .mysqli_connect_error();
        exit;

    }


    $query = "SELECT ticketID, flightcode, departureDate,arrivalDate FROM ticket ORDER BY ticketID";
    $result = mysqli_query($DBC,$query);
    $rowcount = mysqli_num_rows($result);


?>
    
<h1>Current tickets</h1>
<h2>
    <a href="bookticket.php">[Book a ticket]</a>
    <a href="/booktickets/index.php">[Return to main page]</a>
</h2>

<table border="1">
    <thead>
        <tr>
            <th>Current tickets (Flights, departureDae, arrivalDate)</th>
            <th>Action</th>
        </tr>
    </thead>

    <?php
        if($rowcount > 0){
            while ($row = mysqli_fetch_assoc($result)){

                $id = $row['ticketID'];
                $fc =$row['flightcode'];

                $sql = 'SELECT flightcode, flightname, departure_location, destination_location 
                FROM flight WHERE flightcode='.$fc;

                $res =mysqli_query($DBC,$sql);
                $rowc = mysqli_num_rows($res);

                if( $rowc > 0){
                    $rowr = mysqli_fetch_assoc($res);
                }

                echo '<tr><td>'. $rowr['flightname'] .',' .$row['departureDate']
                .',' .$row['arrivalDate'].'</td>';

                echo '<td><a href="ticketdetails.php?id='.$id.'" >[view]</a> ';
                echo '<a href="editbooking.php?id='.$id.'" >[edit]</a> ';
                echo '<a href="editseats.php?id='.$id.'" >[manage seats]</a> ';
                echo '<a href="deleteticket.php?id='.$id.'" >[delete]</a> ';
                echo '</tr>'.PHP_EOL;
                mysqli_free_result($res);
             
            }
        }else echo "<h2>No tickets found!</h2>";

        mysqli_free_result($result);
        mysqli_close($DBC);
    ?>



</table>
</body>
</html>