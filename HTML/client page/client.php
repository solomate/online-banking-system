<?php
	require_once "../../PHP/classes/Client.php";
	require_once "../../PHP/classes/Address.php";
	//session_start();
	//$id = $_SESSION['userId'];
	$id = 929859741;
	$client = new Client($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>client page</title>
	<link rel="stylesheet" type="text/css" href="normalize.css">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="header">
    	<div class="logo">
    		<img src="images/logo.png" width="60px">
    	</div>
    	<div class="bank_name">
    		<span class="master"><span class="m">M</span>aster <span class="b">B</span>ank </span>
    	</div>
    	<div class="logout">
    		<button>Log Out</button>
    	</div>
    </div>
    <div class="person">
    	<div class="container">
    		<div class="photo">
    			<img id="personPic" src="images/person2.png" width="100px;">
    			<h2 id="cName">medo gamal </h2>
    			<h3 id="accountNumber"></h3>
    		</div>
    		<div class="person_information">
    			<h3>personal information</h3>
    			<ul class="hed">
    				<li>Name</li>
    				<li>Age</li>
    				<li>National Id</li>
    			</ul>
    			<ul class="myinfo">
    				<li id="name" class="last"></li>
    				<li id="age" class="last"></li>
    				<li id="nationalID"></li>
    			</ul>
    		</div>
    		<div class="person_information" style="margin-top:3px;">
    			<h3>Contact Information</h3>
    			<ul class="hed">
    				<li>Email</li>
    				<li>Phone </li>
    				<li>Address</li>
    			</ul>
    			<ul class="myinfo">
    				<li id="email" class="last"></li>
    				<li id="phoneNumber" class="last"></li>
    				<li id="address"></li>
    			</ul>
			</div>
			<?php
				$client->start();
			?>
    	</div>
    </div>
    <div class="services">
    	<div class="container">
    		<div>
    			<button onclick="op('statment')">
    				<img src="images/account.png">
    				<span>Get Account Statement</span>
    			</button>
				<form method="GET" style="padding: 0px; margin: 0px; margin-left: 55px; background-color: unset; box-shadow: unset; display: inline">
					<input style="display: none" name="checkClickedGetTask" id="checkClickedGetTask" value="notClicked">
					<button type="submit" value=" Get Task " onclick="openTask()"> <img src="images/war2.png"> <span>Check Warnings</span> </button>
				</form>
    			</button>
    			<button onclick="op('reminders')" class="marg">
    				<img src="images/rim5.png">
    				<span>Check Reminders</span>
    			</button>
    			<button onclick="op('offers')">
    				<img src="images/bon2.png">
    				<span>Check Offers</span>
    			</button>
    			<button onclick="op('enquiry')" class="marg">
    				<img src="images/check.png">
    				<span>Enquiry</span>
    			</button>
    			<button onclick="op('visa')" class="marg">
    				<img src="images/visa2.png">
    				<span>Apply For Visa</span>
    			</button>
    		</div>
    	</div>
    </div>
    <div id="statment" class="statmentmodel">
            <div class="modal-content">
                <div class="myform">
                <a class="close" onclick="cl('statment')">&times;</a>
                <form class="statmentform" method="POST">
				   <b> Time Interval : From</b> <input name="dateStart" type="date"required> <b>To</b> <input name="dateEnd" type="date"required>
				   <select name="accountTypeStatment">
					   <option selected>Account Type</option>
					   <option>Saving Account</option>
					   <option>Current Account</option>
				   </select>
                    <table>
                    	<tr>
                    		<th>Number</th>
                    		<th>Date</th>
                    		<th>Transaction</th>
                    		<th>Amount</th>
						</tr>
						<?php
							if(isset($_POST['dateStart']) && isset($_POST['dateEnd']) && $_POST['accountTypeStatment'] != 'Account Type'):
								$client->getAccountStatement($_POST['accountTypeStatment'], $_POST['dateStart'], $_POST['dateEnd']);
							endif;
						?>
					</table>
					<table>
						<tr>
							<th>Total</th>
							<?php
								if(isset($_POST['dateStart']) && isset($_POST['dateEnd']) && $_POST['accountTypeStatment'] != 'Account Type'):
									$client->getTotalMoney($_POST['accountTypeStatment']);
								endif;
							?>
						</tr>
					</table>
                        <button class="mysubmit">Submit</button>
				</form>
				<?php
				?>
            </div>
            </div>
        </div>
        <div id="warnings" class="warningsmodel">
            <div class="modal-content">
                <div class="myform">
                <a class="close" onclick="cl('warnings')">&times;</a>
                <form class="warningsform" method="POST">
                	<table>
                		<tr>
                			<th>Date</th>
                			<th>Warning</th>
                		</tr>
						<?php
							$url = $_SERVER['REQUEST_URI'];
							@$url = explode("?", $url)[1];
							if($url == 'checkClickedGetTask=clicked'):
								$client->getWarning();
							endif;
						?>
                	</table>
                </form>
            </div>
        </div>
    </div>
    		<div id="reminders" class="remindersmodel">
            <div class="modal-content">
                <div class="myform">
                <a class="close" onclick="cl('reminders')">&times;</a>
                <form class="remindersform">
                	<table>
                		<tr>
                			<th>Date</th>
                			<th>Reminder</th>
                		</tr>
						<?php
							$client->getReminder();
						?>
                	</table>
                </form>
            </div>
        </div>
    </div>
    				<div id="enquiry" class="enquirymodel">
            			<div class="modal-content">
               				 <div class="myform">
                <a class="close" onclick="cl('enquiry')">&times;</a>
                	<form class="enquiryform" method="POST">
                		<b>Your Name</b> <input name="customerName" type="text" placeholder="Customer Name"required>
                		<b>Email Address</b> <input name="email" type="text" placeholder="Mail@Example.com"required>
                		<b>Phone Number</b> <input name="phoneNumber" type="number" placeholder="Phone Number"required>
                		<b>Customer Enquiry</b> <textarea name="enquiry" rows="3" placeholder=" Your Queries.." required></textarea>
                		<button class="mysubmit">Submit</button>
                		<button class="mysubmit">Reset</button>
					</form>
					<?php
						if(isset($_POST['customerName']) && isset($_POST['email']) && isset($_POST['phoneNumber']) && isset($_POST['enquiry'])):
							$client->enquiry($_POST['customerName'], $_POST['email'], $_POST['phoneNumber'], $_POST['enquiry']);
						endif;
					?>
                </div>
            </div>
        </div>

        				<div id="visa" class="visamodel">
            			<div class="modal-content">
               				 <div class="myform">
                <a class="close" onclick="cl('visa')">&times;</a>
                	<form class="visaform" method="POST">
                		<b>Full Name </b> <input name="firstName" type="text" placeholder="First Name"><input name="lastName" type="text" placeholder="Last Name" required>
                		<b>Name On Card </b> <input name="cardName" type="text"placeholder="How do you want your name?" required>
                		<b>Gender</b>
                		<select name="gender2">
							<option selected>Gender</option>
                			<option>Male</option>
                			<option>Female</option>
                		</select>
						<b>Date of Birth </b> <input name="day" class="check" type="number" placeholder="Day" required>
						<input name="month" type="number" placeholder="Month" required>
                		<input name="year" type="number" placeholder="Year" reqired>
                		<b>E-Mail</b> <input name="email2" type="text" placeholder="Example@mail.com"required>
                		<b>Phone Number </b> <input name="phoneNumber" type="number"required>
                		<b>Address </b> <input name="city" type="text"required placeholder="City"> <input name="street" type="text" placeholder="Street" quired> <input name="homeNumber" type="text" placeholder="Home Number" required>
                		<b>Do you Have Dual Citizenship?</b>
                		<select name="dualCitizenship">
                			<option>Yes</option>
                			<option>No</option>
                		</select>
                		<button class="mysubmit">Submit</button>
					</form>
					<?php
						if(isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['cardName']) && $_POST['gender2'] != 'Gender'
							&& isset($_POST['day']) && isset($_POST['month']) && isset($_POST['year']) && isset($_POST['email2'])
							&& isset($_POST['phoneNumber']) && isset($_POST['city']) && isset($_POST['street']) && isset($_POST['homeNumber'])
							&& isset($_POST['dualCitizenship'])):

							$address = new Address($_POST['city'], $_POST['street'], $_POST['homeNumber']);
							$client->applyForVisa($_POST['firstName'], $_POST['lastName'], $_POST['cardName'], $_POST['gender2'],$_POST['day'],
								$_POST['month'], $_POST['year'], $_POST['email2'], $_POST['phoneNumber'], $address, $_POST['dualCitizenship']);
						endif;
					?>
                </div>
            </div>
        </div>
        				<div id="offers" class="offersmodel">
            			<div class="modal-content">
               				 <div class="myform">
                <a class="close" onclick="cl('offers')">&times;</a>
                	<form class="offersform">
                		<ul>
                		<li>Special offers for our clients on Al-Kotob Khan (10% Discount)</li>
                		<img class ="off1"src="images/a1.jpg">
                		</ul>
                		<ul>
                		<li class="wordsss">Special offers for our clients on Swiza (20% Discount)</li>
                		<img class ="off2"src="images/swiza.jpg">
                		</ul>


                	</form>
                </div>
            </div>
        </div>

         <script type="text/javascript">
            function op(id)
            {
                document.getElementById(id).style.display = 'block';
            }
            function cl(id)
            {
             	document.getElementById(id).style.display = "none";
				open('http://localhost/bank/htML/client%20page/client.php', '_self');
            }
			function openTask(){
              document.getElementById('checkClickedGetTask').value = 'clicked';
            }
        </script>
</body>
</html>
