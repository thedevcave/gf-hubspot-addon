<?php 
$oauth = new Helpers\OAuth2Helper;
?>

<div id="gf_hs_addon_auth">
	<h2>Authentication Required to connect Gravity Forms to your Hubspot Account</h2>
	<?php 
	// echo get_option( 'gf_hs_addon_access_token' )."<br>";
	// echo get_option( 'gf_hs_addon_expires_at' )."<br>";
	// echo time()."<br><br>".print_r(get_option( 'gf_hs_addon_token' ), true);
	?>
	<?php if(!$oauth->isAuthenticated()): ?>
		<button onclick="window.open('<?php echo $oauth->getAuthUrl(); ?>', '', 'width=800,height=600')">Authorize GF to Hubspot Add-on</button>
		<script>
				window.setInterval('refresh()', 10000); 
		
				// Refresh or reload page.
				function refresh() {
						window .location.reload();
				}
		</script>
	<?php else: ?>
		<?php include GF_HUBSPOT_ADDON_DIR . '/lib/actions/getForms.php' ?>
		<p>Thank you for authorizing the GF to Hubspot Add-On</p>
		<table class="contacts-list">
			<thead>
			<tr>
				<th>Portal ID</th>
				<th>Form ID</th>
				<th>Form Name</th>
			</tr>
			</thead>
			<tbody>
		
			<?php foreach ($forms as $form) { ?>
				<?php //echo "<pre>"; print_r($form); echo "</pre>"; ?>
				<tr>
					<td><?php echo $form->portalId; ?></td>
					<td><?php echo $form->guid; ?></td>
					<td><?php echo $form->name; ?></td>
				</tr>
			<?php }?>
			</tbody>
		</table>
	<?php endif; ?>
</div>