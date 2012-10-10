// Version 1.0
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
var cookieconsent=EuccGetCookie("eucookiecompliance");

//if (typeof EuccDevMode != 'undefined') {
    if (cookieconsent == 'implied' || cookieconsent == 'accepted'){
        //jQuery("#eu-cookie-compliance").remove();
      }
//}
else 
  {
    jQuery("#eu-cookie-compliance").show();
    
    if (requireAccept == 'implied'){
    EuccSetCookie("eucookiecompliance",'implied',365);
    }
}

jQuery("#eu-cookie-compliance").show(); // temp


/* code to hide the notice on click */
    jQuery(".eucc-closeicon").click(function () {
    jQuery("#eu-cookie-compliance").remove();
    });
};

/* code to hide the notice and save cookie if acceptance needed */
jQuery(document).ready(function() {
    jQuery(".eucc-closeicon").click(function () {
    jQuery("#eu-cookie-compliance").remove();
    EuccSetCookie("eucookiecompliance",'accepted',365);
    });
});

// if bar isnt going to show make the following functions not run without js failing

function euccSetWidth(){
    var containerWidth = jQuery(".eucc-container").width();
    var closeIconWidth = jQuery(".eucc-closeicon").width();
    var paraWidth = jQuery(".eucc-p").width();
    var newParaWidth = containerWidth - closeIconWidth -12; //20 is the padding
    
    if (newParaWidth < paraWidth){ jQuery(".eucc-p").width(newParaWidth); }
    
    var iconHeight = jQuery(".eucc-closeicon").height()
    var iconMargin = (jQuery(".eucc-container").height() - iconHeight)/2;
    
    jQuery(".eucc-closeicon").css('margin-top',iconMargin + "px");
    
    
    // document.write('<p>cont: ' + containerWidth + '<br>icon: ' + closeIconWidth + '<br>para: ' + paraWidth 
    // + '<br>NewPara: ' + newParaWidth + '<br>marg: ' + iconMargin + '<br>iconh: ' + iconHeight + '<br>conth: ' + jQuery(".eucc-container").height()
    // + '</p> ');
    }
    
function euccPosition(euccPos){ // perhaps call in the function that shows the section
    
    if(euccPos == "top-float" || euccPos == "bottom-float") {
        jQuery("#eu-cookie-compliance").addClass("eucc-float eucc-bgcolor"); // eucc-bgcolor is normal theme
        jQuery(".eucc-container").removeClass("eucc-bgcolor"); // remove the inner class bg
        
        if(euccPos == "top-float"){floatPosition = "top";}
        if(euccPos == "bottom-float"){floatPosition = "bottom";}
        
        jQuery("#eu-cookie-compliance").css(floatPosition,0);
        
        var sectionHeight = jQuery("#eu-cookie-compliance").height()
        jQuery("body").css("margin-"+floatPosition,sectionHeight)
    }
    
    
}

function euccTheme(euccTheme){
    
    // a function or global to return weather float or not so the right class can be selected 
    
    addClassName = "#eu-cookie-compliance";
    addClassName = ".eucc-container";

    if(euccTheme != 'std-flat'){
    jQuery(".eucc-container").removeClass("eucc-bgcolor");
    }

    if(euccTheme == 'std-trans'){
        jQuery(addClassName).addClass("eucc-navbar eucc-trans");
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