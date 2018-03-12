<?php
    include 'db_connector.php';
    include 'tourModel.php';
    $tourId = $_GET['id'];
    $tourName;
    $itinerary;
    $status = array();
    $dates = array();
    $conn = (new DatabaseConnector())->connectDatabase();
    $statusAction = array();
    $classDisable = array();
    
    //get tourid 
    if($tourId != "") {
        $sql = "SELECT * FROM tour where id = $tourId";
        $result = $conn->query($sql);
        //get tour name and itinerary
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $tourName = $row['name'];
                $itinerary = $row['itinerary'];
            }
            
            //get dates and status in the tour 
            $sql = "SELECT date,status FROM tour_date where tour_id =$tourId";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $dates[] = $row["date"];
                if($row['status'] == 1){
                    $status[] = "Enable";
                    $statusAction[] = "Disable";
                    $classDisable[] = "btn btn-danger";
                }else{
                    $status[] = "Disable";
                    $statusAction[] = "Enable";
                    $classDisable[] = "btn btn-primary";
                }
            }
        } 
    }
?>


<html>
    
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Tour create Page">
        <link rel="stylesheet"    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!--        <script src="//code.jquery.com/jquery-1.9.1.js"></script>-->
        <title>Tour edit</title>  
        
        <link rel="stylesheet/less" type="text/css" href="styles.less" />
        <link rel="stylesheet" href="main.css">
        
        <!--   Style for date picker     -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.css">
       
        <!--   Date picker JS reference    -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
        
    </head>
    <body style="margin-bottom: 30px;">
        <div class="container">
            <form  id='formid' class="form-horizontal">
            		<input type='hidden' name="originaltourname" value="<?php echo $tourName;?>">
            		<input type="hidden" name='tourid' value='<?php echo $tourId; ?>'>
                <div class="row">
                    <div class="form-group">
                        <label class="control-label col-md-2">Tour name:</label>
                        <div class="col-md-6">
                        <input type="text" class="form-control" name="tourname" id="tourname" value="<?php echo $tourName;?>">
                        <span class="col-md-12" style="display: none; color:red">warning</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label class="control-label col-md-2">Itinerary:</label>
                        <div class="col-md-6">
                        <input type="textarea" class="form-control" name="itinerary" id="itinerary" value="<?php echo $itinerary; ?>">
                        <span class="col-md-12" style="display: none; color:red">warning</span>
                        </div>
                    </div>
                </div>
                 <div class="row">
                    <div class="form-group">
                        <label class="control-label col-md-3" style="font-size: 19px;">Tour available Dates:</label>
                        <div class="col-md-5">
                            <a class='btn btn-primary' id="addDate" style="float: right">Add date</a>
                        </div>
                    </div>
                </div>
                  <div class="row">
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-2">
                            <table class="table table-hover" id="dateTable">
                                <thead>
                                    <tr class="bg-success">
                                        <th class="col-md-5">Date</th>
                                        <th class="col-md-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="dateTbody">
                                <?php 
                                for($i = 0; $i < count($dates); $i++) {
                                ?>  
                                		<tr>
                                			<td>
                                				<input name="fixdate[]" style="display: none" value = "<?php echo $dates[$i]; ?>">
                                				<span ><?php echo $dates[$i]; ?></span>
                                			</td>
                                			<td>
                                				<input name="status[]" style="display: none" value= "<?php echo $status[$i]; ?>">
                                				<a href="javascript:void(0)"  class="<?php echo $classDisable[$i]; ?>" onclick='statusBtnPressDown(this)'><?php echo $statusAction[$i] ; ?></a>
                                			</td>
                                		</tr>
                                <?php   
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9 col-md-offset-2">
                        <div class="col-md-6">
                            <a href="http://localhost:2323/tour.php" class="btn btn-default">cancel</a>
                        </div>
                        <div class="col-md-6">
                            <a href="javascript:void(0)" onclick="submit(this)" id="submit" class="btn btn-primary">Submit</a>
                        </div>
                    </div>
                </div>
            </form>    
        </div>
        
        <!--  statusBtnPressDown -->
        <script>
        		function statusBtnPressDown(e) {
            		//change the status 
            		if(e.innerText == "Disable") {
                		e.innerText = "Enable";
                    	e.className = "btn btn-primary";
                    	e.previousElementSibling.value = "Disable";
                	}else{
                		e.innerText = "Disable";
                		e.className = "btn btn-danger";
                		e.previousElementSibling.value = "Enable";	
                }
            	}
        </script>
        
        <script>
        $(document).ready(function(){
                $("#addDate").click(function(){
                    var tr = document.createElement("tr");
                    var td1 = document.createElement("td");
                    var td2 = document.createElement("td");
                   // var span = document.createElement("span");
                    var span = "<span class='col-md-12' style='display: none; color:red'>warning</span>"
                    
                    td1.innerHTML = "<input type='text' name='date[]' id='datepicker' data-provide='datepicker' class='form-control datepickerGroup'>" + span;
                    td2.innerHTML = "<a class='btn btn-danger' id='removeBtn'>Remove</a>";
                    
                    tr.append(td1)
                    tr.append(td2)                    
                    $("#dateTbody").append(tr);
                });
            
                $("body").on("click", "#removeBtn", function(){
                    $(this).closest("tr").remove();
                });
                
              
        });
        </script>
        
        <!--   Datepicker config     -->
        <script>
            $(".datepickerGroup").datepicker.defaults.format = 'yyyy-mm-dd';
            $(".datepickerGroup").datepicker.defaults.autoclose = true;
        </script>
        
        <script>
            //Function will invoked when submit 
            function submit(e){
                //check input area if empty or not.
                var inputElmenets = document.getElementsByTagName("input");
                var flag = true;

                for(var i = 0; i < inputElmenets.length; i++) {
                    if(inputElmenets[i].id == "tourname" || inputElmenets[i].id == "itinerary" ||
                    		inputElmenets[i].name == "date[]"){
                			if(inputElmenets[i].value == "") {
                                //display warning sign to empty input
                                inputElmenets[i].nextElementSibling.style.display = "block";
                                flag = false;
                        	}else{
                                inputElmenets[i].nextElementSibling.style.display = "none";
                        }
                    }
                }
                
                /*
                * Update the information
                *input name:  tourname
                *			  itinerary
                	*			  fixdate[]
            		*		      status[]
        			*             date[]
        			*			  tourid
                */  
                if($('#tourname').value != "" && $("#itinerary") != null) {
                    $.ajax({
                        url: 'http://localhost:2323/toureditpost.php',
                        type: 'POST',
                        data: $("#formid").serialize(),
                        success: function(data){
                            var span = $('#tourname').siblings("span");
                            //tour name is already exist in the database
                            if(data == "exist"){
                                span.text("tour name is exist, please change other name");
                                span.css("display", "block");
                            }else if(data == "submitsuccess"){
                                //if other inputs are not empty then all inputs have been submit to database by backend server.
                                window.location.href = "http://localhost:2323/tour.php";
                            }else{
                                //span.css("display", "none");
                                span.text("warning");
                            }
                        },
                        error: function(data){
                        
                        }
                    });                    
                }
                return flag;
            }
        </script>
    </body>
</html>

 