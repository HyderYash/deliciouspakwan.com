<?=_getSectionHeader('Sign In',$loginmsg,'width:400px;margin:10px auto;')?>
	<form name="login_form" id="login_form" action="/index.php" method="post">
		<div class="imgcontainer">
			<img src="/images/img_avatar2.png" alt="Avatar" class="avatar">
		</div>

		<div class="login_container" id="login-container">
			<label for="uname"><b>Username</b></label>
			<input type="text" placeholder="Enter Username" id="uname" name="uname" required>

			<label for="psw"><b>Password</b></label>
			<input type="password" placeholder="Enter Password" id="psw" name="psw" required>

			<button type="submit">Login</button>
			<label for="chk">Remember me</label><input type="checkbox" id="chk" name="chk" checked="checked"> 
		</div>

		<div class="login_container" style="background-color:#f1f1f1">
			<a href="/signUp"><button type="button" class="cancelbtn">Sign up</button></a>
			<span class="psw"><a href="/recover">Forgot password?</a></span>
		</div>
	</form>		
<?=_getSectionFooter()?>