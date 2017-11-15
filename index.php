<?php 
include 'inc/header.php'; 
?>
    <center>
		<table>
			<th colspan="2">Login</th>
			<tr>
				<td>Username</td>
				<td><input type="text" id="username" name="username" /></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input type="password" id="password" name="password" /></td>
			</tr>
			<tr>
				<td colspan="2" style="border:none !important;"><input type="button" id="login" name="login" value="Login" style="float:right;" onClick="login(300,0);"/></td>
			</tr>

		</table>
	</center>
<script>
//run func on enter
$('#password').on('keydown',function(e){
    e.stopPropagation();
    if (e.keyCode == 13)
    {
        login(300,0);
    }
});
//check if fields are blank
function isBlank( data ) {
    return ( $.trim(data).length == 0 );
}
//login through ajax
function login(buttonSelect, id){
	
	var username = $('#username').val();
	var password = $('#password').val();
	
	if(isBlank(username) || isBlank(password)){
                $("<div title='Message Status!'>All fields are required!</div>").dialog();

	}else{
	
	var data = {buttonSelect : buttonSelect, id : id, username : username, password : password}
    $.ajax({
           url: "inc/auth.php",
           type: "POST",
           data: data ,
		   dataType: "JSON",
           success: function (result) {
			   if(result.msg == "OK"){ window.location.href=result.page;}else{
			   $("<div title='Message Status!'>"+result.msg+"</div>").dialog();}
           },
           error: function(jqXHR, textStatus, errorThrown) {
	            $("<div title='Message Status!'>Something went wrong!</div>").dialog();
           }
		   
       }); //end ajax post
   }//end ifelse
} // end login function
</script>
<?php include 'inc/footer.php'; ?>