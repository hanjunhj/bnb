<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a ticket</title>
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
  
include "ChromePhp.php";
    include "checksession.php";
    checkUser();
    loginStatus();
    include "config.php"; //load in any variables
    $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
    $searchresult = '';

// var_dump
// echo "<pre>";
// var_dump($_POST);
// var_dump($_GET);

// echo "</pre>";




    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
        exit; //stop processing the page further
    }


    //function to clean input but not validate type and content
    function cleanInput($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }


    //on submit check if empty or not string and is submited by POST
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Book')) {

        #code
        $flight = cleanInput($_POST['flights']);
        $customer = cleanInput($_POST['customers']);
        $depa = $_POST['depa'];
        $arr = $_POST['arr'];
        $prices = cleanInput($_POST['price']);
        $seats = cleanInput($_POST['seat']);

        $error = 0;
        $msg = "Error:";

        $in = new DateTime($depa);
        $out = new DateTime($arr);

        if ($in >= $out) {
            $error++;
            $msg .= "Arrival date cannot be earlier or equal to departure date";
            $arr = '';
        }

        if ($error == 0) {
            $query = "INSERT INTO ticket (flightcode, customerID,departureDate,
            arrivalDate, price, seat_options) VALUES (?,?,?,?,?,?)";

            $stmt = mysqli_prepare($DBC, $query);

            mysqli_stmt_bind_param($stmt, 'iissds', $flight, $customer, $depa, $arr, $prices, $seats);

            ChromePhp::log($flight);
            ChromePhp::log($customer);
            ChromePhp::log($depa);
            ChromePhp::log($arr);
            ChromePhp::log($prices);
            ChromePhp::log($seats);
           

            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            echo "<h5>Booking added successfully.</h5>";
        } else {
            echo "<h5>$msg</h5>" . PHP_EOL;
        }
    }




    $query = 'SELECT flightcode, flightname, departure_location, destination_location FROM flight ORDER BY flightcode';
    $result = mysqli_query($DBC, $query);
    $rowcount = mysqli_num_rows($result);

    $query1 = 'SELECT customerID, fname, lname, email FROM customer ORDER BY customerID';
    $result1 = mysqli_query($DBC, $query1);
    $rowcount1 = mysqli_num_rows($result1);



    ?>


    <h1>Book a ticket</h1>
    <h2>
        <a href='ticketslisting.php'>[Return to the Tickets listing]</a>
        <a href="index.php">[Return to main page]</a>
    </h2>

    <div>

        <form method="POST">
            <div>
                <label for="flights">Flights:</label>
                <select name="flights" id="flights">
                    <?php
                    if ($rowcount > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $id = $row['flightcode']; ?>

                            <option value="<?php echo $row['flightcode']; ?>">
                                <?php echo $row['flightname'] . ' '
                                    . $row['departure_location'] . ' '
                                    . $row['destination_location']

                                ?>
                            </option>
                    <?php }
                    } else echo "<option>No flights found</option>";
                    mysqli_free_result($result);
                    ?>

                </select>
            </div>


            <br>
            <div>
                <label for="customers">Customers:</label>
                <select name="customers" id="customers">
                    <?php
                    if ($rowcount1 > 0) {
                        while ($row = mysqli_fetch_assoc($result1)) {
                            $id = $row['flightcode']; ?>

                            <option value="<?php echo $row['customerID']; ?>">
                                <?php echo $row['customerID'] . ' '
                                    . $row['fname'] . ' '
                                    . $row['lname'] . ' - '
                                    . $row['email']

                                ?>
                            </option>
                    <?php }
                    } else echo "<option>No flights found</option>";
                    mysqli_free_result($result1);
                    ?>
                </select>
            </div>
            <br>
            <div>
                <label for="depa">Departure Date:</label>
                <input type="text" id="depa" name="depa" required>
            </div>
            <br>
            <div>
                <label for="arr">Arrival Date:</label>
                <input type="text" id="arr" name="arr" required>
            </div>
            <br>
            <div>
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" required>
            </div>
            <br>
            <div>
                <label for="seats">Seat Options:</label>
                <input type="text" id="seat" name="seat">
            </div>
            <br>
            <div>
                <input type="submit" name="submit" value="Book">
            </div>

        </form>

    </div>
    <br><br><br>

    <hr>
    <h1></h1>
    <h3>Search for tickets</h3>
    <div>
        <form id="searchForm" method="post" name="searching">


            <input type="text" id="from_date" name="sqa" required placeholder="From Date">
            <input type="text" id="to_date" name="sqb" required placeholder="To Date">
            <input type="submit" name="search" id="search" value="Search">
    </div>
    </form>

    <br><br>

    <div class="row">
        <table id="tblbookings" border="1">
            <thead>
                <tr>
                    <th>Flight#</th>
                    <th>Flight name</th>
                    <th>Departure Location</th>
                    <th>Destination Location</th>
                </tr>
            </thead>
        </table>
    </div>


    <script>
        $(document).ready(function() {

            $('#from_date').datepicker();
            $('#to_date').datepicker();

            $('#searchForm').submit(function(event) {

                var formData = {
                    sqa: $('#from_date').val(),
                    sqb: $('#to_date').val()
                };
                $.ajax({
                    type: "POST",
                    url: "bookingsearch.php",
                    data: formData,
                    dataType: "json",
                    encode: true,

                }).done(function(data) {
                    var tbl = document.getElementById("tblbookings");
                    var rowCount = tbl.rows.length;

                    for (var i = 1; i < rowCount; i++) {
                        tbl.deleteRow(1);
                    }

                    for (var i = 0; i < data.length; i++) {
                        var fid = data[i]['flightcode'];
                        var fn = data[i]['flightname'];
                        var dl = data[i]['departure_location'];
                        var tl = data[i]['destination_location'];
                        //create a table row with four cells
                        //Insert new cell(s) with content at the end of a table row 
                        //https://www.w3schools.com/jsref/met_tablerow_insertcell.asp  
                        tr = tbl.insertRow(-1);
                        var tabCell = tr.insertCell(-1);

                        tabCell.innerHTML = fid; //flightID
                        var tabCell = tr.insertCell(-1);

                        tabCell.innerHTML = fn; //flight name  
                        var tabCell = tr.insertCell(-1);
                        tabCell.innerHTML = dl; //departure location       
                        var tabCell = tr.insertCell(-1);
                        tabCell.innerHTML = tl; //destination location         
                    }
                });
                event.preventDefault();
            })
        })
    </script>

    <?php

    mysqli_close($DBC);
    ?>
</body>

</html>