<?php
/*
    Section: EU Cookie Compliance
    Author: Ryan Varley
    Author URI: http://ryanvarley.co.uk
    Description: Displays a banner to new visitors about your sites use of cookies.
    Class Name: EUCookieCompliance
    Workswith: templates, main, header, morefoot, sidebar1, sidebar2, sidebar_wrap
    Compatibility: 2.2.5+
    Version: 2.0
    External: http://ryanvarley.co.uk/projects/pagelines/eu-cookie-compliance/
    Demo: http://demo.ryanvarley.co.uk/eu-cookie-compliance/
    Cloning: false
*/

class EUCookieCompliance extends PageLinesSection {

    // function section_persistent(){
			// build_passive_section(array('sid' => $this->class_name));
			
			// add_action('pagelines_before_page', array(&$this,'passive_section_template'), 9, 2);
		// }
    

    function section_head(){ # Load the javascript and any associated variables.
    
        $devMode = ( ploption('eucc_DevMode', $this->oset) ) ? ploption('eucc_DevMode', $this->oset) : false;
        
        $customJs = ''; // initialise
        
        // perhaps include in validate variables function which alerts the user to problems?
        if($devMode != 'True' || $devMode != 'False'){$devMode == 'False'; }
        $customJs .= sprintf("var EuccDevMode = '%s';",$devMode);
        
        // Output user set JS variables and the header script
        printf("<script type='text/javascript'>
        %s
        </script>
        <script type='text/javascript' src='%s/eu-cookie-compliance.js?ver=2.0'></script>
        ",$customJs,$this->base_url);
    }

    function section_template( $clone_id = null ) {
    
        // Some Defaults + initials
        $privacyPolicyLink = ( ploption('eucc_PrivacyPolicyLink', $this->oset) ) ? ploption('eucc_PrivacyPolicyLink', $this->oset) : false;
        
        $defaultText = "This site uses cookies. By continuing to browse the site you are agreeing to our use of cookies. <a href=".$privacyPolicyLink.">Find out more here.</a>";
    
        // set variables for ease
        $boxText = ( ploption('eucc_BoxText', $this->oset) ) ? ploption('eucc_BoxText', $this->oset) : $defaultText;
        $closeButtonImage = ( ploption('eucc_CloseButtonImage', $this->oset) ) ? ploption('eucc_CloseButtonImage', $this->oset) : false;
        $acceptMode = ( ploption('eucc_RequireAccept', $this->oset) ) ? ploption('eucc_RequireAccept', $this->oset) : 'implied';
        $devMode = ( ploption('eucc_DevMode', $this->oset) ) ? ploption('eucc_DevMode', $this->oset) : false;
        $acceptButtonText = ( ploption('eucc_AcceptButtonText', $this->oset) ) ? ploption('eucc_AcceptButtonText', $this->oset) : "I Accept";

        $bannerPosition = ( ploption('eucc_BannerPosition', $this->oset) ) ? ploption('eucc_BannerPosition', $this->oset) : "normal";
        $customJs = '';
        $bannerTheme = ( ploption('eucc_BannerTheme', $this->oset) ) ? ploption('eucc_BannerTheme', $this->oset) : "navbar-black";
        $customJs = '';
        
        // If needed values arent set then notify user
        if(!$privacyPolicyLink && !$boxText){ echo setup_section_notify( $this, 'If your using the default text you must set a link to your cookie information page' ); return;}
        
        // some validation of button positoning
        
    // Start section code
        
        // Generate Close Button
        if ($acceptMode == 'acceptance'){ $closeIcon = sprintf('<a class="btn btn-success btn-mini">%s</a>',$acceptButtonText); }
        else { $closeIcon = '<i class="icon-remove"></i>'; } // another option if pagelines < 2.3 - perhaps a filter for legacy?
        
        // final output
        printf('
        <div class="eucc-container eucc-bgcolor">
            <p class="eucc-p">%s</p>  <span class="eucc-closeicon">  %s  </span>
        </div>
        ',$boxText, $closeIcon);
        
        // after section js
        
        if($bannerPosition == 'top-float' || $bannerPosition == 'bottom-float') {
            $customJs .= sprintf("euccPosition('%s');",$bannerPosition);
            }
        
        if($bannerTheme) {
            $customJs .= sprintf("euccTheme('%s');",$bannerTheme);
            }    
        
        
        // Output user set JS variables
        printf("<script type='text/javascript'>
        euccCheckCookie('%s');
        %s
        euccSetWidth();
        </script>
        ",$acceptMode,$customJs);
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
                'eucc_BannerPosition' => array(
                    'default'         => 'normal',
                    'type'             => 'radio',
                    'selectvalues' => array(
                        'normal'         => array('name' => 'As a Section (normal)'),
                        'top-float'         => array('name' => 'Top Float'),
                        'bottom-float'         => array('name' => 'Bottom Float'),                        
                    ),
                    'inputlabel'     => 'Position',
                    'title'         => 'Banner Position',
                    'shortexp'         => 'Either contain the banner to its section or allow it to float on the top or bottom of the page.',
                ),
                'eucc_AcceptButtonText'     => array(
                    'type'             => 'text',
                    'inputlabel'    => 'Accept button text',
                    'title'         => 'Accept button text (optional)',
                    'shortexp'        => 'The text that will display on the accept button when the \'acceptance\' mode has been selected',
                ),
                'eucc_BannerTheme' => array(
                    'default' => 'std-trans',
                    'title' => 'Banner Theme',
                    'shortexp' => 'standard themes match your theme colour - the others are Styled like the NavBar Section', 'eucc_section',
                    'type' => 'select',
                    'selectvalues' => array(
                        'std-flat'             => array('name' => __( 'Standard Flat (default)', 'eucc_section' ) ),
                        'std-trans'         => array('name' => __( 'Standard gradient', 'eucc_section' ) ),                       
                        'navbar-black'         => array('name' => __( 'NavBar Black Trans', 'eucc_section' ) ),
                        'navbar-grey'             => array('name' => __( 'NavBar Light Grey', 'eucc_section' ) ),
                        'navbar-orange'         => array('name' => __( 'NavBar Orange', 'eucc_section' ) ),
                        'navbar-blue'         => array('name' => __( 'NavBar Blue', 'eucc_section' ) ), 
                        'navbar-red'         => array('name' => __( 'NavBar Red', 'eucc_section' ) ),                         
                    ),
                ),
                'eucc_DevMode'     => array(
                    'type'             => 'check',
                    'inputlabel'    => 'Enabled',
                    'title'         => 'Dev Mode',
                    'shortexp'        => 'This will stop the banner from being permanently hidden for testing purposes. This affects ALL users and you should TURN IT OFF before deployment or they will see the banner on every page!.',
                )
            );

            $settings = array(
                'id'         => $this->id.'_meta',
                'name'         => 'EU Cookie',
                'icon'         => $this->icon, 
                'clone_id'    => $settings['clone_id'], 
                'active'    => $settings['active']
            );

            register_metatab($settings, $opt_array);
        }
        
    }