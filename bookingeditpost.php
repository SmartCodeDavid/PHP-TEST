<?php
    include 'db_connector.php';
    $tourdate = isset($_POST['tourdate']) ? $_POST['tourdate'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;
    $deletedPassengerId =  isset($_POST['passengerid']) ? $_POST['passengerid'] : null;
    $givenname = isset($_POST['givenname']) ? $_POST['givenname'] : null;
    $surname = isset($_POST['surname']) ? $_POST['surname'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $mobile = isset($_POST['mobile']) ? $_POST['mobile'] : null;
    $passport = isset($_POST['passport']) ? $_POST['passport'] : null;
    $dateofbirth = isset($_POST['dateofbirth']) ? $_POST['dateofbirth'] : null;
    $specialrequest = isset($_POST['specialrequest']) ? $_POST['specialrequest'] : null;
    $bookingid = isset($_POST['bookingid']) ? $_POST['bookingid'] : null;
    $pgclass = isset($_POST['pgclass']) ? $_POST['pgclass'] : null;
    $conn = (new DatabaseConnector())->connectDatabase();

    //$flag = true;
    
    //update tour date and  status
    $stat = ($status == "Cancel")? 0 : 1;
    $sql = "UPDATE t_booking SET tour_date = '$tourdate', status= $stat WHERE id = $bookingid";
    if($conn->query($sql) == TRUE){
       // echo "1";
    }
 
    //delete passengers -- TABLE t_booking_passenger -- TABLE passenger
    foreach($deletedPassengerId as $psgid) {
        if($psgid != "") {
            $sql = "delete from t_booking_passenger where passenger_id = $psgid";
            $result = $conn->query($sql);
            if($conn->query($sql) == TRUE) {
                $sql = "delete from passenger where passenger_id = $psgid";
                $result = $conn->query($sql);
            }
        }
    }
    
    //update passengers information if there were changes
    //check how many exist passengers need to be update
    for($i = 0; $i < count($givenname); $i++) {         //we can know how many passengers in there through counting givenname
        if($i < count($deletedPassengerId)) {
            
            if($deletedPassengerId[$i] == "") {         // "" means this passenger havent been deleted, we have to update it
                $sql = "UPDATE passenger SET given_name = '$givenname[$i]', surname= '$surname[$i]', email= '$email[$i]', mobile= '$mobile[$i]', passport= '$passport[$i]', birth_date= '$dateofbirth[$i]' WHERE id = '$pgclass[$i]'";
                
                if($conn->query($sql) == TRUE) {
                    //echo "1";
                    $sql = "UPDATE t_booking_passenger SET sepecial_request = '$specialrequest[$i]' WHERE id = '$pgclass[$i]' AND booking_id ='$bookingid' ";
                    //echo $sql;
                    if($conn->query($sql) == TRUE){
                        //echo "1";
                    }else{
                        echo "Error updating record: " . $conn->error;
                    }
                }else{
                   // echo $sql;
                    echo "Error updating record: " . $conn->error;
                }
            }
        }else{     
            //insert new passengers if there have
            $statusDefaul = 1;
            $stmt = $conn->prepare("insert into passenger (given_name,surname,email,mobile,passport,birth_date,status) values(?,?,?,?,?,?,?)");
            $stmt->bind_param("ssssssi",$givenname[$i],$surname[$i],$email[$i],$mobile[$i],$passport[$i],$dateofbirth[$i], $statusDefaul);
            echo $givenname[$i];
            echo $surname[$i];
            echo $email[$i];
            echo $mobile[$i];
            echo $passport[$i]; echo $dateofbirth[$i]; echo $statusDefaul;
            if($stmt->execute() == TRUE) {
                echo "true";
            }else{
                echo "2";
                echo "Error updating record: " . $conn->error;
            }
            $newID = mysqli_insert_id($conn);
            echo $newID;
            $stmt = $conn->prepare("insert into t_booking_passenger (booking_id, passenger_id, sepecial_request) values(?,?,?)");
            $stmt->bind_param("iis",$bookingid, $newID, $specialrequest[$i]);
            if($stmt->execute() == TRUE) {
                echo "true";
            }else{
                echo "Error updating record: " . $conn->error;
            }
        }
    }
    
?>
