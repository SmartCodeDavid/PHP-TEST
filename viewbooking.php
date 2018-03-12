<?php

    include 'db_connector.php';
    $conn = (new DatabaseConnector())->connectDatabase();

    $booking_id = array();
    $name = array();
    $tour_date = array();
    $count = array();
    
    //Get booking_id
    $sql = "select id as booking_id from t_booking";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $booking_id[] = $row['booking_id'];
        }
        //Get tour name
        foreach($booking_id as $id) {
            $sql = "select name from tour where id in (select tour_id from t_booking where id='$id')";
            $result = $conn->query($sql);
            if ($result->num_rows > 0){
                while($row = $result->fetch_assoc()) {
                    $name[] = $row['name'];
                }
            }
        }
        
        //get tour_date
        foreach($booking_id as $id) {
            $sql = "select tour_date from t_booking where id = '$id'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0){
                while($row = $result->fetch_assoc()) {
                    $tour_date[] = $row['tour_date'];
                }
            }
        }
        
        //get number of passenger
        foreach($booking_id as $id) {
            $sql = "SELECT COUNT(id) AS count FROM passenger WHERE id in (select passenger_id from t_booking_passenger where booking_id = '$id')";
            $result = $conn->query($sql);
            if ($result->num_rows > 0){
                while($row = $result->fetch_assoc()) {
                    $count[] = $row['count'];
                }
            }
        }
    }

?>


<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Home page - Tour">
        <link rel="stylesheet"    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
         
        <title>Home</title>

        <link rel="stylesheet" href="main.css">
    </head>
    
    <body>
    		<div class="container">
    			<div class="row">
    				<div class='col-md-2'><a href="http://localhost:2323/tour.php" class="btn btn-success">Tours</a></div>
    			</div>
    			<table class="table table-striped table-hove">
    				<thead>
    					<tr>
    						<th class="col-md-1">Booking Id</th>
    						<th class="col-md-3">Tour name</th>
    						<th class="col-md-3">Tour date</th>
    						<th class="col-md-3">Number of Passengers</th>
    						<th class="col-md-2">Actions</th>
    					</tr>
    				</thead>
    				<tbody >
    					<?php 
    					   for($i = 0; $i < count($booking_id); $i++) {     
        		         ?>
 						<tr>
        						<td><?php echo $booking_id[$i];?></td>
        						<td><?php echo $name[$i];?></td>
        						<td><?php echo $tour_date[$i];?></td>
        						<td><?php echo $count[$i];?></td>
        						<td><a class="btn btn-success" 
        						href="http://localhost:2323/bookingedit.php?tourname=<?php echo $name[$i];?>&booking_id=<?php echo $booking_id[$i]; ?>">Edit</a>
        						</td>
    						</tr>       		         		
        		         <?php  		
        					}
    					 ?>
    				</tbody>
    			</table>
    		</div>
    </body>
</html>