<?php
    include 'db_connector.php';
    include 'tourModel.php';   
    
    $tourid = isset($_POST['tourid']) ? $_POST['tourid'] : null;
    $tourdate = isset($_POST['tourdate']) ? $_POST['tourdate'] : null;
    $givenname = isset($_POST['givenname']) ? $_POST['givenname'] : null;
    $surname = isset($_POST['surname']) ? $_POST['surname'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $mobile = isset($_POST['mobile']) ? $_POST['mobile'] : null;
    $passport = isset($_POST['passport']) ? $_POST['passport'] : null;
    $dateofbirth = isset($_POST['dateofbirth']) ? $_POST['dateofbirth'] : null;
    $specialrequest = isset($_POST['specialrequest']) ? $_POST['specialrequest'] : null;
    $conn = (new DatabaseConnector())->connectDatabase();
    
    $bookingid;
    $passengerid;
    $successSubmit = true;
    
    //submit and insert passenger info to database
    if($givenname != null){
        //t_booking with “Submitted” 0, “Confirmed” 1 “Cancelled” 2;
        $sql = "insert t_booking(tour_id, tour_date, status) values ('$tourid', '$tourdate',1)";
        $conn->query($sql);
        $bookingid = mysqli_insert_id($conn);
        
        //passenger with “Enabled” 1 and “Disabled” 0
        for($i = 0; $i < count($givenname); $i++) {
            $sql = "insert passenger(given_name, surname, email, mobile, passport, birth_date, status) values 
                            ('$givenname[$i]', '$surname[$i]','$email[$i]', '$mobile[$i]', '$passport[$i]', 
                                                    '$dateofbirth[$i]', 1)";
            
            $conn->query($sql);
            $passengerid = mysqli_insert_id($conn);
            
            if(mysqli_affected_rows($conn) > 0) {
                //t_booking_passenger
                $sql = "insert t_booking_passenger(booking_id, passenger_id, sepecial_request) values('$bookingid', '$passengerid', '$specialrequest[$i]')";
                $conn->query($sql);
                if(mysqli_affected_rows($conn) < 0) {
                    $successSubmit = false;
                }
            }else {
                $successSubmit = false;
            }
        }
        
        if($successSubmit) {
            echo "success";
        }else{
            echo "fail";
        }
    }else{
        echo "notable";
    }
?>