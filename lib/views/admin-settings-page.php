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
		<p>Thank you for authorizing the GF to Hubspot Add-On. You now have access to sync form submissions from Gravity Forms to any of your Hubspot forms.</p>
		<p>To avoid any errors, please be sure that your Gravity Form fields all match up to a Hubspot field, including making sure required fields are marked as so on both systems.</p>
		<p>If you are having any issues with form submissions not going through you can return to this page and check the Error Logs below. The most recent errors will always be at the top of the file.</p>
		<p>&nbsp;</p>
		<hr />
		<h3>Hubspot Error Logs</h3>
		<code style="display: inline-block; padding: 10px 12px; border-radius: 5px; border: 2px solid #ccc; line-height: 1.6; width: 100%; max-width: 1080px; max-height: 500px; overflow-y: scroll;">
			<?php 
				$fh = fopen(GF_HUBSPOT_ADDON_DIR . "/logs/hubspot-error-logs.txt", "r"); 
				$logText = fread($fh, 5000);
				echo nl2br($logText);
			?>
		</code>
	<?php endif; ?>
</div>