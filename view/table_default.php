<div class="<?php echo $structure_class; ?>">
	<table class="table-component table-default">
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
		<?php
		foreach ($table_data as $key => $data_row) {
			?>
			<tr class="table-row" id="ligne_<?php echo $key; ?>">
				<?php
				foreach ($data_row as $data_cell) {
					?>
					<td> <?php echo $data_cell; ?> </td>
					<?php
				}
				?>
			</tr>
			<?php
		}
		?>
	</table>
</div>