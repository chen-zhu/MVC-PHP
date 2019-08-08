<!doctype html>
<html>
	<head>
		<title>MVC Tutorial</title>
		<link rel="stylesheet" href="<?php echo URL;?>public/css/default.css"/>
		<script type="text/javascript" src="<?php echo URL;?>public/js/jquery.js"></script>
		<script src="<?php echo URL;?>public/js/custom.js"></script>
		<?php 
			//load js files that are related to the controller!
			if(isset($this->js) && is_array($this->js)){ //this is inside view object!
				foreach($this->js as $js){
					echo '<script type="text/javascript" src="' . URL . 'views/' . $js . '"></script>';
				}
			}
		?>
	</head>

<?php Session::init();?>	
	
	<body>
		<div id="header">
<?php if(Session::get('loggedIn') == false){ ?>
		<a href="<?php echo URL;?>index">Index</a>
		<a href="<?php echo URL;?>help">Help</a>
<?php } ?>
		
<?php if(Session::get('loggedIn') == true){ ?>
			<a href="<?php echo URL;?>dashboard">Dashboard</a>
			<a href="<?php echo URL;?>note">Notes</a>
			<?php if(Session::get('role') == 'owner'){ ?> 
				<a href="<?php echo URL;?>user">Users</a>
			<?php } ?>
			<a href="<?php echo URL;?>dashboard/logout">Logout</a>
<?php } else { ?>
			<a href="<?php echo URL;?>login">Login</a>
<?php } ?>
		
		</div>
		
		<div id="content">