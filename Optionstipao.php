<?php
/**
 * Tipao is a plugin for WordPress 2.7 - 1.0 and WordPress MU.
 * Copyright 2010 - 2011  Charvolin stephane
 */

/**
 * This file is required for user-control of the Tipao options.
 */


global $wpdb;
if(!$wpdb){
	// not run from wordpress
	?>
	<h1>Tipao </h1>
	<p>This is the settings page for Tipao and should not be viewed outside of the Wordpress dashboard</p>
	<p>You can download the latest version version of Tipao at Wordpress.org</p>
	<?php	exit;
}

?>

 <STYLE type="text/css">
      TABLE  {  
    padding-left: 30px;
    border-collapse: separate;
    clear: both;
    margin-bottom: -8px;
    margin-top: 1em;
    width: 35%;
    padding-right: 120px;
    
    }
    TD,input{
    font-size: 11px;
    font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
    }
  .sub
{
border: 1px solid #D2D2D2;
	-moz-box-shadow: 0 0 30px #030303;
	-webkit-box-shadow: 0 0 30px #030303;
	box-shadow: 0 0 30px #030303;
	-moz-border-radius : 5px 5px 5px 5px;
-webkit-border-radius : 5px 5px 5px 5px;

}
    </STYLE>
	<div id="confirmtipao" class="updated fade">
	</div>
	<div align="center">
		<h1><?php _e('R&eacute;glage des Param&egrave;tres de Connexion sur Tipao',$this->plugin_domain);?></h1>
					<small><?php _e('Afin de pouvoir poster &eacute;galement votre article sur Tipao, vous devez renseigner les identifiants de connexion de votre Compte Tipao.',$this->plugin_domain);?></small><br />
					</div>
					<br>
					<div align="center" >
					<img src="<?php echo $this->plugin_url;?>images/imageoption.png" style="margin-bottom: -110px; padding-right:750px; margin-top: 30px;" /> 

					<table>
  					<tbody>
  						<tr>
    					<td><?php _e('Identifiant ou adresse mail',$this->plugin_domain);?></td>
    					<td><input type="text" value="<?php echo $options ['name'];?>" name="cl_name" id= "tipao_login"/></td>
  						</tr>
  						<tr>
    					<td><?php _e('Mot de passe',$this->plugin_domain);?></td>
    					<td salign="left"><input value="<?php echo $options ['mdp'];?>" type="password" name="cl_mdp" id = "tipao_pwd"/></td></tr>
  						<tr><td id="labcity"> <?php _e('Votre ville par d&eacute;faut est :',$this->plugin_domain);?></td>
  						<td ><input style="color:blue; font-weight: bold;" type="text"  value="<?php echo $options ['town'];?>" name="tp_town" id="answer" disabled="disabled"/></td></tr>
  						<tr height="20"></tr>
  						<td><input id="cl_auto_on" type="checkbox" name="cl_auto_on" <?php echo $auto_on;?>/>
						<label for="cl_auto_on"><?php _e('Poster automatiquement ?',$this->plugin_domain);?></label></td>
  						
						</tbody></table>
						</div>
					
						<br/>
						<br/>
						
			<!-- 		
			
				<h2><?php _e('Display Badge',$this->plugin_domain);?></h2>
					<table class="form-table">
					<tr>
      					<td><?php _e('Choose badge to display','Postontipao')?> </td>
      					
     
   
     <td><label><input type="radio" <?php echo $badge1; ?> name="cl_badge" value="Tipao.gif"><img src="<?php echo $this->plugin_url;?>images/Tipao.gif"/></label></td>
        <td><label><input type="radio" <?php echo $badge2; ?> name="cl_badge" value="Tipao2.gif"><img src="<?php echo $this->plugin_url;?>images/Tipao2.gif"/></label></td>
		<td><label><input type="radio" <?php echo $badge3; ?> name="cl_badge" value="Tipao3.gif"><img src="<?php echo $this->plugin_url;?>images/Tipao3.gif"/></label></td>
		<td><label><input type="radio" <?php echo $badge4; ?> name="cl_badge" value="Tipao4.gif"><img src="<?php echo $this->plugin_url;?>images/Tipao4.gif"/></label></td>
		<td><label><input type="radio" <?php echo $badge5; ?> name="cl_badge" value="nothing.gif"><?php _e('Show nothing',$this->plugin_domain);?></label></td>
  </tr>
  
   -->
  
  
  </table>
		
					<p align="center" id="error" </p>	
					<div align="center" class="submit">
					
					<table>
					<td>
					<input style = "width: 150px" type="button" name="Submit" value="Mettre &agrave; jour" id = "validate"/></div>
				
				</td>
				<td style = " padding-bottom: 20px;">
	
		
		<?php _e('Remet les Options par defaut',$this->plugin_domain);?>

					<?php $javamsg =  __('Voulez vous remettre vos options par defaut? Appuyez sur OK pour continuer',$this->plugin_domain);?>
					<input onclick="<?php echo 'if(confirm(\''.$javamsg.'\') != true) { return false; } else { return true; } ';?>" style = "width:150px;" type="button" name="reset" value="R&eacute;initialisez les Options" id = "resetall"/></div>
					</td>
					</table>
					<p id="requete" style="width: 280px; padding-right:70px;"><img src="<?php echo $this->plugin_url;?>images/tipaodl.gif" />    Requete en cours, veuillez patienter.</p>
					</div>
		</form>

<?php // end ?>