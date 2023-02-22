<?=$header?>
<div id="wrapper">
	<!-- Navigation -->
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
		<div class="navbar-header">
			<img style="width: 30px; float: left; margin: 10px;" src="/images/exp_logo.jpg" /><a class="navbar-brand" href="#l">Admin Panel</a>
		</div>
		<?php 
		$this->load->view('adminpanel/common/nav-top.php','', '');
		$this->load->view('adminpanel/common/nav-left.php','', '');
		?>
	</nav>
	<?=$content?>
</div>
<!-- /#wrapper -->
<?=$footer?>