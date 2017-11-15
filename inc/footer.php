<script type="text/javascript">

//LOGOUT:::::::::::::
 $(document).ready( function() {
    $("#logout").click(function() {
        logout(301);
     });
 });

//logout through ajax
function logout(buttonSelect){
	
   var data = {buttonSelect : buttonSelect}
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

} // end logout function
</script>
  </body>
</html>