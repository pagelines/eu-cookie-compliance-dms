function EuccSetCookie(c_name,value,exdays)
{
var exdate=new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
document.cookie=c_name + "=" + c_value;
}

/* Get URL arguments credit: ErickPetru, source: http://stackoverflow.com/questions/6001839/check-whether-a-url-variable-is-set-using-jquery*/
jQuery.extend({
    getUrlVars: function(){
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    },
    getUrlVar: function(name){
        return jQuery.getUrlVars()[name];
    }
});

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

function EuccDevModeOn(){
    alert(jQuery(".section-eu-cookie-compliance .alert").hasClass('eucc-devmode'));
    return jQuery(".section-eu-cookie-compliance .alert").hasClass('eucc-devmode');
}

function EuccCheckCookie(requireAccept){
    var cookieconsent=EuccGetCookie("eucookiecompliance");

    if (!EuccDevModeOn()){
        if ((cookieconsent == 'implied' || cookieconsent == 'accepted')){
            jQuery(".section-eu-cookie-compliance").remove();
          }
        else
          {
            jQuery(".section-eu-cookie-compliance").show();

            if (requireAccept == 'implied'){
            EuccSetCookie("eucookiecompliance",'implied',365);
            }
          }
    }
    else{
        jQuery(".section-eu-cookie-compliance").show();
    }

}

/* code to hide the notice and save cookie if acceptance needed */
jQuery(document).ready(function() {
	jQuery(".eucc-accept").click(function () {
	EuccSetCookie("eucookiecompliance",'accepted',365);
	});
});

/* code to hide the notice on click */
jQuery(document).ready(function() {
    jQuery(".eucc-close").click(function () {
        if (EuccDevModeOn){
            jQuery(".section-eu-cookie-compliance").hide();
            setTimeout(function() { jQuery(".section-eu-cookie-compliance").show(); }, 800);
        }
        else{
            jQuery(".section-eu-cookie-compliance").remove();
        }
    });
});