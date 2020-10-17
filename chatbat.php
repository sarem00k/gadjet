<?php 
$conn =  mysqli_connect("localhost","root","","chatbat");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_name="";

if(isset($_GET["admin"])){
	echo"

	";
	//admin login form---cpanel
	//on log delete existed cliant cookie and set admin cookie
	//	$cliant_name=$_COOKIE["visitor"];															

	/*

	    $table_check=mysqli_query($conn,"SELECT * FROM $_COOKIE['visitor']");
	    if(!$table_check){
			$sql = "CREATE TABLE $_COOKIE['visitor'] (
		        timestamp TIMESTAMP on update CURRENT_TIMESTAMP NULL,	
		        pm_sound BOOLEAN NULL DEFAULT TRUE,
		        theme TEXT NULL,
				parts TEXT NULL,
		    	msgtype TEXT NULL,	
		        message_seen BOOLEAN
		    )";	
	    }

	*/
}
 
//admin lofin form isset and then setcookie 

//--- check visitor is a cliant or the website admin

if(!$_COOKIE["visitor"]){
	$user_name="cliant_".time();
	setcookie("visitor",$user_name,time() + (86400 * 30), "/");	
}
else{
	$user_name=$_COOKIE["visitor"];	
	setcookie("visitor",$user_name,time() + (86400 * 30), "/");	// renew it and reset time						
}

 ?>

<html>
<style type="text/css">
	*{box-sizing: border-box;}
	.c{clear: both;}

	.chatbox{width:20%;height:40%;position: fixed;top:50%;border: 1px solid pink;}
	.chatbox .topchatbox{width:100%;height:20%;background-color:pink;text-align:center;padding:15px;
		                display:flex;align-items:center;justify-content:space-between;}
	.chatbox .topchatbox button{cursor:default; margin-left:;}

	#partist{display:none;width:100%;height:60%; background-color:gray;position: absolute;z-index: 10;}

	.chatbox section{width:100%;height:70%;background-color: white;overflow-y:scroll;overflow-x: hidden;}
		.left{float:left;width: 60%; padding: 10px;}
	    .left p:first-child {color:#d864bf;font-weight: bold;text-align: left;}
	    .left p:last-child {color:white; background-color:#d864bf;text-align: left;padding:5px;}

	    .right{float:right;width: 60%; padding: 10px;}
	    .right p:last-child{color:#64cdd8;font-weight: bold;text-align: right;}
	    .right p:last-child{color:white; background-color:#64cdd8;text-align:right;padding:5px;}

	.chatbox footer{width:100%;height:20%;background-color: pink;padding:15px 20px;}

	#partist{display: none;overflow-y: scroll;}
	.darkcli{color:black;font-weight: bold}
	#partist div{color:gry;display: ;}
	#partist div i:first-child{}
	#partist div i:nth-child(2){}
	#partist div i:last-child{color:white;cursor:default;background-color: red;display: flex;align-items: center;justify-content:center;}

	.sign{font-family:Wingdings;color:orange;font-size:22px;}

	#cp{background-color:lightgreen;padding:10px;width:100px;text-align: center;}
	#cpForm{display:none;background-color:lightblue;padding:50px ;width:350px;margin:auto;}
	#cpForm input[type="text"]{margin-top:10px;padding:10px 50px;}
	#cpForm input[type="password"]{margin-top:20px;padding:10px 50px;}
	#cpForm input[type="submit"]{margin-top:20px;padding:10px 50px;width:100px;}
</style>


<div id="cp">admin login</div>
<form id="cpForm" method="POST" action="">
	<input type="text" placeholder="admin user"><br>
	<input type="password" placeholder="password"><br>
	<input type="submit" value="login">
</form>

<div class="chatbox">
	<div class="topchatbox">
        <script>function tglpartist(){$('#partist').slideToggle('slow');}</script>
<?php 
if($user_name!="admin"){	
echo"<button onclick='tglpartist()''>messages &nbsp<i></i></button>";
}
?>
	<i style="font-size: 14px;"><?php echo $user_name; ?></i>
	 <button>reduce</button>
	 </div>

	 <div id="partist"></div>

    <section></section>
    <footer>
    	<form id="msgform" method="POST" action="ret.php">
	    	<input id="msginput" type="text" name="msinput" autocomplete="none">
	    	<input type="submit" value="send">
    	</form>
    </footer>
</div>




<script type="text/javascript">
	var uname="<?php echo $user_name; ?>"
	var cli;//it gets its value only from partist and only if uname is admin
	//alert(cli);
//------Admin LOGIN------
$("#cp").click(function(){
	$("#cpForm").toggle();
});

$("#cpForm").on("submit",function(e){
	e.preventDefault();
	var un="admin";
	var up="nimda";

	var unf=$("#cpForm :text").val();
	var upf=$("#cpForm :password").val();
	if(un==unf && up==upf){
		uname=un;
		alert(uname);

		//sessionStorage.setItem("user_name", un);
		var date = new Date();
        document.cookie = "visitor=admin; expires="+date.setDate(date.getDate() + 1)+"; path=/";
        alet(document.cookie);
        $("#cpForm").hide();
    }
});

//-----reduce chat button---------
	$(".topchatbox button:eq(1)").click(function(){
		$(".chatbox").css({
			"display":"none",
		})
	});

///----GET messages function()-------	
var so;
var oh;
$(".chatbox section:eq(0)").scroll(function(){
	var k =$(".chatbox section:first").prop("scrollHeight");
	var j =$(".chatbox section:first").outerHeight();  
	if($(this).scrollTop()==Math.ceil(k-j)){
		so=$(".chatbox section:first").prop("scrollHeight");
		oh =$(".chatbox section:first").outerHeight();  
	}
});
function getmsg(){
	var xmlhttp= new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
		    $(".chatbox section:eq(0)").html(this.responseText);   
		    //let sw=document.querySelector(".chatbox section:first").scrollHeight;
		    let sw=$(".chatbox section:first").prop("scrollHeight");
 			$(".chatbox section:eq(0)").scrollTop(sw);	  

 			so=$(".chatbox section:first").prop("scrollHeight");
	        oh=$(".chatbox section:first").outerHeight();    
        }
    }
    if(uname=="admin"){
    	xmlhttp.open("POST", "ret.php?getmsg=admin&cli="+cli, true);
    }
    else{
    	xmlhttp.open("POST", "ret.php?getmsg="+uname, true);
    }
    xmlhttp.send();		
}
getmsg();
//---APPEND NEW MESSAGE----------
function appmsg(){
	let lid=""
	if($(".chatbox section:first div").children().length > 0){
	    lid=$(".chatbox section:first div:last").attr("id");
	}
	var xmlhttp= new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        	if(this.responseText!=""){
				$(".chatbox section:eq(0)").append(this.responseText);

//$(".chatbox section:eq(0)").append("<div class='right'><p>this.responseText</p></div><div class='c'/>");

	        	var sw=$(".chatbox section:first").prop("scrollHeight");
	        	var cs=$(".chatbox section:first").scrollTop();


				if(cs==Math.ceil(so-oh) || Math.abs(cs-Math.ceil(so-oh)) < 30){
	 				$(".chatbox section:eq(0)").scrollTop(sw);	
	 				so=$(".chatbox section:first").prop("scrollHeight");
	        	    oh=$(".chatbox section:first").outerHeight();

	        	}
	        	else{
			    	alert("new message");
	        	}
            }
        }
    }
    if(uname=="admin"){
        xmlhttp.open("POST", "ret.php?appmsg="+uname+"&lid="+lid+"&cli="+cli, true);
    }
    else{
        xmlhttp.open("POST", "ret.php?appmsg="+uname+"&lid="+lid, true);
    }
    xmlhttp.send();		
}

