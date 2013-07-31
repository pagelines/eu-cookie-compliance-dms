<?php
/*
	Section: EU Cookie Compliance
	Author: Ryan Varley
	Author URI: http://ryanvarley.co.uk
	Description: Displays a banner to new visitors about your sites use of cookies.
	Class Name: EUCookieCompliance
	Workswith: templates, main, header, morefoot, sidebar1, sidebar2, sidebar_wrap
	Compatibility: 2.1+
	Version: 1.0
	External: http://ryanvarley.co.uk/projects/pagelines/eu-cookie-compliance/
	Demo: http://demo.ryanvarley.co.uk/eu-cookie-compliance/
	Cloning: false
    V3: true
*/

class EUCookieCompliance extends PageLinesSection {    

    function section_head(){ # Load the javascript and any associated variables.
    $euccDevMode = ( $this->opt('eucc_DevMode', $this->oset) ) ? $this->opt('eucc_DevMode', $this->oset) : false;
            if(!$euccDevMode){ ?> <script type='text/javascript' src='<?php echo $this->base_url;?>/eu-cookie-compliance.js'></script><?php }
        }

    function section_template() {
	
		//set variables for ease
        $euccBoxText = ( $this->opt('eucc_BoxText', $this->oset) ) ? $this->opt('eucc_BoxText', $this->oset) : false;
        $euccPrivacyPolicyLink = ( $this->opt('eucc_PrivacyPolicyLink', $this->oset) ) ? $this->opt('eucc_PrivacyPolicyLink', $this->oset) : false;
        $euccAcceptMode = ( $this->opt('eucc_RequireAccept', $this->oset) ) ? $this->opt('eucc_RequireAccept', $this->oset) : 'implied';
        $euccDevMode = ( $this->opt('eucc_DevMode', $this->oset) ) ? $this->opt('eucc_DevMode', $this->oset) : false;
		$euccAcceptButtonText = ( $this->opt('eucc_AcceptButtonText', $this->oset) ) ? $this->opt('eucc_AcceptButtonText', $this->oset) : "I Accept";
		
		// If needed values arent set then notify user
        if(!$euccPrivacyPolicyLink && !$euccBoxText){ echo setup_section_notify( $this, 'If your using the default text you must set a link to your cookie information page' ); return;}


		// Start section
        
		// a switch for later
        if($euccAcceptMode == 'acceptance'){ $euccRequireAccept = true; } else{ $euccRequireAccept = false;}
		


        if ($euccRequireAccept) {$euccCloseButton = '<button type="button" class="btn btn-small btn-success eucc-accept-cookies eucc-hidebutton"><span data-sync="eucc_AcceptButtonText">'.$euccAcceptButtonText.'</span></button>';}
        else{ $euccCloseButton = '<button type="button" class="close" data-dismiss="alert">&times;</button>'; } // if implied or custom button output image
        
        // start output
        ?>
        <div class="alert alert-block">
        <?php print $euccCloseButton; ?>
        <p data-sync="eucc_BoxText">
        <?php

        if ($euccBoxText){
            printf($euccBoxText);
        }
        else{
            ?>
            This site uses cookies. By continuing to browse the site you are agreeing to our use of cookies. <a href="<?php echo $euccPrivacyPolicyLink; ?>">Find out more here.</a><?php
        }
?></p></div>
        
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
                'eucc_RequireAccept'     => array(
                    'type'             => 'select',
                    'inputlabel'    => 'Mode',
                    'title'         => 'Require user to accept to hide or take implied consent',
                    'default'    => 'implied_consent',
                    'selectvalues'    => array(
                                'implied'    => array('name' => 'Implied consent (default)'),
                                'acceptance'    => array('name' => 'Required consent'), 
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