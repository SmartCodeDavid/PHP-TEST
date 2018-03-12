<?php
    include 'db_connector.php';
    include 'Passenger.php';
    
    $tourname = isset($_GET['tourname']) ? $_GET['tourname'] : null;
    $booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : null;
    $selectedTourdate;
    $selectedStatus;
    $otherOption;

    
    $conn = (new DatabaseConnector())->connectDatabase();
    $tourdate = array();
    $passengers = array();
    $specialRequest = array();
    $array = array();
        
        //selectedtour date
        $sql = "select tour_date from t_booking where id = $booking_id";
        $result = $conn->query($sql);
        if( $result->num_rows > 0){
            while($row = $result->fetch_assoc()) {
                $selectedTourdate = $row['tour_date'];
            }
        }
        
        //get the selectedStatus
        $sql = "select status from t_booking where id = $booking_id";
        $result = $conn->query($sql);
        if( $result->num_rows > 0){
            while($row = $result->fetch_assoc()) {
                if($row['status'] == 1){            //Confirmed
                    $selectedStatus = "Confirmed";
                    $otherOption = "Cancelled";
                }else{                              //Cancelled
                    $selectedStatus = "Cancelled";
                    $otherOption = "Confirmed";
                }
            }
        }

        //grab the tour date
        $sql = "SELECT date FROM tour_date WHERE tour_id in (select tour_id from t_booking where id= $booking_id ) AND status=1";
        $result = $conn->query($sql);
        if( $result->num_rows > 0){
            while($row = $result->fetch_assoc()) {
                $tourdate[] = $row['date'];
            }
        }
        
        //get passenger
        $sql = "select * from passenger where id in (select passenger_id from t_booking_passenger where booking_id = $booking_id)";
        $result = $conn->query($sql);
        if( $result->num_rows > 0){
            while($row = $result->fetch_assoc()) {
                $pasgen = new Passenger($row['id'], $row['given_name'], $row['surname'],
                    $row['email'], $row['mobile'], $row['mobile'], $row['birth_date'], $row['status']);
                $passengers[] = $pasgen;
            }
        }
        
        //sepecial_request
        $sql = "select sepecial_request as specialreq from t_booking_passenger where booking_id = '$booking_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            //$i = 0;
            while($row = $result->fetch_assoc()) {
                $specialRequest[] = $row['specialreq'];
                //echo $row["specialreq"];
            }
        }else{
            //echo "eeee";
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
         
        <!--   Style for date picker     -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.css">
       
        <!--   Date picker JS reference    -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
         
        <title>Home</title>

        <link rel="stylesheet" href="main.css">
    </head>
    
    
	<body>
		<div class="container">
			<div class="row">
				<form class="form-horizontal" id="formid">
				<input type='hidden' name='tourid' value="<?php echo $tourid; ?>">
				<input type='hidden' name='bookingid' value="<?php echo $booking_id; ?>">
				
    					<div class="col-md-12">
    						<div class="form-group">
    							<label class="col-md-3 control-label">Tour name</label>
    							<label class="col-md-3 control-label" style="text-align: left"><?php echo $tourname;?></label>
    						</div>
    						<div class="form-group">
    							<label class="col-md-3 control-label">Tour Date</label>
    							<div class="col-md-5">
            							<select class="form-control" name="tourdate">
            								<option value="<?php echo $selectedTourdate;?>" selected="selected"><?php echo $selectedTourdate;?></option>
                							<?php 
                							foreach ($tourdate as $td) {
                							    if($selectedTourdate != $td) {
                							?>
                								 <option value="<?php echo $td;?>"><?php echo $td;?></option>		   
                							<?php 
                							    }
                							}
                							?>				
            							</select>	
    							</div>
    						</div>
    						<div class="form-group">
    							<div class="col-md-5 col-md-offset-3">
    							<select class="form-control" name="status">
    								<option value='<?php echo $selectedStatus;?>' selected="selected"><?php echo $selectedStatus;?></option>
    								<option value='<?php echo $otherOption;?>'>
    								<?php echo $otherOption;?></option>
    							</select>
    							</div>
    						</div>
    						<div class="form-group">
    							<div class="col-md-6 col-md-offset-2">
    								<label class="col-md-3 control-label">Passenger</label>
    								<div class="col-md-3" style="float: right">
    									<a href="javascript:void(0)" class="btn btn-success" onclick='addPassengerBtnPressDown(this)' style="float:right">Add passenger</a>
    								</div>
    							</div>
    						</div>
    						
    						<!-- start -div clone -->
    						<div id='divClone'>
        						<?php  
        						for($i = 0; $i < count($passengers); $i++) {
        						//foreach($passengers as $pag){
        						    
        						?>
        						<input class="passenDelete" type="hidden" name="passengerid[]" value="">
                				<div id='divwrapper' class="copyDivGroup" style="display: block">
                					<div class="form-group" style="backgroup-color: grey">
                						<label class="col-md-3 control-label" >Given name</label>
                						<div class="col-md-3">
                							<input type='text' class="form-control" name="givenname[]" value='<?php echo $passengers[$i]->getGiven_name() ;?>'></input>
                							<span style="color:red; display:none">warning</span>
                						</div>
                						<label class="control-label col-md-1" >Surname</label>
                						<div class="col-md-3">
                							<input type='text' class="form-control" name=surname[] value='<?php echo $passengers[$i]->getSurname();?>'></input>
                							<span style='color:red; display:none'>warning</span>
                						</div>
                					</div>
                				          				
                					<div class="form-group" style="backgroup-color: grey">
                						<label class="col-md-3 control-label">Email</label>
                						<div class="col-md-3">
                							<input type='text' class="form-control" name="email[]" value='<?php echo $passengers[$i]->getEmail();?>'></input>
                							<span style='color:red; display:none'>warning</span>
                						</div>
                						<label class="control-label col-md-1" >Mobile</label>
                						<div class="col-md-3">
                							<input type='text' class="form-control" name="mobile[]" value='<?php echo $passengers[$i]->getMobile();?>'></input>
                							<span style='color:red; display:none'>warning</span>
                						</div>
                					</div>
                				
                					<div class="form-group" style="backgroup-color: grey">
                						<label class="col-md-3 control-label">Passport</label>
                						<div class="col-md-3">
                							<input type='text' class="form-control" name="passport[]" value='<?php echo $passengers[$i]->getPassport();?>'></input>
                							<span style='color:red; display:none'>warning</span>
                						</div>
                						<label class="control-label col-md-1" >dateofbirth</label>
                						<div class="col-md-3">
                							<input type='text' class="form-control datepickerGroup" name="dateofbirth[]" id='datepicker' data-provide='datepicker'
                								value='<?php echo $passengers[$i]->getBirth_date();?>'></input>
                							<span style='color:red; display:none'>warning</span>
                						</div>
                					</div>
                				
                					<div class="form-group" style="backgroup-color: grey">
                						<label class="col-md-3 control-label">Special Request</label>
                						<div class="col-md-5">
                							<input type='text' class="form-control" name="specialrequest[]" value='<?php echo $specialRequest[$i]; ?>'></input>
                							<span style='color:red; display:none'>warning</span>
                						</div>
                						<div class="col-md-2">
                							<input type="hidden" id="pgid" name='pgclass[]' value="<?php echo $passengers[$i]->getId();?>">
                							<a class="btn btn-success" onclick='removeExistPassenger(this)'>Remove</a>
                						</div>
                					</div>
                				</div>   
                				<?php } ?> 							
    						</div>
    						
    						<!-- End ---- div clone -->
    						
    						<div class="form-group">
        						<div class="col-md-4 col-md-offset-3">
        							<a href="http://localhost:2323/tour.php" class="btn btn-default">cancel</a>
        						</div>
        						<div class="col-md-4">
        							<a href="javascript:void(0)" onclick='return submitBtnPressDown(this)' class="btn btn-primary">submit</a>
        						</div>
        					</div>
    					</div>
				</form>
				
				<!-- Template - div -->
				<div id='copyDivNew' style="display: none">
					<div class="form-group" style="backgroup-color: grey">
						<label class="col-md-3 control-label" >Given name</label>
						<div class="col-md-3">
							<input type='text' class="form-control" name="givenname[]"></input>
							<span style="color:red; display:none">warning</span>
						</div>
						<label class="control-label col-md-1" >Surname</label>
						<div class="col-md-3">
							<input type='text' class="form-control" name=surname[]></input>
							<span style='color:red; display:none'>warning</span>
						</div>
					</div>
				
				
					<div class="form-group" style="backgroup-color: grey">
						<label class="col-md-3 control-label">Email</label>
						<div class="col-md-3">
							<input type='text' class="form-control" name="email[]"></input>
							<span style='color:red; display:none'>warning</span>
						</div>
						<label class="control-label col-md-1" >Mobile</label>
						<div class="col-md-3">
							<input type='text' class="form-control" name="mobile[]"></input>
							<span style='color:red; display:none'>warning</span>
						</div>
					</div>
				
					<div class="form-group" style="backgroup-color: grey">
						<label class="col-md-3 control-label">Passport</label>
						<div class="col-md-3">
							<input type='text' class="form-control" name="passport[]"></input>
							<span style='color:red; display:none'>warning</span>
						</div>
						<label class="control-label col-md-1" >dateofbirth</label>
						<div class="col-md-3">
							<input type='text' class="form-control datepickerGroup" name="dateofbirth[]" id='datepicker' data-provide='datepicker'></input>
							<span style='color:red; display:none'>warning</span>
						</div>
					</div>
				
					<div class="form-group" style="backgroup-color: grey">
						<label class="col-md-3 control-label">Special Request</label>
						<div class="col-md-5">
							<input type='text' class="form-control" name="specialrequest[]"></input>
							<span style='color:red; display:none'>warning</span>
						</div>
						<div class="col-md-2">
							<a class="btn btn-success" onclick='removeBtnPressDown(this)'>Remove</a>
						</div>
					</div>
				</div>				
			</div>
		</div>
		
		<script>
			function addPassengerBtnPressDown(e){
                var elementClone = $("#copyDivNew").find(".form-group").clone();
               // var elementwraper = document.createElement("div");
                var ele = $("<div id='divwrapper' class='copyDivGroup'>");
                ele.append(elementClone);
                $("#divClone").append(ele);
			}

			function removeExistPassenger(e){
				var id = e.previousElementSibling.value;
				var input = e.closest("div#divwrapper").previousElementSibling;
				input.value = id;
				e.closest("div.copyDivGroup").remove();
			}
			
			function removeBtnPressDown(e) {
				e.closest("div#divwrapper").remove();
			}

			function submitBtnPressDown(e) {
				var flag = true;
				//$divGroup = $(".copyDivGroup");
				$('div.copyDivGroup input').each(function(){
					if($(this).val() == "") {
						$(this).next().text("warning");
						$(this).next().css("display", "block");
						flag = false;
					}else{
						$(this).next().css("display", "none");
						//validate email formate
						if($(this).attr("name") == "email[]"){
							var email = $(this).val();
							var reg = /\w+[@]{1}\w+[.]\w+/;
							   if(reg.test(email)){
								   //set defatul
								  	$(this).next().text("warning");
									$(this).next().css("display", "none");
							   }else{
								   //set text with invalidate email
									$(this).next().css("display", "block");
									$(this).next().text("invalidate email");
									flag = false;
							   }							
						}
					}
				});

				//check and submit if all information are ready
				if(flag) {
					$.ajax({
                        url: 'http://localhost:2323/bookingeditpost.php',
                        type: 'POST',
                        data: $("#formid").serialize(),
                        success: function(data){
                            // if(data == "success" || data == "notable"){		//locates to tour page if no passenger or submmiting successfully 
                                 //redirect to tour page
                            		window.location.href = "http://localhost:2323/tour.php";
                            // }
                        },
                        error: function(data){		
                        }
                    });             					
				}
			}

		</script>
		
        <!--   Datepicker config     -->
        <script>
            $(".datepickerGroup").datepicker.defaults.format = 'yyyy-mm-dd';
            $(".datepickerGroup").datepicker.defaults.autoclose = true;
        </script>		
		
    </body>
</html>