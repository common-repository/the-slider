<?php
function the_slider_options() {
	global $the_slider_name,$the_slider_shortname,$cc_login_type,$current_user,$wp_roles;
	$the_slider_name = "The Slider";
	$the_slider_shortname = "the_slider";

	$the_slider_options[] = array(  "name" => "Help",
            "type" => "heading",
			"desc" => "This section provides information about how to configure The Slider plugin.");
	
	return $the_slider_options;
}

function the_slider_add_admin() {

	global $the_slider_name, $the_slider_shortname, $the_slider;

	$the_slider_options=the_slider_options();

	if (isset($_GET['page']) && ($_GET['page'] == "the_slider")) {

		if ( isset($_REQUEST['action']) && 'install' == $_REQUEST['action'] ) {
			delete_option('the_slider_log');
			foreach ($the_slider_options as $value) {
				if( isset( $_REQUEST[ $value['id'] ] ) ) {
					update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
				} else { delete_option( $value['id'] );
				}
			}
			header("Location: admin.php?page=the_slider&installed=true");
			die;
		}
	}
	
	add_options_page($the_slider_name, $the_slider_name, 'activate_plugins', 'the_slider','the_slider_admin');
}

function the_slider_admin() {

	global $the_slider_name, $the_slider_shortname;

	$controlpanelOptions=the_slider_options();

	if ( isset($_REQUEST['install']) ) echo '<div id="message" class="updated fade"><p><strong>'.$the_slider_name.' settings updated.</strong></p></div>';
	if ( isset($_REQUEST['error']) ) echo '<div id="message" class="updated fade"><p>The following error occured: <strong>'.$_REQUEST['error'].'</strong></p></div>';

	?>
<div class="wrap">
<div id="cc-left" style="position: relative; float: left; width: 80%">
<h2><b><?php echo $the_slider_name; ?></b></h2>
	<?php
	$the_slider_version=get_option("the_slider_version");
	$submit='Update';
	?>
<form method="post">
<?php require(dirname(__FILE__).'/includes/cpedit.inc.php')?>
<!-- 
<p class="submit"><input name="install" type="submit" value="<?php echo $submit;?>" /> <input
	type="hidden" name="action" value="install"
/></p>
 -->
 <?php require(dirname(__FILE__).'/includes/help.php')?>
</form>
<hr />
	</div>
<?php
require(dirname(__FILE__).'/includes/support-us.inc.php');
zing_support_us('the-slider','the-slider','the_slider',THESLIDER_VERSION);
}
add_action('admin_menu', 'the_slider_add_admin'); ?>