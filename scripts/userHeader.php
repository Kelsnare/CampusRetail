<style>
	@font-face{
		font-family: "logobloqo2";
		src: url('css/fonts/Logobloqo2.ttf');
	}
	#logoText{
		position:relative;
		top:10px;
		font-family:'logobloqo2';
		font-size: 15pt;
	}
	#headRight{
		float: right;
		position: relative;
		right: 20px;
		top: 7px;
		
	}
	#logout,#logged_user{
		padding:3px;
		background-color: #000000;
		color:white;
		border-radius: 3px;
		transition: box-shadow, 0.4s;
		position: relative;
		right: 20px;
		top: 4px;
	}

	#logout:hover{
		cursor:pointer;
		box-shadow: 0 0 3px rgba(255,255,255,0.8);
	}
</style>
<span id='logoText'>Online Campus Retail</span>
<div id='headRight'>
	<span id='logged_user'><?php $logged_user = explode('@', $log_username); echo $logged_user[0]; ?></span>
	<span id='logout' onclick="window.location = 'scripts/logout.php';">Logout</span>
</div>
