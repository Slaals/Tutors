<!DOCTYPE html>
<html lang="fr">
	<head>
		<title><?php echo $title; ?></title>
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo __SITE_ROOT . '/assets/img/favicon.ico'; ?>" /> 
		<link rel="shortcut icon" type="image/png" href="<?php echo __SITE_ROOT . '/assets/img/favicon.ico'; ?>" /> 

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

		<link rel="stylesheet" href="<?php echo __SITE_ROOT . '/assets/css/reset.css'; ?>" type="text/css" media="screen">
		<link rel="stylesheet" href="<?php echo __SITE_ROOT . '/assets/css/style.css'; ?>" type="text/css" media="screen">
		<link rel="stylesheet" href="<?php echo __SITE_ROOT . '/assets/css/components.css'; ?>" type="text/css" media="screen">

		<script type="text/javascript" src="<?php echo __SITE_ROOT . '/assets/js/jquery-1.10.2.js'; ?>"></script>
		<script type="text/javascript" src="<?php echo __SITE_ROOT . '/assets/js/jquery-ui-1.10.4.custom.js'; ?>"></script>
		<script type="text/javascript" src="<?php echo __SITE_ROOT . '/assets/js/jquery.easing.1.3.js'; ?>"></script>
		<script type="text/javascript" src="<?php echo __SITE_ROOT . '/assets/js/functions.js'; ?>"></script>
		<script type="text/javascript" src="<?php echo __SITE_ROOT . '/assets/js/components.js'; ?>"></script>

	</head>
	<body id="page1">
		<!--==============================header=================================-->
		<header>
			<div class="menu-row">
				<?php echo $widget_menu_feature; ?>
				<div class="main">
					<nav class="wrapper">
					<img id="logo" src="<?php echo __SITE_ROOT . '/assets/img/logo_utt.png'; ?>"/>
						<?php echo $widget_menu_main; ?>
					</nav>
				</div>
			</div>
		</header>

		<!--==============================content================================-->
		<section id="content">
			<div class="main">
                <div class="wrapper">
					<h1><?php echo $page_first_title; ?></h1>
					<h3><?php echo $page_second_title; ?></h3>
					<hr/>
					<?php echo $content; ?>
                </div>
			</div>
		</section>

		<!--==============================footer=================================-->
		<footer>
			<div class="main">
				<div class="aligncenter">
					<p class="p0"></p>
				</div>
			</div>
		</footer>
	</body>
</html>
