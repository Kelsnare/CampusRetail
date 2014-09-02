
function fill_content(){
	$.ajax({
		type: "GET",
		url: "scripts/getusers.php",
		//data: {},
		async: false,
		success: function(response){
			if(response != "ERROR")
				$("#userList").html(response);
		}
	});
}

function locate(){
	var left = ($(window).width() - $("#signin").width())/2;
	var top = ($(window).height() - $("#signin").height())/2;

	$("#signin").css({
		"top" : top,
		"left" : left
	});

	var width = $("#signInWebmail").width()-3;
	$("#signInPass > input").css({"width": width + "px"});
}

function login(){
	var webmail = $("#signInWebmail > input").val()+"@iitg.ernet.in";
	var pass = $("#signInPass > input").val();
	var obj = {
		'u': webmail,
		'p' : pass
	}
	$.ajax({
		type: "POST",
		url: "scripts/login.php",
		data: obj,
		async: false,
		success: function(response){
			if(response.indexOf("success") > -1){
				var user = obj.u;
				user = user.toLowerCase();
				window.location = "user.php";
			}else{
				$("#loginStatus").html("Login Failed !!")
			}

		}
	});
}

