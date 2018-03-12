<?php 

    /*
    * Update the information that user edit
    *
    *input name:  tourname
    *			  itinerary
    	*			  fixdate[]
    *		      status[]
	*             date[]
	*			  tourid
    */  
    include 'db_connector.php';
    include 'tourModel.php';
    
    $tourname = isset($_POST['tourname']) ? $_POST['tourname'] : null;
    $itinerary = isset($_POST['itinerary']) ? $_POST['itinerary'] : null;
    $fixDate =  isset($_POST['fixdate']) ? $_POST['fixdate'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;
    $tourid = isset($_POST['tourid']) ? $_POST['tourid'] : null;
    $dates = isset($_POST['date']) ? $_POST['date'] : null;
    $originaltourname = isset($_POST['originaltourname']) ? $_POST['originaltourname'] : null;
    
    $conn = (new DatabaseConnector())->connectDatabase();
    
    //if tourname is not equal to orginal tourname then check it if valid or not
    if($tourname != "") {
        if($originaltourname != $tourname) {
            //check tourname firstly
            $sql = "SELECT name FROM tour where name = '$tourname'";
            $result = $conn->query($sql);
            if( $result->num_rows > 0){
                echo "exist";
            }else{
                updateInformation();
            }
        }else{
            updateInformation();
        }
    }
    
    
    
    function updateInformation() {
        global $tourname;
        global $itinerary;
        global $fixDate;
        global $status;
        global $dates;
        global $tourid;
        global $conn;
        
        //update 
        if($tourname != "" && $itinerary != "") { 
            //Insert new date if there have
            if($dates != null) {
                //do a double check in order to confirm there is not any empty "" from date inputs
                if(! in_array("", $dates)) {
                    //insert new dates to database
                   // $statusPublic = 1;
                    $stmt = $conn->prepare("insert into tour_date(tour_id,date,status) values(?,?,1)");
                    $stmt->bind_param("is",$tourid,$dat);
                    foreach($dates as $date){
                        $dat = $date;
                        $stmt->execute();
                    }  
                    //update the tourname and itinerary
                    updateTournameAndItinerary();
                    echo "submitsuccess";
                }
            } else if($dates == null) { //date insert could be null, update the tourname and itinerary directly
                updateTournameAndItinerary();
                echo "submitsuccess";
            }
            //close database
            $conn->close();
          }
     }
      
      
      function updateTournameAndItinerary(){
          global $tourname;
          global $itinerary;
          global $fixDate;
          global $status;
          global $dates;
          global $tourid;
          global $conn;
          
          //update tourname and itinerary
          $stmt = $conn->prepare("UPDATE tour SET name = ?, itinerary = ? WHERE id = ?");
          $stmt->bind_param("ssi",$tourname,$itinerary,$tourid);
          $stmt->execute();
          
          //update status for exist date
          $stmt = $conn->prepare("UPDATE tour_date SET status = ? WHERE date = ? AND tour_id = ?");
          for($i = 0; $i < count($fixDate); $i++) {
              if($status[$i] == "Enable"){
                  $statusPublic = 1;
              }else{
                  $statusPublic = 0;
              }
              $stmt->bind_param("isi",$statusPublic,$fixDate[$i],$tourid);
              $stmt->execute();
          }
      }
?>