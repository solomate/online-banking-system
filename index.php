<?php
    require_once "PHP/classes/Database.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>home page</title>
        <link rel="stylesheet" href="http://localhost/Bank/HTML/home/normalize.css">
        <link rel="stylesheet" type="text/css" href="http://localhost/Bank/HTML/home/style.css">
    </head>
    <body>
    	<div class="mycover">
    		<div class="about">
            	<div class="container">
            		<div class="photo">
	                    <img class="social" src="http://localhost/Bank/HTML/home/images/facebook1.png" onclick="location.href='http://www.facebook.com'">
	        			<img class="social" src="http://localhost/Bank/HTML/home/images/twitter2.png" onclick="location.href='http://www.twitter.com'">
	        			<img class="social" src="http://localhost/Bank/HTML/home/images/linkedin1.png"onclick="location.href='http://www.linkedin.com'">
	        			<img class="social" src="http://localhost/Bank/HTML/home/images/instagram2.png"onclick="location.href='http://www.instgram.com'">
	        			<button class="login" onclick="location.href='http://localhost/Bank/Login%20System/login.php'">Log in</button>
	    			</div>
            	</div>
	        </div>
	        <div class="navigation">
	        	<div class="container">
	        		<div class="logo">
	        			<img class="banklogo" src="http://localhost/Bank/HTML/home/images/logo.png" width="50px">
	        		</div>
	        		<div class="bankname">
	        			 <span class="master"><span class="m">M</span>aster <span class="b">B</span>ank </span>
	        		</div>
	        		<ul>
	        			<button>Home</button>
	        			<button onclick="window.location.href='#aboutus'">About</button>
	        			<button onclick="window.location.href='#ourservices'">Services</button>
	        			<button onclick="window.location.href='#contactus'">Contact Us</button>
	        		</ul>
	        	</div>
	        </div>

    	</div>

        <div class="Servicesofbank">
        	<div class="container" style=" background-color: #eee;border-radius: 25px; width: 30%;border: 2px solid #FF9800;">
        		Our <span id="ourservices" style="color: #FF9800;">Services</span>
        	</div>
        </div>
        <div class="typeofservices">
        	<div class="container">
        		<div class="image">
        			<img src="http://localhost/Bank/HTML/home/images/bank Service3.jpg">
        		</div>
        		<div class="info">
        			<div class="hoppies">
        				<div class="content">
        					<div class="icon">
        						<img src="http://localhost/Bank/HTML/home/images/dolar.png">
        					</div>
        					<div class="textcontent">
        						<h2> Free Online</h2>
        						<p>Vestibulum ante ipsum primis in faucibus orci luctus eted Vestibulum ante ipsum primis in faucibus orci luctus eted ultrices posuere Curae primis in faucibus orci luctus eted.
        						</p>
        					</div>

        				</div>
        				<div class="content">
        					<div class="icon">
        						<img src="http://localhost/Bank/HTML/home/images/person.png">
        					</div>
        					<div class="textcontent">
        						<h2>Accept All Major</h2>
        						<p>Vestibulum ante ipsum primis in faucibus orci luctus eted Vestibulum ante ipsum primis in faucibus orci luctus eted ultrices posuere Curae primis in faucibus orci luctus eted.
        						</p>
        					</div>
        				</div>
        				<div class="content">
        					<div class="icon">
        						<img src="http://localhost/Bank/HTML/home/images/pointer.png">
        					</div>
        					<div class="textcontent">
        						<h2>Financial Advisors</h2>
        						<p>Vestibulum ante ipsum primis in faucibus orci luctus eted Vestibulum ante ipsum primis in faucibus orci luctus eted ultrices posuere Curae primis in faucibus orci luctus eted.
        						</p>
        					</div>

        				</div>
        				<div class="content">
        					<div class="icon">
        						<img src="http://localhost/Bank/HTML/home/images/busniss.png">
        					</div>
        					<div class="textcontent">
        						<h2>Business Loan </h2>
        						<p>Vestibulum ante ipsum primis in faucibus orci luctus eted Vestibulum ante ipsum primis in faucibus orci luctus eted ultrices posuere Curae primis in faucibus orci luctus eted.
        						</p>
        					</div>

        				</div>
        			</div>
        		</div>
        	</div>
        </div>
        <div class="aboutusheader">
        	<div class="container"style=" background-color: #eee;border-radius: 25px; width: 30%;border: 2px solid #FF9800;">
        		About<Span class="ff" id="aboutus"> Us</Span>
        	</div>
        </div>
        <div class="aboutusbody">
        	<div class="container">
        		<div class="contentofabour">
        			<div class="headerofabout">
        				<h2>Sed tincidunt lorem</h2>
        				<p>bjbdjbdkdbfdjdfdhfdjk Vestibulum ante ipsum primis in faucibus orci luctus eted Vestibulum ante ipsum primis in faucibus orci luctus eted ultrices posuere Curae primis in faucibus orci luctus eted bjbdjbdkdbfdjdfdhfdjk Vestibulum ultrices posuere Curae </p>
        				<h2>Sed tincidunt lorem</h2>
        				<p>bjbdjbdkdbfdjdfdhfdjk eted Vestibulum ante ipsum primis in faucibus orci luctus eted ultrices posuere Curae primis in faucibus orci luctus eted bjbdjbdkdbfdjdfdhfdjk Vestibulum ante ipsum primis in faucibusin faucibus orci luctus eted ultrices </p>
        				<h2>Offering the most </h2>
        				<p>bjbdjbdkdbfdjdfdhfdjk Vestibulum ante ipsum primis in faucibus orci luctus eted Vestibulum ante ipsum primis in faucibus orci luctus eted ultrices posuere Curae primis in faucibus orci luctus eted </p>
        			</div>
        			<div class="imageofabout">
        				<img src="http://localhost/Bank/HTML/home/images/bank Service4.jpg">
        			</div>
        		</div>
        	</div>
        </div>
        <div class="producthead">
        	<div class="container">
        		Products To Meet Your Life Goals
        	</div>
        </div>
        <div class="productbody">
        	<div class="container">
        		<div>
        			<img src="http://localhost/Bank/HTML/home/images/coin.png">
        			<h3>Personal Loan</h3>
        		</div>
        		<div>
        			<img src="http://localhost/Bank/HTML/home/images/fixed.png">
        			<h3>Fixed Deposit</h3>
        		</div>
        		<div><img src="http://localhost/Bank/HTML/home/images/save.png">
        			<h3>Saving Account</h3>
        		</div>
        		<div>
        			<img src="http://localhost/Bank/HTML/home/images/credit.png">
        			<h3>Credit Cards</h3>
        		</div>
        	</div>
        </div>

        <div class="contacinfo">
        	<div class="container">
        		<div class="medo">
        			<h2 id="contactus">Contact Us</h2>
                    <p>Contact us by visiting our bank on the following addresses or by contacting us either by following E-Mail or following Phone Numbers or sending your message to our site.
        			</p>
        			<address>
        				123 Elnozha<br>
        				Cairo<br>
        				Ramsise Area
        			</address>
        			<p>
        				<strong>Email: </strong>Master bank@gmail.com<br>
        				<strong>Phone: </strong> 05465659843
        			</p>
        		</div>
        		<form class="form" method="POST">
        			<label>Name *</label>
        			<input name="name" type="text">
        			<label>Email Address *</label>
        			<input name="email" type="text">
        			<label>Phone *</label>
        			<input name="phone" type="text">
        			<label>Massage *</label>
        			<textarea name="message"></textarea>
        			<input type="submit" value="Contact Us">
        		</form>
                <?php
                    if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['message'])):
                        $name = $_POST['name'];
                        $email = $_POST['email'];
                        $phoneNumber = $_POST['phone'];
                        $message = $_POST['message'];
                        $number = $db->search("SELECT * FROM `enquiry`", ['Number']);
                        if(empty($number)):
                            $number = 1;
                        else:
                            $number = $number[count($number) - 1] + 1;
                        endif;
                        $db->insert("INSERT INTO `enquiry`(`Number`, `Name`, `Email`, `Phone Number`, `Customer Enquiry`) VALUES('$number', '$name', '$email', '$phoneNumber', '$message')");
                    endif;
                ?>
        	</div>
        </div>
        <div class="footer">
        	<div class="container">
        		copyrights are recived 2020 &copy;
        	</div>
        </div>
    </body>
</html>
