<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View flights</title>
</head>

<body>

    
    <?php


include "checksession.php";



    include "config.php";
    $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

    if (mysqli_connect_errno()) {
        echo "Error:Unable to connect to MySql." . mysqli_connect_error();
        exit; //stop processing the page further.
    }

   

    //prepare a query and send it to the server

    $query = 'SELECT flightcode, flightname, departure_location, destination_location FROM flight ORDER BY flightcode';
    $result = mysqli_query($DBC, $query);
    $rowcount = mysqli_num_rows($result);
    ?>

    

    <h1>Flights Listing</h1>
    <h2>
        <?php  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']  ==1){?>

        <a href="addflight.php">[Add a flight]</a>           
        <?php } ?>
            <a href="/booktickets/index.php">[Return to main page]</a>
    </h2>

    <table border="1">
        <thead>
            <tr>
                <th>Flight Code</th>
                <th>Flight Name</th>
                <th>Action</th>
               
            </tr>
        </thead>

        <!-- .PHP_EOL can be "\n"
    represents the endline character for the current system -->
        <?php
        if ($rowcount > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['flightcode'];
                
                echo '<tr><td>' . $row['flightcode'] . '</td><td>' . $row['flightname'] . '</td>';

                echo '<td><a href="viewflights.php?id='.$id.'">[view]</a>';

                if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] ==1){
                    echo '<a href="editflight.php?id='.$id.'">[edit]</a>';
                    echo '<a href="deleteflight.php?id='.$id.'">[delete]</a></td>';

                }

                echo '</tr>' . PHP_EOL;
            }
        } else echo "<h2>No flights found!</h2>";
        echo "</table>";
        
        mysqli_free_result($result); //free any memory used by the query
        mysqli_close($DBC);
        ?>
 
 <?php 
        if (isset($_SESSION['username'])){
          if (isset($_POST['logout'])) logout();

          $un = $_SESSION['username'];
            if($_SESSION['loggedin'] == 1){ ?>
           
           
           <h6>Logged in as <?php echo $un ?></h6>
            
            
            <form method="post">
            <input  type="submit" name="logout" value="Logout"> 
            </form>

	
          <?php 
            }
        }
        ?>

</body>

</html>