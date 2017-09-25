<?php
	require('functions/all.php');
?>

<html>
	<head>
		<title>ערעורון - בטא</title>
		
		<!-- Google fonts -->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
		
		<!-- Favicon -->
		<link rel="icon" type="image/png" href="images/favicon.png" />
		
		<!-- Stylesheets -->
		<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.10.4/themes/start/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/skeleton.css" />
		<link rel="stylesheet" type="text/css" href="css/skeleton-overrides.css" />
		<link rel="stylesheet" type="text/css" href="css/hexdots.css" />
		<link rel="stylesheet" type="text/css" href="css/mobile.css" />
		
		<!-- Javascript -->
		<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
		<script src="js/jquery.inputmask.bundle.js"></script>
		<script src="js/main.js"></script>
		<script src="js/storyboard.js"></script>
		<script src="js/logo.js"></script>
		
		<!-- Google Analytics -->
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			
			ga('create', 'UA-23421052-6', 'auto');
			ga('send', 'pageview');
		</script>
		
		<!-- General Meta -->
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="description" content="האפליקציה שעוזרת לך לבטל דוחות חניה" />
		<meta name="keywords" content="דוחות, חניה, דוח, parking, ticket, אלירן, פאר, Eliran, Pe'er'" />
		
		<!-- Google Meta -->
		<link rel="author" href="https://plus.google.com/106403508857505805908"/>
		
		<!-- Facebook Meta -->
		<meta property="og:title" content="ערעורון"/>
		<meta property="og:type" content="profile" />
		<meta property="og:description" content="האפליקציה שעוזרת לך לבטל דוחות חניה" />
		<meta property="og:locale" content="he_IL" />  
		<meta property="og:locale:alternate" content="en_US" />
		<meta property="article:author" content="https://www.facebook.com/eliran.peer" />
		<meta property="og:image" content="/siteimage.png" />
		
		<!-- Twitter Meta -->
		<meta property="twitter:card" content="summary" />
		<meta property="twitter:title" content="ערעורון" />
		<meta property="twitter:description" content="האפליקציה שעוזרת לך לבטל דוחות חניה" />
	</head>
	<body>
		<div id="loading-div">
			<div style="position: fixed; top: 50%; left: 50%;">
				<div class="hexdots-loader"></div>
			</div>
		</div>
				
		<div class="container">
			<header>
				<h1 id="site-name">
					<a href="/">
						<img src="images/logo.png" id="logo" /> 
						ערעורון
					</a>
				</h1> 
				
				<h2 class="slogan">
					הדרך המהירה לבטל דוחות חניה
				</h2>
			</header>
			
			<?php 
				sections();
			?>
			
			<footer>
				פותח על ידי 
				<a href="http://cookiesession.com/עלינו/אלירן-פאר" class="bold-link">אלירן פאר</a>. 
			</footer>
		</div>
		
		<div id="confirmDialog">
			<div class="message">
				בלחיצה על כן, הודעה תשלח אל העירייה. האם אתה בטוח שברצונך לשלוח ערעור זה?
			</div>
			
			<div class="buttons">
				<a class="button button-primary left yes">כן</a>
				<a class="button right no">לא</a>
			</div>
		</div>
	</body>
</html>