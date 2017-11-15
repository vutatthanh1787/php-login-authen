<?php 
include('inc/header.php'); 
?>
<ul id="nav">
    <li><a href="#" id="addUser" ><span class="ui-icon ui-icon-person"></span>Add User</a></li>
    <li><a href="#" id="editUser"><span  class="ui-icon ui-icon-pencil"></span>Edit Users</a></li>
    <li><a href="#" id="settings" ><span class="ui-icon ui-icon-gear"></span>Settings</a></li>
	<li><a href="#" id="logout" ><span class="ui-icon ui-icon-unlocked"></span>Logout</a></li>
</ul>





<!-- Modal ADD USER -->
<div id="ModalAddUser" title="Add User" style="display:none;">
  
<input type="text" name="username" class="text ui-widget-content ui-corner-all" id="username" placeholder="Username">
<input type="text" name="fullname" class="text ui-widget-content ui-corner-all" id="fullname" placeholder="Full name">
<input type="email" name="email" class="text ui-widget-content ui-corner-all" id="email" placeholder="Email">
<input type="password" name="password" class="text ui-widget-content ui-corner-all" id="password" placeholder="Password" >
<input type="password" name="passwordc" class="text ui-widget-content ui-corner-all" id="passwordc" placeholder="Re-password" >
<input type="text" name="title" class="text ui-widget-content ui-corner-all" id="title" placeholder="Title (optional)" >
<input type="number" name="phone" class="text ui-widget-content ui-corner-all" id="phone" placeholder="phone #" >
<select name="phoneserviceprovider" id="phoneserviceprovider">
  <option value="">Select a service provider</option>
  <option value="AT&T">AT&T</option>
  <option value="Verizon">Verizon</option>
  <option value="T-Mobile">T-Mobile</option>
  <option value="Spirit">Spirit</option>
<option value="Cricket">Cricket</option>
<option value="Consumer Cellular">Consumer Cellular</option>
<option value="Virgin">Virgin</option>
<option value="US Cellular">US Cellular</option>
</select>
<label for="groupid" style="color:red;">Check if you want this user to be admin:</label><input type="checkbox"  name="groupid" id="groupid">
</div>

<!-- Modal EDIT USER -->
<div id="ModalEditUser" title="Edit User" style="display:none;">
<select name="members" id="members"></select>
<input type="text" name="username" class="text ui-widget-content ui-corner-all" id="username" placeholder="Username">
<input type="text" name="fullname" class="text ui-widget-content ui-corner-all" id="fullname" placeholder="Full name">
<input type="email" name="email" class="text ui-widget-content ui-corner-all" id="email" placeholder="Email">
<input type="password" name="password" class="text ui-widget-content ui-corner-all" id="password" placeholder="Change user password" >
<input type="text" name="title" class="text ui-widget-content ui-corner-all" id="title" placeholder="Title (optional)" >
<input type="number" name="phone" class="text ui-widget-content ui-corner-all" id="phone" placeholder="phone #" >
<select name="phoneserviceprovider" id="phoneserviceprovider">
  <option value="">Select a service provider</option>
  <option value="AT&T">AT&T</option>
  <option value="Verizon">Verizon</option>
  <option value="T-Mobile">T-Mobile</option>
  <option value="Spirit">Spirit</option>
<option value="Cricket">Cricket</option>
<option value="Consumer Cellular">Consumer Cellular</option>
<option value="Virgin">Virgin</option>
<option value="US Cellular">US Cellular</option>
</select>
<label for="groupid" style="color:red;">Check if you want this user to be admin:</label><input type="checkbox"  name="groupid" id="groupid">
</div>



<script>

$( "#nav" ).menu({position: {at: "left bottom"}});

