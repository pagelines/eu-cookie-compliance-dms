<?php
/*
	Section: EU Cookie Compliance
	Author: Ryan Varley
	Author URI: http://ryanvarley.co.uk
	Description: Displays a banner to new visitors about your sites use of cookies.
	Class Name: EUCookieCompliance
	Workswith: templates, main, header, morefoot, sidebar1, sidebar2, sidebar_wrap
	Compatibility: 2.1+
	Version: 1.0.1
	External: http://ryanvarley.co.uk/projects/pagelines/eu-cookie-compliance/
	Demo: http://demo.ryanvarley.co.uk/eu-cookie-compliance/
	Cloning: false
*/

class EUCookieCompliance extends PageLinesSection {    

    function section_head(){ # Load the javascript and any associated variables.
    $euccDevMode = ( ploption('eucc_DevMode', $this->oset) ) ? ploption('eucc_DevMode', $this->oset) : false;
            if(!$euccDevMode){ ?> <script type='text/javascript' src='<?php echo $this->base_url;?>/eu-cookie-compliance.js?ver=1.0.1'></script><?php }
        }

    function section_template( $clone_id = null ) {
	
		//set variables for ease
        $euccBoxText = ( ploption('eucc_BoxText', $this->oset) ) ? ploption('eucc_BoxText', $this->oset) : false;
        $euccPrivacyPolicyLink = ( ploption('eucc_PrivacyPolicyLink', $this->oset) ) ? ploption('eucc_PrivacyPolicyLink', $this->oset) : false;
        $euccCloseButtonImage = ( ploption('eucc_CloseButtonImage', $this->oset) ) ? ploption('eucc_CloseButtonImage', $this->oset) : false;
        $euccAcceptMode = ( ploption('eucc_RequireAccept', $this->oset) ) ? ploption('eucc_RequireAccept', $this->oset) : 'implied';
        $euccDevMode = ( ploption('eucc_DevMode', $this->oset) ) ? ploption('eucc_DevMode', $this->oset) : false;
		$euccAcceptButtonText = ( ploption('eucc_AcceptButtonText', $this->oset) ) ? ploption('eucc_AcceptButtonText', $this->oset) : "I Accept";
		$euccButtonPosition = ( ploption('eucc_ButtonPosition', $this->oset) ) ? ploption('eucc_ButtonPosition', $this->oset) : "top-right";
		
		// If needed values arent set then notify user
        if(!$euccPrivacyPolicyLink && !$euccBoxText){ echo setup_section_notify( $this, 'If your using the default text you must set a link to your cookie information page' ); return;}
		
		// Start section
		
		// define the close button (or trigger generation later)
        $euccCustomCloseButton = true;
  
        if(!$euccCloseButtonImage){
        $euccCloseButtonImage = $this->base_url.'/close.png';
        $euccCustomCloseButton = false;
        }
        
		// a switch for later
        if($euccAcceptMode == 'acceptance'){ $euccRequireAccept = true; } else{ $euccRequireAccept = false;}
		
		// add a class for the bottom-center layout mode
		if ($euccButtonPosition == 'bottom-center'){$euccExtraButtonClass ='eucc-ownline';}
		else {$euccExtraButtonClass ='';}
      
	  
		// set the close button variable depending on options
		
		//
		//if ($euccButtonPosition = 'center-right') {$euccCloseButton = '<span class="eucc-hidebutton eucc-center-right" style="background-image:url(\''.$euccCloseButtonImage.'\');"></span>';}
        if ($euccRequireAccept && !$euccCustomCloseButton) {$euccCloseButton = '<span class="eucc-accept-cookies eucc-hidebutton '.$euccExtraButtonClass.'">'.$euccAcceptButtonText.'</span>';}
        else{ $euccCloseButton = '<span class="eucc-hidebutton '.$euccExtraButtonClass.'"><img class="eucc-closeicon" src="'.$euccCloseButtonImage.'"/></span>'; } // if implied or custom button output image
        
        // start output
        ?><p><?php
		
		if($euccButtonPosition == 'top-right' || $euccButtonPosition == 'center-right') {echo $euccCloseButton;}
		
        if ($euccBoxText){
            printf($euccBoxText);
        }
        else{
            ?>
            This site uses cookies. By continuing to browse the site you are agreeing to our use of cookies. <a href="<?php echo $euccPrivacyPolicyLink; ?>">Find out more here.</a><?php
        }
		if($euccButtonPosition == 'bottom-right' || $euccButtonPosition == 'bottom-center') {echo $euccCloseButton;}
         ?></p>
        
        <?php if(!$euccDevMode){ ?><script type='text/javascript'>EuccCheckCookie('<?php echo $euccAcceptMode; ?>');</script><?php } //output script variable
    }
    
	
    function section_optionator( $settings ){
            
            $settings = wp_parse_args($settings, $this->optionator_default);
            
            $opt_array = array(
                'eucc_BoxText'     => array(
                    'type'             => 'textarea',
                    'inputlabel'    => 'Box Text',
                    'title'         => 'The text to be displayed (optional)',
                    'shortexp'        => 'Replace the text displayed in the message. Note: doing this renders the privacy policy link obsolete. You should include your own link with HTML.',
                ),
                'eucc_PrivacyPolicyLink'     => array(
                    'type'             => 'text',
                    'inputlabel'    => 'link (with http://)',
                    'title'         => 'Cookie information Link',
                    'shortexp'        => 'The link to your privacy policy or page where you fully describe your use and the implication of cookies (used with the default text)',
                ),
                'eucc_CloseButtonImage'     => array(
                    'type'             => 'image_upload',
                    'inputlabel'    => 'close/hide image',
                    'title'         => 'Replace the close button with your own image (optional)',
                    'shortexp'        => 'This will be used for the visitor to hide the message (implied consent) or replace the accept button (acceptance). Unless your message spans multiple lines the max size is 23px in height',
                ),
                'eucc_RequireAccept'     => array(
                    'type'             => 'radio',
                    'inputlabel'    => 'Mode',
                    'title'         => 'Require user to accept to hide or take implied consent',
                    'default'    => 'implied_consent',
                    'selectvalues'    => array(
                                'implied'    => array('name' => 'Implied consent (default)'),
                                'acceptance'    => array('name' => 'Required consent'), 
                                        ), 
                    'shortexp'        => 'You can either show the banner once and if the user takes no action, assume implied consent or you can require the user actually consent to remove the banner.',
                ),
				'eucc_ButtonPosition' => array(
					'default' 		=> 'top-right',
					'type' 			=> 'radio',
					'selectvalues' => array(
						'top-right' 		=> array('name' => 'Top Right (default)'),
						//'center-right' 		=> array('name' => 'Center Right'), # future
						'bottom-right' 		=> array('name' => 'Bottom Right'),
						'bottom-center' 		=> array('name' => 'Bottom Center'),						
					),
					'inputlabel' 	=> 'Position',
					'title' 		=> 'Button Position',
					'shortexp' 		=> 'Choose where you want the button to display on your banner (this mainly effects multi-line banners)',
				),
				'eucc_AcceptButtonText'     => array(
                    'type'             => 'text',
                    'inputlabel'    => 'Accept button text',
                    'title'         => 'Accept button text (optional)',
                    'shortexp'        => 'The text that will display on the accept button when the \'acceptance\' mode has been selected',
                ),
                'eucc_DevMode'     => array(
                    'type'             => 'check',
                    'inputlabel'    => 'Enabled',
                    'title'         => 'Dev Mode',
                    'shortexp'        => 'This will stop the banner from being permanently hidden for testing purposes. This affects ALL users and you should turn it off before deployment',
                )
            );

            $settings = array(
                'id'         => $this->id.'_meta',
                'name'         => $this->name,
                'icon'         => $this->icon, 
                'clone_id'    => $settings['clone_id'], 
                'active'    => $settings['active']
            );

            register_metatab($settings, $opt_array);
        }
        
    }