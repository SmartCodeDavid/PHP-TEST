<?php
    include 'db_connector.php';
    include 'tourModel.php';    
    
    $tourname = isset($_POST['tourname']) ? $_POST['tourname'] : null;
    $itinerary = isset($_POST['itinerary']) ? $_POST['itinerary'] : null;
    $dates = isset($_POST['date']) ? $_POST['date'] : null;
    $conn = (new DatabaseConnector())->connectDatabase();

    if($tourname != "") {
        //check tourname firstly
        $sql = "SELECT name FROM tour where name = '$tourname'";
        $result = $conn->query($sql);
        if( $result->num_rows > 0){
            echo "exist";
        }else{ 
            insertData();
        }
    }
    
    function insertData(){
        global $itinerary;
        global $tourname;
        global $dates;
        global $conn;
        if($tourname != "" && $itinerary != "") {
            //Users pick date
            if(! $dates){
                echo "dateHaventPick";
            }else{
                //do a double check in order to confirm there is not any empty "" from date inputs
                if(! in_array("", $dates)) {
                    //insert data to database
                    if ($stmt = $conn->prepare("insert into `tour`(name,itinerary,status) values(?,?,?)")) {
                        
                        $stmt->bind_param("ssi",$tourname,$itinerary,$status);
                        $status = 1;
                        
                        $stmt->execute();
                        
                        //insert dates relative to jour
                        $insertId = mysqli_insert_id($conn);
                        
                        $stmt = $conn->prepare("insert into `tour_date`(tour_id,date,status) values(?,?,?)");
                        $stmt->bind_param("isi",$insertId,$dat,$status);
                        
                        foreach($dates as $date){
                            $dat = $date;
                            $stmt->execute();
                        }
                        //close database
                        $conn->close();
                        echo "submitsuccess";
                    }
                }
            }
        }
    }
    
?>