<?php
/*
Section: EU Cookie Compliance
Author: Ryan Varley
Author URI: http://ryanvarley.co.uk
Version: 1.0
Description: Displays a banner to new visitors about your sites use of cookies.
Demo: http://demo.ryanvarley.co.uk/eu-cookie-compliance/
External: http://ryanvarley.co.uk/projects/pagelines/eu-cookie-compliance/
Class Name: EUCookieCompliance
Cloning: False
Workswith: templates, main, header, morefoot, sidebar1, sidebar2, sidebar_wrap
*/

class EUCookieCompliance extends PageLinesSection {    

    function section_head(){ # Load the javascript and any associated variables.
    $euccDevMode = ( ploption('eucc_DevMode', $this->oset) ) ? ploption('eucc_DevMode', $this->oset) : false;
            if(!$euccDevMode){ ?> <script type='text/javascript' src='<?php echo $this->base_url;?>/eu-cookie-compliance.js'></script><?php }
        }

    function section_template( $clone_id = null ) {
        $euccBoxText = ( ploption('eucc_BoxText', $this->oset) ) ? ploption('eucc_BoxText', $this->oset) : false;
        $euccPrivacyPolicyLink = ( ploption('eucc_PrivacyPolicyLink', $this->oset) ) ? ploption('eucc_PrivacyPolicyLink', $this->oset) : false;
        $euccCloseButtonImage = ( ploption('eucc_CloseButtonImage', $this->oset) ) ? ploption('eucc_CloseButtonImage', $this->oset) : false;
        $euccAcceptMode = ( ploption('eucc_RequireAccept', $this->oset) ) ? ploption('eucc_RequireAccept', $this->oset) : 'implied';
        $euccDevMode = ( ploption('eucc_DevMode', $this->oset) ) ? ploption('eucc_DevMode', $this->oset) : false;
		$euccAcceptButtonText = ( ploption('eucc_AcceptButtonText', $this->oset) ) ? ploption('eucc_AcceptButtonText', $this->oset) : "I Accept";
 
 
        $euccCustomCloseButton = true; //a switch for later
        
        if(!$euccCloseButtonImage){
        $euccCloseButtonImage = $this->base_url.'/close.png';
        $euccCustomCloseButton = false;
        }
        
        if($euccAcceptMode == 'acceptance'){ $euccRequireAccept = true; } else{ $euccRequireAccept = false;} //another switch
        
        // If needed values arent set then notify user
        if(!$euccPrivacyPolicyLink && !$euccBoxText){ echo setup_section_notify( $this, 'If your using the default text you must set a link to your cookie information page' ); return;}
      
        if ($euccRequireAccept && !$euccCustomCloseButton) {$euccCloseButton = '<span id="eucc-accept-cookies" class="eucc-hidebutton">'.$euccAcceptButtonText.'</span>';}
        else{ $euccCloseButton = '<span class="eucc-hidebutton"><img id="eucc-closeicon" src="'.$euccCloseButtonImage.'"/></span>'; } // if implied or custom button output image
        
        // start output
        ?><p><?php
		
        if ($euccBoxText){
            printf($euccBoxText);
        }
        else{
            ?>
            This site uses cookies. By continuing to browse the site you are agreeing to our use of cookies. <a href="<?php echo $euccPrivacyPolicyLink; ?>">Find out more here.</a><?php
        }
		echo $euccCloseButton;
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
                                'implied'    => array('name' => 'Implied consent'),
                                'acceptance'    => array('name' => 'Acceptance'), 
                                        ), 
                    'shortexp'        => 'You can either show the banner once and if the user takes no action, assume implied consent or you can require the user actually consent to remove the banner.',
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
                    'title'         => 'Make the banner always display for testing purposes',
                    'shortexp'        => 'This will stop the banner from being permanently hidden. This affects ALL users and you should turn it off before deployment',
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