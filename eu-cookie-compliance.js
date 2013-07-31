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
function EuccCheckCookie(requireAccept){
var cookieconsent=EuccGetCookie("eucookiecompliance");
if (cookieconsent == 'implied' || cookieconsent == 'accepted'){
	jQuery("#eu-cookie-compliance").remove();
  }
else 
  {
	jQuery("#eu-cookie-compliance").show();
	
	if (requireAccept == 'implied'){
    EuccSetCookie("eucookiecompliance",'implied',365);
	}
  }
}

/* code to hide the notice and save cookie if acceptance needed */
jQuery(document).ready(function() {
	jQuery(".eucc-hidebutton").click(function () {
	EuccSetCookie("eucookiecompliance",'accepted',365);
	});
});