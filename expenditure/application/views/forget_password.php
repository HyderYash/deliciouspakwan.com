<?=_getSectionHeader('Password Recovery',$loginmsg,'width:400px;margin:10px auto;')?>
	<form name="login_form" id="login_form" action="/signUp" method="post">
		<div class="imgcontainer">
			<img src="/images/img_avatar2.png" alt="Avatar" class="avatar">
		</div>

		<div class="login_container" id="login-container">
			<label><b>Username</b></label>
			<input type="text" placeholder="Enter Username" name="uname" required>
			
			<label><b>Email</b></label>
			<input type="email" placeholder="Enter Email" name="uemail" required>
			
			<label><b>Question</b></label>
			<input type="text" name="addques" value="(<?=rand(6,20)?>*<?=rand(2,10)?>)+(<?=rand(41,60)?>-<?=rand(21,40)?>)" style="background-color:#ececec;text-align:center;" readonly>
			
			<label><b>Answer</b></label>
			<input type="text" placeholder="Enter Answer of the above Question" name="addans" required>
			
			<button type="submit">Recover Password</button>
			<input type="checkbox" checked="checked"> Remember me
		</div>

		<div class="login_container" style="background-color:#f1f1f1">
			<a href="/index.php"><button type="button" class="cancelbtn">Sign In</button></a>
		</div>
	</form>		
<?=_getSectionFooter()?>