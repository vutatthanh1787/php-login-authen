<!DOCTYPE html>
<html>
<head>
	<title>Text Ajax</title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>

	<button type="button">Click Me</button>
	<p></p>
	<?php
		$password = 'admin';
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		var_dump($hashed_password);
		var_dump(password_verify($password, $hashed_password)) ;
	 ?>
	<script type="text/javascript">
	    $(document).ready(function(){
	        $("button").click(function(){

	            $.ajax({
	                type: 'POST',
	                url: 'script.php',
	                success: function(data) {
	                    alert(data);
	                    $("p").text(data);

	                }
	            });
	   });
	});
	</script>

</body>
</html>