<?php 
$conn =  mysqli_connect("localhost","root","","chatbat");

	//if(isset($_POST["msinput"])){
//if ($_SERVER["REQUEST_METHOD"] == "POST") {}
// INSERT A MSG-------------------
if(isset($_REQUEST["msinput"])){	
	$msg=$_REQUEST["msinput"];
    $cliant_name= $_REQUEST["cliant"];
    //$cliant_name= $_REQUEST["sender"];

    if($cliant_name!=''){
	    if(!mysqli_query($conn,"SELECT msgTo_".$cliant_name." FROM mysite_chatbot")){
	      $uptable="ALTER TABLE mysite_chatbot ADD msgTo_".$cliant_name." TEXT, msgFrom_".$cliant_name." TEXT";
          mysqli_query($conn,$uptable);
		    //mysqli_query($conn,"ALTER TABLE mysite_chatbot ADD msgTo_".$cliant_name." TEXT");
		   // mysqli_query($conn,"ALTER TABLE mysite_chatbot ADD msgFrom_".$cliant_name." TEXT");
	    }
		//send seen to last id msg

		$ceckcli=mysqli_query($conn,"SELECT cliants FROM mysite_chatbot WHERE cliants ='$cliant_name'");
		if(mysqli_num_rows($ceckcli) == 0){
			mysqli_query($conn,"INSERT INTO mysite_chatbot (cliants) VALUES ('$cliant_name')");
		}
		else{
			mysqli_query($conn,"DELETE FROM mysite_chatbot WHERE cliants='$cliant_name'");		
			mysqli_query($conn,"INSERT INTO mysite_chatbot (cliants) VALUES ('$cliant_name')");	
		}
	}

	if($msg!=''){
	    if($cliant_name=="admin"){
		    $inst=mysqli_query($conn,"INSERT INTO mysite_chatbot (msgTo_$cliant_name) VALUES ('$msg')");
		    if($inst){echo"y";}
		    else{echo"n";}
	    }
	    else{
	        $inst=mysqli_query($conn,"INSERT INTO mysite_chatbot (msgFrom_$cliant_name) Values ('$msg')");
	        if($inst){echo"y";}
		    else{echo"n";}
		}

		$checkpart=mysqli_query($conn,"SELECT cliants FROM mysite_chatbot WHERE cliants = '$cliant_name'");
		if(mysqli_num_rows($checkpart) == 0){
	     	mysqli_query($conn,"INSERT INTO mysite_chatbot (cliants) VALUES ('$cliant_name')");		
		}
    }
}
//---GET MESSAGES-----------------
if(isset($_REQUEST["getmsg"])){	
	$un=$_REQUEST["getmsg"];

	if($un=="admin"){
		$cli=$_REQUEST["cli"];
		$get=mysqli_query($conn,"SELECT * FROM mysite_chatbot WHERE msgTo_$cli !='' OR msgFrom_$cli != ''");
		if($get){
			while ($fet=mysqli_fetch_array($get)) {
				if($fet["msgTo_".$cli]!=""){
		echo "<div id='".$fet["id"]."' class='right'><p>".$fet["msgTo_".$cli]."</p></div><div class='c'/>";
				}
				if($fet["msgFrom_".$cli]!=""){
		echo "<div id='".$fet["id"]."' class='left'><p>".$cli."</p><p>".$fet["msgFrom_".$cli]."</p></div><div class='c'/>";
				}
		    }
		}
	}

	else{
        $get=mysqli_query($conn,"SELECT * FROM mysite_chatbot WHERE msgTo_$un !='' OR msgFrom_$un != ''");
        if($get){
			while ($fet=mysqli_fetch_array($get)) {
				if($fet["msgTo_".$un]!=""){
			echo "<div id='".$fet["id"]."' class='left'><p>".$un."</p><p>".$fet["msgTo_".$un]."</p></div><div class='c'/>";
				}
				if($fet["msgFrom_".$un]!=""){
			echo "<div id='".$fet["id"]."'class='right'><p>".$fet["msgFrom_".$un]."</p></div><div class='c'/>";
				}
			}
        } 
	}
}

