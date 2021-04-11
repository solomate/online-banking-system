<?php
	session_start();
?>
<!DOCTYPE html>
<html Lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="normalize.css">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="http://localhost/Bank/chatbot/style.css">
</head>
<body>
	<div class="about">
    	<div class="photo">
            <img >
			<img >
			<img >
			<img >
			<img >
    	</div>
    </div>
    <div class="navigation">
		<div class="logo">
			<img class="banklogo" src="images/logo1.png" width="80px">
		</div>
		<div class="bankname">
			<span class="master"><span class="m">M</span>aster <span class="b">B</span>ank </span>
		</div>
		<ul class="others">
			<form action="http://localhost/Bank/">
				<button>Home</button>
			</form>
		</ul>
    </div>
	<div class="container">
    <div class="background">
			<form action="system/login.sys.php" method="post">
			                <input type="text" name="mailuid" placeholder="Username/E-mail">
			                <input type="password" name="pwd" placeholder="password">
			                <select name="userType">
			                    <option value="Employee">Employee</option>
			                    <option value="Client">Client</option>
			                  </select>
			                <div class="log">
			                <button type="submit" name="login-submit">Login</button>
			                <a href="reset-password.php"><span class="forgot">Forgot Your Password?</span></a>
			              </div>
			              </form>
										<?php
							if(isset($_GET['newpwd'])){
								if($_GET['newpwd'] == 'passwordupdated'){
										echo "<p>Your password has been reset!</p>";
								}
							}
						?>
    	</div>
    </div>
    		<button class="open-button" onclick="openForm()">Need Help?</button>

<div class="chat-popup" id="myForm">
<form action="http://localhost/Bank/chatbot/logs.php" class="form-container" method="post">
	<h1>Need Help ?</h1>
	<label for="msg"><b>Message</b></label>
	<input type="text" name="msg" placeholder="Enter message.." required>

	<?php
		if(isset($_SESSION['ans'])){
			?>
			<textarea><?php echo$_SESSION['ans'];?></textarea>
			<?php
			session_unset();
			session_destroy();
			echo "<script>document.getElementById('myForm').style.display = 'block';</script>";
		}
	?>
	<button type="submit" class="btn">Send</button>
	<button type="button" class="btn cancel" onclick="closeForm()">Close</button>
</form>
</div>

<script>
function openForm() {
document.getElementById("myForm").style.display = "block";
}

function closeForm() {
document.getElementById("myForm").style.display = "none";
}
</script>
</body>
</html>
