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
          <form action="system/reset-request.sys.php" method="post">
                <input type="text" name="email" placeholder="Enter your e-mail address...">
                <button type="submit" name="reset-request-submit">Recieve new password by e-mail</button>
           </form>
           <?php
            if(isset($_GET['reset'])){
              if($_GET['reset'] == 'success'){
                echo '<p>Check your e-mail!</p>';
              }
            }
           ?>
        	</div>
        </div>
</body>

</html>
