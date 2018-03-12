<?php
    include 'db_connector.php';
    include 'tourModel.php';
    $conn = (new DatabaseConnector())->connectDatabase();

// get data from database and prepare for table creation.
//    `id` int NOT NULL AUTO_INCREMENT,
//    `name` varchar(256) NOT NULL,
//    `itinerary` text NOT NULL,
//    `status` tinyint NOT NULL,

    $sql = "select id, name from tour";
    $result = $conn->query($sql);
    $arrayTourInfo = array();
  
    if ($result->num_rows > 0) {
    // output data of each row
        while($row = $result->fetch_assoc()) {
            $tourModel = new TourInfo($row['id'], $name['name'], row['itinerary'], 
                                     $row['status']);
            array_push($arrayTourInfo, $tourModel);
        }
    } else {
            echo "0 results";
    }
    
?>