jQuery(document).ready( function() {
	var dejabdd;
	jQuery("#nouvelleville").hide();
	jQuery("#requeteville").hide();
	jQuery('#resultatville').hide();
	jQuery('#bouttonconfirmer').hide();
	jQuery('#selecthistorique').hide();
	
	
	
	//Message si post sur Tipao
	if (jQuery("#messagetipao").val()=="on") {
		jQuery("#message").html( '<p>Article publi&eacute; sur Wordpress et sur Tipao.</p>');
		jQuery.get("../wp-admin/options-general.php?page=postontipao.php",
				  { 
				   messagetipao: "off", 
				   verificationmessage : "ok"
				  }
				 );
		
			}
	jQuery('#publish').click(function(){	
		
		
		
		if (jQuery("#postersurtipao").is(':checked') && jQuery("#content_ifr").contents().find("body").html()!='<p><br mce_bogus="1"></p>' && jQuery('#publish').attr("name")=='publish')
			{
			//envoi la requete en POST a l'api et recupere un json
			jQuery("#choisirvillehisto option").each(function() {
				   if (jQuery("#idtownbdd").val()== jQuery(this).val())
						return dejabdd = 'false';			
						}
				);

				if (dejabdd!='false')
				{
				jQuery.get("../wp-admin/options-general.php?page=postontipao.php",
							{ 
							historiquevilleid: jQuery("#idtownbdd").val(),
							historiqueville: jQuery("#changerville").text(),
					    	verificationvillehisto : "ok"
							}
						  );	
				}
			try{
				jQuery.ajax({
					url: "../wp-content/plugins/tipao/valider.php",
					dataType: 'json',
					data: { login: jQuery("#loginbdd").val(), password: jQuery("#mdpbdd").val() },
					async: false,
					success: function(json){
										//success code
								var pictures = [];
								jQuery("#content_ifr").contents().find("img").each(function(index) {
									var img = Image();
									img.src = jQuery(this).attr("src");
									pictures[index] = img.src;
									
								});

										try{
											if (jQuery("#edButtonPreview").attr("class")=="active"||"active hide-if-no-js") lecontenu = jQuery("#content_ifr").contents().find("body").html() 
											else lecontenu= jQuery('#content').val();
											jQuery.ajax({
												type: 'POST',
												url: "../wp-content/plugins/tipao/poster.php",
												data: { 
													iduser : json.id, 
													ticket : json.ticket,
													idtown : jQuery("#idtownbdd").val(),
													content :jQuery("#title").val() + "<br><br>"+ lecontenu,
													idcat: jQuery("#idcateg").val(),
													image0 : pictures[0],
													image1 : pictures[1],
													image2 : pictures[2],
													image3 : pictures[3],
													image4 : pictures[4]
													
												},
												async: false,
												success: function(json){
													
													if (json.indexOf("error") != -1){
														var reg=new RegExp("(.*)\<error\>(.*)\<\/error\>.*", "g");
														var replaced = json.replace(reg,"$2");
														alert(replaced.substring(38));
														return false;
													}

													jQuery.get("../wp-admin/options-general.php?page=postontipao.php",
															  { 
															   messagetipao: "on", 
															   verificationmessage : "ok"
															  }
															 );
													
													jQuery("#postersurtipao").attr('checked', false);
													jQuery('#publish').click();
												
													
												},
												error: function(XHR,textStatus,errorThrown){
													//error code
													alert("Erreur dans le post : "+XHR+ " | "+textStatus+ " | "+errorThrown);
													return false;
												}
											});
										}
										catch (error){alert("Erreur dans le catch du post : "+error); return false;}
										return false;
					},
					
					
					error: function(XHR){
						//error code
						alert("Erreur dans la validation de l'utilisateur : "+XHR);
						return false;
					}
				});
			}
			catch (error){alert("Erreur dans le catch du validate : "+error); return false;}
			return false;

		}
		else return true;
	});
	
	function Changerville() 
	{	//requete en ajax et change la ville 
		
		if 	(jQuery("#changeville").val() == "Changer ma ville")
		 	{
			jQuery("#changeville").val('Rechercher la ville'),
			jQuery("#nouvelleville").show();
			jQuery("#nouvelleville").focus();
			if (jQuery('#choisirvillehisto option:selected').text()!="")
			{
			jQuery('#selecthistorique').show();
			jQuery('#confirmerhisto').show();
			}
		 	}
		
		
		else
			{
				jQuery("#requeteville").show();
				jQuery("#changeville").val('Changer ma ville'),
				jQuery("#nouvelleville").hide(),
					jQuery.ajax({
						type: 'POST',
						url: "../wp-content/plugins/tipao/trouverville.php",
						dataType: 'json',
						data: { ville: jQuery("#nouvelleville").val() },
						async: false,
						
						success: function(json){
							if (!json.error){
								
								if (json.city_list[1]){
									
									jQuery('#errorville').hide();
									jQuery("#requeteville").hide();
									jQuery('#bouttonconfirmer').show();
									jQuery("#changeville").hide();
									jQuery('#resultatville').show()
									for( var i=0; i < json.city_list.length; i++ ){
										jQuery('#resultatville').append('<option value="'+ json.city_list[i].geonameid +'">'+ json.city_list[i].name +'</option>');
												}
									
							}
								else{
									jQuery('#changerville').html('<b>' +json.city_list[0].name + '</b>');
									jQuery('#idtownbdd').val(json.city_list[0].geonameid);
									jQuery('#errorville').hide();
									jQuery("#requeteville").hide();
									jQuery('#selecthistorique').hide();
									}
							}	
							else
								{
								jQuery('#errorville').html('<span style="font-size:12px ;color:red">'+ json.error +'</span>');
								jQuery('#errorville').show();
								jQuery("#requeteville").hide();
								jQuery('#selecthistorique').hide();
								}
						} 
					});
				
			}
	}

	jQuery("#changeville").click(Changerville);
	
	  
	jQuery("#nouvelleville").keydown(function(event) {
	var code = (event.keyCode ? event.keyCode : event.which);
	 if(code == 13) { //Enter keycode
		   //Do something
		 jQuery(Changerville);
	 		} 
	});
	
	function Trouverville() 
	{	//requete en ajax et change la ville 
				ville = jQuery('#resultatville').val();
				jQuery("#resultatville").children();
				jQuery("#resultatville option");
				jQuery('#changerville').html('<b>' +jQuery("#resultatville option:selected").text() + '</b>');
				jQuery('#idtownbdd').val(ville);
				jQuery('#bouttonconfirmer').hide();
				jQuery('#changeville').show();
				jQuery('#resultatville').hide();
				jQuery("#resultatville option").remove();
				jQuery('#selecthistorique').hide();
				jQuery('#confirmerhisto').hide();
				
				
	}
	jQuery("#bouttonconfirmer").click(Trouverville);
	
	
	function Trouverhisto() 
	{	//requete en ajax et change la ville 
				ville = jQuery('#choisirvillehisto').val();
				jQuery("#choisirvillehisto").children();
				jQuery("#choisirvillehisto option");
				jQuery('#changerville').html('<b>' +jQuery("#choisirvillehisto option:selected").text() + '</b>');
				jQuery('#idtownbdd').val(ville);
				jQuery('#bouttonconfirmer').hide();
				jQuery('#changeville').show();
				jQuery('#resultatville').hide();
				jQuery('#errorville').hide();
				jQuery("#changeville").val('Changer ma ville');
				jQuery('#nouvelleville').hide();
				jQuery('#selecthistorique').hide();
				jQuery('#confirmerhisto').hide();
				jQuery("#resultatville option").remove();
			
	}
	jQuery("#confirmerhisto").click(Trouverhisto);

});