//MODALS :::
$( function() {

 $( "#addUser" ).on( "click", function() {
      $( "#ModalAddUser" ).dialog( "open" );
    });

//Modal Add User
 $( "#ModalAddUser" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: 400,
      modal: true,
      
      buttons: {
        "Save": function() {
          runUsers("#ModalAddUser",1);
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });

//Modal Edit User
 $( "#ModalEditUser" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: 400,
      modal: true,
      
      buttons: {
        "Delete": function() {
          runUsers("#ModalEditUser",3);
        },
		"Update": function() {
          runUsers("#ModalEditUser",2);
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });

 $( "#editUser" ).on( "click", function() {
       $('#members').html('');
      //get user data::
	  runUsers("#ModalEditUser",10);
      $( "#ModalEditUser" ).dialog( "open" );
    });

	
} ); //end MODALS
//On selcet change
$("#members").change(function () {
   runUsers("#ModalEditUser",11);
 });


function runUsers(submitType, submitId){

var goodToGo = 0;


var id = $('#ModalEditUser #members option:selected').val();

if(submitId < 3){
	var username = $(submitType+' #username').val();
	var fullname = $(submitType+' #fullname').val();
	var email = $(submitType+' #email').val();
	var password = $(submitType+' #password').val();
	var passwordc = $(submitType+' #passwordc').val();
	var title = $(submitType+' #title').val();
	var phone = $(submitType+' #phone').val();
	var phoneserviceprovider = $(submitType+' #phoneserviceprovider option:selected').text();
	var groupid;
	//check-box group id is checked
	if($(submitType+' #groupid').is(":checked")){groupid = 2;}

		//check if it's update or add new
		if(submitId == 1){ //1=for add new
			if(username == "" || fullname == "" || email == "" || password == "" )
			{
				  alert("Please enter username, fullname, email, and password!");
				  goodToGo = 0;
			}
			else
			{ 
				if(password != passwordc ){
				  alert("Passwords do not match!");
				  goodToGo = 0;
				}else{
				  goodToGo = 1;
				}

			}
		}else{

			if(username == "" || fullname == "" || email == ""){
				 alert("Username, fullname and email cannot be empty!");
				 goodToGo = 0;
			}else{
				goodToGo = 1;
			}

		}
	
}else{
goodToGo = 1;
}

//Confirm if delete option is not an accident 
if(submitId == 3){
	var r = confirm("Are you sure you want to delete this user?");
	if (r == true) {
		goodToGo = 1;
	} else {
		goodToGo = 0;
	}
}

if(goodToGo == 1)
{

//var dataString = 'id='+ id + '&title='+ title + '&phone='+ phone + '&phoneserviceprovider='+ phoneserviceprovider + '&password='+ password + '&email='+ email + '&fullname='+ fullname + '&username='+ username + '&groupid='+ groupid + '&submitId='+ submitId;
var dataString ={id:id,title:title,phone:phone,phoneserviceprovider:phoneserviceprovider,password:password,email:email,fullname:fullname,username:username,groupid:groupid,submitId:submitId}
          $.ajax({
            type: 'POST',
            url: 'inc/process-users.php',
            data: dataString,
	        dataType: "json",
            cache: false,
            success: function (result) {
			
            
			  if(submitId < 4){
			  $(submitType).dialog('close');
              $("<div title='Message Status!'>"+result.msg +"</div>").dialog();
			  }else{
			  //GET USERS :::
             if(submitId == 10){
			    var options = [];				
				$.each(result.userList, function(key, value) {
					options.push($("<option/>", {
						value: key,
						text: value
					}));
				});
				$('#members').html(options);
			 
			 }
				$(submitType+' #username').val(result.userData['username']);
				$(submitType+' #fullname').val(result.userData['fullname']);
				$(submitType+' #email').val(result.userData['email']);
                                $(submitType+' #password').val("");
				$(submitType+' #title').val(result.userData['title']);
				$(submitType+' #phone').val(result.userData['phone']);
				$(submitType+' #phoneserviceprovider').val(result.userData['phoneserviceprovider']);
				if(result.userData['groupid']==2){$(submitType+' #groupid')[0].checked = true;}
				else{$(submitType+' #groupid')[0].checked = false;}
			 
			 }
			  
			  
            }
          });

}//end if

}//end function runUsers for update/add/delete

</script>
<?php include('inc/footer.php'); ?>