setInterval(appmsg,5000);

///------INSERT MESSAGE function()---------
//document.getElementById("msgform").onsubmit=function(e){
$("#msgform").on("submit", function(e){
    e.preventDefault();

	$(".chatbox section:first div:last").prev().find("i").remove();
	$(".chatbox section:first").append("<div class='right'><p>"+$("#msgform :text").val()+"</p> &nbsp <i class='sign' >sending...</i></div> <div class='c'/>");
    let sw=document.querySelector(".chatbox section").scrollHeight;
 	$(".chatbox section:eq(0)").scrollTop(sw);	

	let dataa=$(this).serialize();
	let done = new FormData(this);

    done.append("cliant", uname);
    //done.append("cliant", sender);

	$.ajax({
		type:"POST",
		url:$(this).prop("action"),
		//data:dataa,
		//data:"msinput=imad&sender=walid",	
		data: done,	
		contentType: false,
	    processData: false,
		success:function(response){
			//alert(response);
			if(response=="y"){
				$(".sign").text("ü");

	            if(uname=="admin"){ 
	             //---check if cliant is already a part--if no add it to partist
	            // or we can check if its the first mesg in msg section--its better
					let chk;
					$("#partist").children().each(function(){
						if($(this).find("i").first().text()==uname){
							chk="ys";
						}
					});
					if(chk!="ys"){
						let idi=$('#partist div:first').attr("id");
						$("#partist").append("<div id='"+(idi+1)+"'><i>"+uname+"</i> &nbsp <i></i><i>del</i></div>");
					}
				}
		    }
			else if(response=="n"){
				$(".sign").text("failed");
			}
		}
	});
	$("#msginput").val(" ");
});

///----ADMIN OPTIONS SHOWS------
if(uname!="admin"){
	//***---GETparts messages function()
	function getparts(){
		var xmlhttp= new XMLHttpRequest();
	    xmlhttp.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {
			    $("#partist").html(this.responseText);
			    var np=$("#partist .darkcli").length;
			    $(".topchatbox button:first i:first").text(np);
	        }
	    }
		xmlhttp.open("POST", "ret.php?getparts=", true);
	    xmlhttp.send();		
	}
	getparts();	

	//***---Append part NEW function()
	function appparts(){
		let plid="";
		if($("#partist").children().length > 0){
			plid=$("#partist div:first").attr("id"); 
        }
		var xmlhttp= new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
				$("#partist").prepend(this.responseText);
				var np=$("#partist .darkcli").length;
			    $(".topchatbox button:first i:first").text(np);
		    }
		}
	    xmlhttp.open("POST", "ret.php?appparts="+plid, true);
		xmlhttp.send();	
    }
	setInterval(appparts,1000);	

	//----partist click-----
	$("#partist").on("click",function(e){ 
		if($(e.target).attr("tagName")=="div"){
		cli=$(e.target).find("i").first().text();
		}
		else{
		cli=$(e.target).parent().find("i").first().text();
		}
		//alert(cli);
		getmsg();
		$("#partist").hide();
		// after messages loaded remove dark class from the part
	});	

	///-----DELETE a PART  ------------------------------
	$("#partist").on("click",function(e){
		alert($(this).parent());
		if($(e.target).text()=="del"){
			alert('yes');
			$.ajax({
				type:"POST",
				url:"ret.php",
				data:"delpart="+$(e.target).parent().find("i").first().text(),	
				success:function(response){
					if(response=="cliant deleted"){
						$(e.target).parent().remove();
					}
				}
			});
	    }
	});	
}
</script>
</html>

												
