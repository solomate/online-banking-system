<!DOCTYPE html>
<html Lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="normalize.css">
<link rel="stylesheet" href="style.css">
</head>
<body>
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
          <?php
            $selector = $_GET['selector'];
            $validator = $_GET['validator'];

            if(empty($selector) || empty($validator)){
              echo "Could not validate your request";
            }
            else{
              if(ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false){
                ?>

                  <form action="system/reset-password.sys.php" method="post">
                    <input type="hidden" name="selector" value="<?php echo $selector; ?>">
                    <input type="hidden" name="validator" value="<?php echo $validator; ?>">
                    <input type="password" name="pwd" placeholder="Enter a new password...">
                    <input type="password" name="pwd-repeat" placeholder="Repeat new password...">
                    <button type="submit" name="reset-password-submit">Reset password</button>
                  </form>

                <?php
              }
            }
          ?>
        	</div>
        </div>


    	<div class="footer">
    		Copyrights are recieved 2020 &copy;
    </div>
</body>

</html>
