<ul class="widget-menu-feature">
    <li>
        <a href="#" class="first">Fonctionnalités</a>
        <ul class="widget-menu-feature-list">
			<?php
			foreach ($links as $val) {
				?>
				<li class="<?php echo $val['class']; ?>"><a href="<?php echo __SITE_ROOT . $val['href']; ?>"><?php echo $val['value']; ?></a></li>
				<?php
			}
			?>
        </ul>
    </li>
</ul>