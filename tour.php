<?php
    include 'db_connector.php';
    include 'tourModel.php';
    $conn = (new DatabaseConnector())->connectDatabase();
    $sql = "SELECT * FROM tour";
    $result = $conn->query($sql);
    
    $arrayTourInfo = array();
  
    if ($result->num_rows > 0) {
    // output data of each row
        
        while($row = $result->fetch_assoc()) {
            $tourModel = new TourInfo($row['id'], $row['name'], $row['itinerary'], 
                                     $row['status']);
            array_push($arrayTourInfo, $tourModel);
        }
    } 

    //Display the information about tour
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
                <a href="http://localhost:2323/tourcreate.php" class="btn btn-success">Create New Tour</a>
                <a href="http://localhost:2323/viewbooking.php" class="btn btn-primary">View Bookings</a>
            </div>
            <div class="row" style="margin-top: 10px;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="col-md-2">Tour Id</th>
                            <th class="col-md-8">Tour Name</th>
                            <th class="col-md-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(count($arrayTourInfo) > 0) {
                                for($i = 0; $i < count($arrayTourInfo); $i++){
                                    
                        ?>
                        <tr>
                            <td><?php echo $arrayTourInfo[$i]->getId(); ?></td>
                            <td><?php echo $arrayTourInfo[$i]->getName(); ?></td>
                            <td><a href="http://localhost:2323/touredit.php?id=<?php echo $arrayTourInfo[$i]->getId();?>"  class='btn btn-primary'>Edit</a>
                                <a href="http://localhost:2323/booking.php?id=<?php echo $arrayTourInfo[$i]->getId();?>" class='btn btn-success'>Booking</a>
                            </td>
                        </tr>   
                        
                        <?php
                                }
                            }
                        ?>
                    </tbody>
                    
                </table>
            </div>
        </div>
    </body>
</html>
