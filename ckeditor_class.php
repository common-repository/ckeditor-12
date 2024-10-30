<?php

require_once(dirname(__FILE__).'/../../../wp-config.php');
class ckeditor {
    var $version = '1.3';
    var $default_options = array();
	var $ckeditor_path = "";
	var $plugin_path ="";

	//var $smiley_images = array();
    function ckeditor()
	{
		$this->__construct();
	}

	function __construct()
    {
		$siteurl = trailingslashit(get_option('siteurl'));
		$this->plugin_path =  $siteurl .'wp-content/plugins/' . basename(dirname(__FILE__)) .'/';
		$this->ckeditor_path = $siteurl .'wp-content/plugins/' . basename(dirname(__FILE__)) .'/ckeditor/';
        $this->default_options['user_file_path'] = 'wp-content/uploads/';
        $this->default_options['ckskin'] = 'kama';
        
        $options = get_option('ckeditor');
		if (!$options) {
			add_option('ckeditor', $this->default_options);
			$options = $this->default_options;
		}
		foreach ($options as $option_name => $option_value)
	        $this-> {$option_name} = $option_value;
    }
	function deactivate()
	{
		global $current_user;
		update_user_option($current_user->id, 'rich_editing', 'true', true);
	}

	function activate()
	{
		global $current_user;
		update_user_option($current_user->id, 'rich_editing', 'false', true);
	}
    function option_page()
    {
        $message = "";
        if (!empty($_POST['submit_update']) || !empty($_POST['doRebuild'])) {
			$new_options = array ('ckskin' => trim($_POST['ckskin']));
			if (empty($new_options['user_file_path']))
			{
				$new_options['user_file_path'] = 'wp-content/uploads';
			}
			update_option("ckeditor", $new_options);
			foreach ($new_options as $option_name => $option_value)
		        $this-> {$option_name} = $option_value;

			echo '<div class="updated"><p>' . __('Configuration updated!') . '</p></div>';
        }
		else if (isset($_POST['submit_reset'])) {
				update_option('ckeditor', $this->default_options);
				foreach ($this->default_options as $option_name => $option_value)
					$this-> {$option_name} = $option_value;
				echo '<div class="updated"><p>' . __('Configuration updated!') . '</p></div>';
		}
        ?>
		<div class=wrap>
			<fieldset>
				<form method="post" >
				<h2><?php _e('CKEditor', 'ckeditor') ?> <?php echo $this->version?></h2>
					<legend><?php _e('Basic Options', 'ckeditor') ?></legend>
					<li>
						<?php _e('Select the skin to load:')?>
						<select name="ckskin"">
							<option value="kama"  <?php if ($this->ckskin == 'kama') { ?> selected="selected"<?php } ?>>Kama</option>
							<option value="office2003" <?php if ($this->ckskin == 'office2003') { ?> selected="selected"<?php } ?>>Office 2003</option>
							<option value="v2" <?php if ($this->ckskin == 'v2') { ?> selected="selected"<?php } ?>>v2</option>
						</select>
					</li>
				<li>
			</fieldset>
				<p class="submit">
				<input type="hidden" name="df_submit" value="1" />
				<input type="submit" value="Reset to defaults" name="submit_reset" id="default-reset" />
				<input type="submit" value="Update Options &#187;" name="submit_update" />
				</p>
		</form></div>
		<?php
    }
     function add_admin_head()
    {
    ?>
		<style type="text/css">
			#quicktags { display: none; }
		</style>
	<?php
    }
	function load_ckeditor()
	{
		?>
	<script type="text/javascript">
		//<![CDATA[
		var content = CKEDITOR.replace( 'content',
		{
			skin : '<?php echo $this->ckskin;?>',
			language : 'en',
			uiColor: '#6B6868',
			enterMode		: 1,
			shiftEnterMode	: 0
			});
		
  		AjexFileManager.init({
			editor: content,
			skin: 'light',
			lang: 'en'
		});

	//]]>
	</script>
		<?php
	}

    function user_personalopts_update()
    {
        global $current_user;
        update_user_option($current_user->id, 'rich_editing', 'false', true);
    }

    function add_option_page()
    {
		add_options_page('ckeditor', 'CKEditor', 8, 'ckeditor', array(&$this, 'option_page'));
    }

	function disable_preview()
	{
		if ($this->enable_preview != 'yes')
		{
		?>
		<script type="text/javascript">
		var oPreview = document.getElementById('preview');
		if (oPreview)
			{
			oPreview.innerHTML = '&nbsp;';
			}
		</script>
		<?php
		}
	}

	function add_admin_js()
	{
		wp_deregister_script(array('AjexFileManager')); 
		wp_enqueue_script('ckeditor', $this->ckeditor_path . 'ckeditor.js');
		wp_enqueue_script('AjexFileManager', $this->plugin_path . 'AjexFileManager/ajex.js', array('thickbox'), '20080710');
	}
	function can_upload()
	{
		if ((function_exists('current_user_can') && current_user_can('upload_files')) || (isset($user_level) && $user_level >= 3))
		{
			return true;
		}
		return false;
	}
}
$ckeditor = new ckeditor();
?>