///---APPEND NEW MESSAGE------
if(isset($_REQUEST["appmsg"])){	
	$un=$_REQUEST["appmsg"];
	$lid=$_REQUEST["lid"];

if($lid!=""){
	if($un =="admin"){
	$cli=$_REQUEST["cli"];
$get=mysqli_query($conn,"SELECT * FROM mysite_chatbot WHERE msgTo_$cli !='' OR msgFrom_$cli != '' AND id >".$lid);
	    if($get){
			while ($fet=mysqli_fetch_array($get)) {
				if($fet["msgTo_".$cli]!=""){
        echo "<div id='".$fet["id"]."' class='right'><p>".$fet["msgTo_".$cli]."</p></div><div class='c'/>";
				}
				if($fet["msgFrom_".$cli]!=""){
echo"<div id='".$fet["id"]."' class='left'><p>".$cli."</p><p>".$fet["msgFrom_".$cli]."</p></div><div class='c'/>";
				}
			}
		}
	}
    else{
$get=mysqli_query($conn,"SELECT * FROM mysite_chatbot WHERE msgTo_$un !='' OR msgFrom_$un != '' AND id >".$lid);
		if($get){
			while ($fet=mysqli_fetch_array($get)) {
				if($fet["msgTo_".$un]!=""){
echo"<div id='".$fet["id"]."' class='left'><p>".$un."</p><p>".$fet["msgTo_".$un]."</p></div><div class='c'/>";
				}
				if($fet["msgFrom_".$un]!=""){
echo"<div id='".$fet["id"]."'class='right'><p>".$fet["msgFrom_".$un]."</p></div><div class='c'/>";
				}
			}
		}
    }
}
}
///--------GET PART REQUEST-------------
if(isset($_REQUEST["getparts"])){
	$get=mysqli_query($conn,"SELECT id, cliants FROM mysite_chatbot WHERE cliants !='' ");

    while ($fet=mysqli_fetch_array($get)) {
    	$chek=mysqli_query($conn,"SELECT id, msgFrom_".$fet['cliants']."  FROM mysite_chatbot WHERE msgFrom_".$fet['cliants']." !='' AND message_seen = false ORDER BY id DESC");

    	if(mysqli_num_rows($chek) != 0){
    		echo "<div id='".$fet['id']."' class='darkcli'><i>".$fet['cliants']."</i> &nbsp <i>".mysqli_num_rows($chek)."</i> &nbsp <i>del</i></div>";
        }
        else{
            echo "<div id='".$fet['id']."'><i>".$fet['cliants']."</i> &nbsp <i>".mysqli_num_rows($chek)."</i><i>del</i></div>";        
        }
    }
}
///--------AppEND NEW PART -------------
if(isset($_REQUEST["appparts"])){
	$plid=$_REQUEST["appparts"];
	if($plid!=""){
		$get=mysqli_query($conn,"SELECT id, cliants FROM mysite_chatbot WHERE cliants !='' AND id > $plid");

	    while ($fet=mysqli_fetch_array($get)) {
	    	$chek=mysqli_query($conn,"SELECT msgFrom_".$fet['cliants']."  FROM mysite_chatbot WHERE msgFrom_".$fet['cliants']." !='' AND message_seen != true ORDER BY id");
	    	if(mysqli_num_rows($chek) != 0){
	    		echo "<div id='".$fet['id']."' class='darkcli'><i>".$fet['cliants']."</i> &nbsp <i>".mysqli_num_rows($chek)."</i> &nbsp <i>del</i></div>";
	        }
	        else{
	            echo "<div id='".$fet['id']."'><i>".$fet['cliants']."</i> &nbsp <i>".mysqli_num_rows($chek)."</i><i>del</i></div>";        
	        }
	    }
    }
}
///----DELETE PART REQUEST------
if(isset($_REQUEST["delpart"])){
	$del='ALTER TABLE mysite_chatbot DROP msgFrom_'.$_REQUEST["delpart"].', DROP msgTo_'.$_REQUEST["delpart"];
	$deltable=mysqli_query($conn,$del);
	$delpart=mysqli_query($conn,'DELETE FROM mysite_chatbot WHERE cliants="'.$_REQUEST["delpart"].'"');

	if($delpart AND $deltable){
		echo "cliant deleted";
	}
}

 ?>