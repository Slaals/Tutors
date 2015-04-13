<div class="table-manage-div">
	<table class="table-component table-manage-data">
		<caption><?php echo $caption; ?></caption>
		<tr>
			<?php
			foreach ($table_header as $val) {
				?>
				<th><?php echo $val; ?></th>
				<?php
			}
			?>
		</tr>
		<tr class="<?php echo $hidden_row_class; ?>" id="table-hidden-row">
			<?php
			foreach ($table_hidden_data as $data_row) {
				?>
				<td>
					<?php
					echo $data_row;
					?>
				</td>
				<?php
			}
			?>
		</tr>

		<?php
		foreach ($table_data as $key => $data_row) {
			?>
			<tr class="table-row <?php echo $class; ?>" id="enseignant_<?php echo $key; ?>">
				<?php
				foreach ($data_row as $key_cell => $data_cell) {
					if (is_array($data_cell)) {
						?>
						<td>
							<table>
								<tr>
									<?php
									foreach ($data_cell as $key => $value) {
										?>
										<td class="<?php echo $key; ?>"><?php echo $value; ?> </td>
										<?php
									}
									?>
								</tr>
							</table>
						</td>
						<?php
					} else {
						?>
						<td class="<?php echo $key_cell; ?>"> <?php echo $data_cell; ?> </td>
						<?php
					}
				}
				?>
			</tr>
			<?php
		}
		?>
	</table>
	<?php
	if($error != '') {
		?>
		<div class="form-error"><?php echo $error ?></div>
		<?php
	}
	?>
</div>
