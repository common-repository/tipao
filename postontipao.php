<?php
/*
Plugin Name: Tipao
Version: 1.0.0
Description: Un plugin pour pouvoir poster sur Tipao.
Contributors:
Author: Stephane Charvolin
Author URI:
Plugin URI: http://tipao.com/
Donate Link:
Update Server:
Disclaimer: No warranty is provided. PHP 5 and Curl are required.
Requires at least: 2.7
Tested up to: 1.0.0
Stable tag: 1.0.0
License: GPLv2
Text Domain:
Domain Path: /lang
*/
   
	define('TP_FILE_LOC', '?page=' . '/' . basename(__FILE__));
	if (! class_exists ( 'tipao' )) {
	// let class begin
	class tipao {
		//localization domain
		var $plugin_domain = 'postontipao';
		var $plugin_url;
		var $db_option = 'postontipao_options';
		var $cl_version = 281.3;
		var $api_url;
		var $test = false;
		
		
		//initialize the plugin
		function tipao() {
			global $wp_version, $pagenow;
			// pages where Postontipao needs translation
			$local_pages = array ('plugins.php', 'postontipao.php' );
			// check if translation needed on current page
			if (in_array ( $pagenow, $local_pages ) || in_array ( $_GET ['page'], $local_pages )) {
				$this->handle_load_domain ();
			}
			$exit_msg = __ ( 'tipao requires Wordpress 2.9.2 or newer.', $this->plugin_domain ) . '<a href="http://codex.wordpress.org/Upgrading_Wordpress">' . __ ( 'Please Update!', $this->plugin_domain ) . '</a>';

			// can you dig it?
			if (version_compare ( $wp_version, "2.9.2", "<" )) {
				echo ( $exit_msg ); // no diggedy
			}
			// check if update changes needed
			$installed_version = get_option('cl_version');
			if(!$installed_version || $installed_version < $this->cl_version){
				update_option('cl_version',$this->cl_version);
				$options = $this->get_options();
				update_option($this->db_option,$options);
			}

			// action hooks
			$this->plugin_url = trailingslashit ( WP_PLUGIN_URL . '/' . dirname ( plugin_basename ( __FILE__ ) ) ); //important!
			if($this->test){
				$this->api_url = 'http://tipao.com/wp-api/';	
			} 
	
			add_action ( 'admin_menu', array (&$this, 'admin_menu' ) ); // add image to admin link and setup options page
			add_action ( 'init',array (&$this,'my_init_method') ); // template_redirect always called when page is displayed to user
			add_action( 'init',array (&$this, 'bringbdd' ));
			add_action('admin_menu',array($this,'tp_post_options_init'));	
			add_action('admin_menu', array($this,'add_testbox'));
			add_filter ( 'plugin_action_links', array (&$this, 'postontipao_action' ), - 10, 2 ); // add a settings page link to the plugin description. use 2 for allowed vars
		}
		
	// Localization support
		function handle_load_domain() {
			// get current language
			$locale = get_locale ();

			// locate translation file
			$mofile = WP_PLUGIN_DIR . '/' . plugin_basename ( dirname ( __FILE__ ) ) . '/lang/' . $this->plugin_domain . '-' . $locale . '.mo';

			// load translation
			load_textdomain ( $this->plugin_domain, $mofile );
		}

			
		// hook the options page
		function admin_menu() {
			$options = $this->get_options ();
			//$menutitle = '<img src="' . $this->plugin_url . 'images/menu.gif" alt=""/> ';
			$menutitle .= 'Tipao'.'<input type="hidden" id="extentionact" value="'.$options["Extensionon"].'"/>';
			add_options_page ( 'Options Tipao', $menutitle, 8, basename ( __FILE__ ), array (&$this, 'handle_options' ) );
			//return '<input type="hidden" id="extentionact" value="'.$options["Extensionon"].'"/>';	
		}
		
	
		function my_init_method() {
		
    if (is_admin()) {
        wp_deregister_script( 'tipaooptions' );
        wp_deregister_script( 'tipaoposter' );
        wp_register_script( 'tipaooptions',  $this->plugin_url . 'js/postontipao.js');
        wp_register_script( 'tipaoposter',  $this->plugin_url . 'js/postersurtipao.js');
    
     	 wp_enqueue_script( 'tipaoposter' );
        wp_enqueue_script( 'tipaooptions' );
    }
}    

	// add the settings link
		function postontipao_action($links, $file) {
			$this_plugin = plugin_basename ( __FILE__ );
			if ($file == $this_plugin) {
				$links [] = "<a href='options-general.php?page=postontipao.php'>" .  __ ( 'Parametres', $this->plugin_domain ) . "</a>";
			}
			return $links;
		}
		
		
		
function showmetaboxtipao() {


$options = $this->get_options ();
//$options ['badge'] = $this->plugin_url . 'images/' . $options ['badge'];
	
 echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
 echo'<input type="hidden" id="loginbdd" value="'.$options["name"].'"/>';
 echo'<input type="hidden" id="mdpbdd" value="'.$options["mdp"].'"/>';
 echo'<input type="hidden" id="idtownbdd" value="'.$options["idtown"].'"/>';
 echo'<input type="hidden" id="messagetipao" value="'.$options["messagetipao"].'"/>';
 
 
 if ($options ['town'] == 'Citybydefault')
	 {
 			echo '<strong>'; 
 			_e('Connectez vous sur votre compte Tipao ici ==> <a href="../wp-admin/options-general.php?page=postontipao.php">Reglages &gt; Tipao </a>');
 			echo '</strong>';
	 }

 else {
 		echo'<p style= font-size:18px; align="center"><strong>';
 		 _e('Bienvenue sur le Plugin de Tipao');
 		echo '</strong></p>';
	    echo '<table style="margin:left" cellpadding=5 cellspacing=10>';
	//	echo 		'<tr><td><img src = "'.$options ['badge'].'"/></td>';	
 		echo 		'<td width="150" align="right"></td><td><strong>  Bonjour ' .$options ['name'].', O&ugrave; voulez vous postez aujourd\'hui?</strong></td>';
 		echo 		'<td width="200" align="right">Poster sur la ville de : </td><td id="changerville" style="color:blue"><b>' .$options ['town'].'</b></tr>';
		$auto_on = $options ['auto_on'] == 'on' ? 'checked' : '';
 		echo  '<td></td><td align="left">Cat&eacute;gorie: <select id="idcateg"><option value="0">Actualit&eacute;</option><option value="1">Art & Culture</option><option value="2">Sport</option><option value="3">Info route</option><option value="4">Fait divers</option><option value="5">Sortie & Loisirs</option><option value="6">Evenement</option></select></td><td align="right"><input style="color:blue;border:1px solid blue" type ="button" id="bouttonconfirmer" value="Confirmer la ville"/><input id="changeville" type="button" value="Changer ma ville"/></td>
 				<td><input type="text" id="nouvelleville"/><img id="requeteville" src="'.$this->plugin_url.'images/tipaodl.gif" /><select  style="font-style:italic;font-size:13px;width: 120px; height:25px;" id ="resultatville"></select></td></tr> ';
		echo '</table>';	
		echo '<p align="center" id="errorville"></p>';
		echo '<p align="right" style="padding-right: 108px" id ="selecthistorique">Historique des villes : <select id="choisirvillehisto"  style="font-style:italic;font-size:13px;width: 120px; height:25px;">';
		$nb=count($options['historiqueville']);
		for(  $i=0; $i < $nb; $i++ )
		{
			echo'<option value="'.$options["historiquevilleid"][$i].'">'.$options['historiqueville'][$i].'</option>';
		}
		echo'</select><input type="button" id="confirmerhisto" value ="Confirmer la ville"/></p>';
		echo'<p style="padding-left: 170px; font-size: 12px"><br/><input id = "postersurtipao" type = "checkbox" '.$auto_on.'/>';
		echo '<label for="postersurtipao"> Poster l\'article sur Tipao ? </label></p><br>';
		echo    '<p align="center"><i>Si dans les r&eacute;glages vous n\'avez pas coch&eacute; " Poster automatiquement " alors cochez cette case </i></p>';
 	   
 	}
 	  echo'<input type="hidden" id="autobdd" value="'.$options["auto_on"].'"/>';
}		

function add_testbox() {
   add_meta_box('boxPostonTipao', 'Tipao', array (&$this, 'showmetaboxtipao' ), 'page','normal','high');
}

		
	function tp_post_options_init() {
	// Unused version check.
	add_meta_box('boxPostonTipao', 'Tipao', array (&$this, 'showmetaboxtipao' ), 'post','normal','high');
}


function bringbdd() {
	if (isset ($_GET ['login']) && isset ($_GET ['password']) && isset ($_GET ['verification']))
	{ 
		$options = $this->handle_options();
		exit;
	} 	
	
	if (isset ($_GET ['login']) && isset ($_GET ['password']) && isset ($_GET ['resetset']))
	{ 
		$options = $this->handle_options();
		exit;
	} 	
	
	if (isset($_GET ['verificationmessage'])&& isset ($_GET ['messagetipao']))
	{
				$options = $this->affichermessage();
				exit;
	}
if (isset($_GET ['verificationextention'])&& isset ($_GET ['extentionact']))
	{
				$options = $this->afficherextention();
				exit;
	}
	
	if (isset($_GET ['verificationvillehisto'])&& isset ($_GET ['historiqueville']))
	{
				$options = $this->afficherhistorique();
				exit;
	}
}
	

		// get plugin options
		function get_options() {
			// default values
			$options = array ( 'name' => '', 'mdp' => '', 'town' => 'Citybydefault','Extensionon'=>'on','historiqueville'=>array(),'historiquevilleid'=>array());
			
			
			// get saved options unless reset button was pressed
			$saved = '';
			 
			$saved = get_option ( $this->db_option );
			
			
			// assign values
			if (! empty ( $saved )) {
				foreach ( $saved as $key => $option ) {
					$options [$key] = $option;
				}
			}
			// update the options if necessary
			if ($saved != $options) {
				update_option ( $this->db_option, $options );
			}
			// return the options
			return $options;
		}

		function affichermessage()
		
		{	
				$options = $this->get_options();
				$options ['messagetipao'] = $_GET ['messagetipao'];
					update_option ( $this->db_option, $options );
		
		}
		
	function afficherextention()
		
		{	
				$options = $this->get_options();
				$options ['Extensionon'] = $_GET ['extentionact'];
					update_option ( $this->db_option, $options );
		
		}
		
function afficherhistorique()
		
		{	
				$options = $this->get_options();
				
			$options ['historiqueville'][]  =  $_GET ['historiqueville'];
			$options ['historiquevilleid'][]  =  $_GET ['historiquevilleid'];
			
					update_option ( $this->db_option, $options );
		}
		
		// handle saving and displaying options
		function handle_options() {
			$options = $this->get_options();
			
		if (isset ($_GET ['login']) && isset ($_GET ['password'])){


				// initialize the error class
				$errors = new WP_Error ( );

				// check security
				//check_admin_referer ( 'commentluv-nonce' );

				$options = array ();
				$options ['name'] = $_GET ['login'];
				$options ['mdp'] = $_GET ['password'];	
				$options ['auto_on'] = $_GET ['auto'];
				//$options ['badge'] = $_POST ['cl_badge'];
				$options ['town'] = $_GET ['ville'];
				$options ['idtown']= $_GET ['idtown'];
				$options ['Extensionon']= $_GET ['extentionon'];	
				
			
				// check for errors
				if (count ( $errors->errors ) > 0) {
					echo '<div class="error"><h3>';
					_e ( 'Il y a des erreurs dans vos reglages', $this->plugin_domain );
					echo '</h3>';
					foreach ( $errors->get_error_messages () as $message ) {
						echo $message;
					}
					echo '</div>';
				} else {
					//every-ting cool mon
					update_option ( $this->db_option, $options );
				}

		}
				
			$auto_on = $options ['auto_on'] == 'on' ? 'checked' : '';

			/* $badge1 = $options ['badge'] == 'Tipao.gif' ? 'checked="checked"' : '';
			$badge2 = $options ['badge'] == 'Tipao2.gif' ? 'checked="checked"' : '';
			$badge3 = $options ['badge'] == 'Tipao3.gif' ? 'checked="checked"' : '';
			$badge4 = $options ['badge'] == 'Tipao4.gif' ? 'checked="checked"' : '';
			$badge5 = $options ['badge'] == 'nothing.gif' ? 'checked="checked"' : '';
			
			*/
			
			// url for form submit
			$action_url = $_SERVER ['REQUEST_URI'];
			include ('Optionstipao.php');
			
		}
			
	function desinstall() {
	
	delete_option('postontipao_options');
}

		// set up default values
		function install() {
			// set default options
			$this->get_options ();
		}
		
	}
}

		
		
		// start Postontipao class engines
if (class_exists ( 'tipao' )) :
$tipao = new tipao ( );

// confirm warp capability
if (isset ( $tipao )) {
	// engage
	register_activation_hook ( __FILE__, array (&$tipao, 'install' ) );
}

register_deactivation_hook(__FILE__, array (&$tipao,'desinstall'));
endif;

	
function htmlspecialchars_decod($string,$style=ENT_COMPAT)
{
	$translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS,$style));
	if($style === ENT_QUOTES){ $translation['&#039;'] = '\''; }
	return strtr($string,$translation);
}?>