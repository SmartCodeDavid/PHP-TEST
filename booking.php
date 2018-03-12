<?php 
    include 'db_connector.php';
    include 'tourModel.php';  
    $tourid = isset($_GET['id']) ? $_GET['id'] : null;
    $conn = (new DatabaseConnector())->connectDatabase();
    $tourdate = array();
    $tourname;
    
    //get tourname
    if($tourid != null) {
        $sql = "SELECT name FROM tour where id = '$tourid'";
        $result = $conn->query($sql);
        if( $result->num_rows > 0){
            while($row = $result->fetch_assoc()) {
                $tourname = $row['name'];
            }
        }
        //grab the dates
        $sql = "SELECT date FROM tour_date WHERE tour_id = '$tourid' AND status=1";
        $result = $conn->query($sql);
        if( $result->num_rows > 0){
            while($row = $result->fetch_assoc()) {
                $tourdate[] = $row['date'];
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
    					<div class="col-md-12">
    						<div class="form-group">
    							<label class="col-md-3 control-label">Tour name</label>
    							<label class="col-md-3 control-label" style="text-align: left"><?php echo $tourname;?></label>
    						</div>
    						<div class="form-group">
    							<label class="col-md-3 control-label">Tour Date</label>
    							<div class="col-md-5">
            							<select class="form-control" name="tourdate">
            							<?php 
            							foreach ($tourdate as $td) {
            							?>
            								 <option value="<?php echo $td;?>"><?php echo $td;?></option>		   
            							<?php 
            							}
            							?>				
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
    						
    						<div id='divClone'>
    							
    						</div>
    						
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
				<div id='copyDiv' style="display: none">
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
                var elementClone = $("#copyDiv").find(".form-group").clone();
               // var elementwraper = document.createElement("div");
                var ele = $("<div id='divwrapper' class='copyDivGroup'>");
                ele.append(elementClone);
                $("#divClone").append(ele);
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
                        url: 'http://localhost:2323/bookingpost.php',
                        type: 'POST',
                        data: $("#formid").serialize(),
                        success: function(data){
                            if(data == "success" || data == "notable"){		//locates to tour page if no passenger or submmiting successfully 
                                //redirect to tour page
                            		window.location.href = "http://localhost:2323/tour.php";
                            }
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