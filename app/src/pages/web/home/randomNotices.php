<?php if ($tipopost == 1){ ?>
	<div class="randomNotices">
		<ul>
			<?php do { $imagenes=explode('-:#:-', $row_RandomNotice['imagen1']); ?>
				<li onclick="location.href='<?php echo $urlWeb ?><?php echo $row_RandomNotice['urlamigable']; ?>.html'">
					<img src="<?php echo $imagenes[0]; ?>"/>
					<div class="title">
						<?php echo $row_RandomNotice['titulo']; ?>
					</div>
				</li>
			<?php } while ($row_RandomNotice = mysql_fetch_assoc($RandomNotice)); ?>			
		</ul>
	</div>
<?php } ?>