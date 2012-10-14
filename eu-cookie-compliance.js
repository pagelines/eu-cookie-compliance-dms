// Version 2.0 by Ryan Varley (http://ryanvarley.co.uk)
// For use only with licensed versions of Eu Cookie Compliance for PageLines
// If you have bought a licence you may modify and use it how you wish but not sell it
euccObj=new Object();
function EuccSetCookie(c_name,value,exdays)
{
var exdate=new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
document.cookie=c_name + "=" + c_value;
}

function EuccGetCookie(c_name)
{
var i,x,y,ARRcookies=document.cookie.split(";");
for (i=0;i<ARRcookies.length;i++)
{
  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
  x=x.replace(/^\s+|\s+$/g,"");
  if (x==c_name)
    {
    return unescape(y);
    }
  }
}
function euccCheckCookie(requireAccept){
    euccObj.requireAccept = requireAccept;
    var cookieconsent=EuccGetCookie("eucookiecompliance");
    
    if (euccObj.devMode == "on") {cookieconsent = 'DevMode';}

    if (cookieconsent == 'implied' || cookieconsent == 'accepted'){
            jQuery("#eu-cookie-compliance").remove();
        }
    else 
    {
        jQuery("#eu-cookie-compliance").show();
        euccGetVariables();
        euccSetWidth();
        euccPosition(euccObj.euccPos);
        euccTheme(euccObj.euccTheme);
        
        if (requireAccept == 'implied' && euccObj.devMode != "on"){
        EuccSetCookie("eucookiecompliance",'implied',365);
        }
    }
    
}
function euccGetVariables() {
    euccObj.containerWidth = parseInt(jQuery(".eucc-container").width());
    euccObj.closeIconWidth = parseInt(jQuery(".eucc-closeicon").width());
    euccObj.paraWidth = parseInt(jQuery(".eucc-p").width());
    euccObj.newParaWidth = parseInt(euccObj.containerWidth - euccObj.closeIconWidth -12); //20 is the padding
    euccObj.iconHeight = parseInt(jQuery(".eucc-closeicon").height());
    euccObj.iconMargin = parseInt((jQuery(".eucc-container").height() - euccObj.iconHeight)/2);
    euccObj.sectionHeight = parseInt(jQuery("#eu-cookie-compliance").height());
    euccObj.fixedNavbar = false;
    euccObj.bottomMargin = false;
}

function euccCloseBanner() {
    
    jQuery("#eu-cookie-compliance").remove();
    
    if (euccObj.fixedNavbar) {jQuery("#navbar.fixed-top").css('margin-top',0);}
    if (euccObj.bottomMargin) {jQuery("#site").css('margin-bottom',0);}
    
    if (euccObj.requireAccept == "acceptance"){EuccSetCookie("eucookiecompliance",'accepted',365);}
    
}

/* code to hide the notice and save cookie if acceptance needed */
jQuery(document).ready(function() {
    jQuery(".eucc-closeicon").click(function() {euccCloseBanner();});
});

// call the following from the other function

function euccSetWidth(){
    
    if (euccObj.newParaWidth < euccObj.paraWidth){ jQuery(".eucc-p").width(euccObj.newParaWidth); }
    
    jQuery(".eucc-closeicon").css('margin-top',euccObj.iconMargin + "px");
    }
    
function euccPosition(euccPos){
    
    if(euccPos == "top-float" || euccPos == "bottom-float") {
        
        euccObj.fullWidth = true;
        
        jQuery("#eu-cookie-compliance").addClass("eucc-float eucc-bgcolor"); // eucc-bgcolor is normal theme
        jQuery(".eucc-container").removeClass("eucc-bgcolor"); // remove the inner class bg
        
        if(euccPos == "top-float"){
            if (jQuery("#navbar.fixed-top").length ) {
                jQuery("#navbar.fixed-top").css('margin-top',euccObj.sectionHeight + "px"); 
                euccObj.fixedNavbar = true;
                }
            jQuery('#site').prepend(jQuery('#eu-cookie-compliance'));
        }
        if(euccPos == "bottom-float"){
            jQuery("#eu-cookie-compliance").addClass("eucc-float-bottom");
            jQuery("#site").css('margin-bottom',euccObj.sectionHeight + "px");
            euccObj.bottomMargin = true;
        }
        
         
    }
    else {euccObj.fullWidth = false;}
    
    
}

function euccTheme(euccTheme){
    
    // a function or global to return weather float or not so the right class can be selected 
    if (euccObj.fullWidth) {addClassName = "#eu-cookie-compliance";}
    else {addClassName = ".eucc-container";}

    if(euccTheme != 'std-flat'){
    jQuery(".eucc-container").removeClass("eucc-bgcolor");
    }

    if(euccTheme == 'std-trans'){
        jQuery(addClassName).addClass("eucc-trans");
    }

    if(euccTheme == 'navbar-black'){
        jQuery(addClassName).addClass("eucc-navbar eucc-black");
    }
    if(euccTheme == 'navbar-grey'){
        jQuery(addClassName).addClass("eucc-navbar eucc-grey");
    }
    if(euccTheme == 'navbar-orange'){
        jQuery(addClassName).addClass("eucc-navbar eucc-orange");
    }
    if(euccTheme == 'navbar-blue'){
        jQuery(addClassName).addClass("eucc-navbar eucc-blue");
    }
    if(euccTheme == 'navbar-red'){
        jQuery(addClassName).addClass("eucc-navbar eucc-red");
    }
}