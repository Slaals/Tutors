<form method="<?php echo $method; ?>" action="<?php echo $action; ?>" <?php echo $data_form; ?>>
	<table class="form">
		<?php
		foreach ($fields as $key => $val) {
			?>
			<tr>
				<td class="label"><?php echo $val['label']; ?></td>
				<td class="field"><?php echo $val['field']; ?></td>
			</tr>
			<?php
		}
		?>
		<tr><td colspan="2"><input type="submit"/></td></tr>
	</table>
</form>
<?php
if (!empty($errors)) {
	?>
	<div class="form-error"><?php echo $errors; ?></div>
	<?php
}