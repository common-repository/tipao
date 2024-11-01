		
jQuery(document).ready( function() {
		
	jQuery("#error").hide();
	jQuery("#requete").hide();
	
	//Message si extention activée
	if (jQuery("#extentionact").val()=="on") {
		jQuery("#message").html( '<p>Extension <b>activ&eacute;e</b>. Reglez vos parametres sur ==> <a href="../wp-admin/options-general.php?page=postontipao.php">Reglages &gt; Tipao </a></p>');
		jQuery.get("../wp-admin/options-general.php?page=postontipao.php",
				  { 
				   extentionact: "off", 
				   verificationextention : "ok"
				  }
				 );
			}

	if(jQuery("#answer").val() == "Citybydefault")  
		{
		jQuery("#answer").hide(), 
		jQuery("#labcity").hide();
		}
	
	function logOnTipaoAPI() 
	{	//envoi la requete en POST a l'api et recupere un json
		jQuery("#requete").show();
		jQuery.getJSON("../wp-content/plugins/tipao/valider.php", 
				{ login: jQuery("#tipao_login").val(), password: jQuery("#tipao_pwd").val() },
				   function(data){
				
						if (!data.error){
							//Si c'est bon pour la connexion alors affiche la ville
							jQuery("#requete").hide();
							jQuery("#error").hide();
							jQuery("#answer").val(data.city_name);
							
							//enregistre dans la bd de wordpress les bon elements					

							if(jQuery("#cl_auto_on").is(':checked'))  
								jQuery("#cl_auto_on").val('on');
							else
								jQuery("#cl_auto_on").val('off');
							
							jQuery("#answer").show(); 
							jQuery("#labcity").show();

							 jQuery.get("../wp-admin/options-general.php?page=postontipao.php",
							  { 
							   login: jQuery("#tipao_login").val(), 
							   password: jQuery("#tipao_pwd").val(), 
							   ville : jQuery("#answer").val(),
							   auto : jQuery("#cl_auto_on").val(),
							   idtown: data.city_id ,
							   verification : "ok" 
							  }
							 );
							 
							 jQuery("#confirmtipao").html('<p>Les r&eacute;glages ont &eacute;t&eacute; sauvegard&eacute;s.</p>');
	
							
							
						}
					    else{
					    	jQuery("#requete").hide();
					    	jQuery("#error").show();
					    	jQuery("#error").html('<span style="padding-right:100px ; color:red">'+ data.error +'</span>') ;
					    	jQuery("#tipao_pwd").focus();

						
					    }
					   
				   }
		);
	}	
		
	jQuery("#validate").click(logOnTipaoAPI);
	
	jQuery(window).keypress(function(e) {
	    if(e.keyCode == 13) {
	    jQuery(logOnTipaoAPI);
	    }
	});

	
	function ResetSettings() 
	{	//Reset les options 
		
		deflog='Nom de Compte';
		defpass='None';
		defville='Citybydefault';
		defextentionon='off';
			
		jQuery("#tipao_login").val("");
		jQuery("#tipao_pwd").val("");
		jQuery("#answer").val(defville);
		jQuery("#cl_auto_on").removeAttr('checked');
		jQuery("#answer").hide();
		jQuery("#labcity").hide();
		jQuery("#error").hide();
		jQuery("#confirmtipao").html('<p>Les r&eacute;glages ont &eacute;t&eacute; r&eacute;initialis&eacute;.</p>');
		
		jQuery.get("../wp-admin/options-general.php?page=postontipao.php", 
				{ 	login: deflog,
					password: defpass,
					ville: defville,
					extentionon: defextentionon,
					resetset : "ok" 
				}
		
					);
	}	
	jQuery("#resetall").click(ResetSettings);
	
	
});
