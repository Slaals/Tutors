<select name="<?php echo $name; ?>" class="<?php echo $class; ?>" id="<?php echo $id; ?>" <?php echo $event; ?>>
	<?php
	foreach ($option as $val) {
		if ($value == $val) {
			?>
			<option value="<?php echo $val; ?>" selected ><?php echo $val; ?></option>
			<?php
		} else {
			?>
			<option value="<?php echo $val; ?>" ><?php echo $val; ?></option>
			<?php
		}
	}
	?>
</select>