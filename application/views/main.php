<?php
// block direct access to view
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html>
	<head>
	<?php include($GLOBALS['APP_PATH'].'/views/head.php');?>
	</head>
	<body>
	<?php include($GLOBALS['APP_PATH'].'/views/header.php');?>
	<h1><?php echo htmlentities($greeting) ?></h1>
	</body>
</html>