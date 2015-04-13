<ul class="widget-menu-main">
	<?php
	foreach ($links as $val) {
		?>
		<li class="<?php echo $val['class']; ?>"><a href="<?php echo __SITE_ROOT . $val['href']; ?>"><?php echo $val['value']; ?></a></li>
		<?php
	}
	?>
</ul>
