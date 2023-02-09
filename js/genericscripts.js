

var IntegerOnly_Pattern = /^\d+$/;
var DecimalOnly_Pattern = /^\d*[0-9](\.\d*[0-9])?$/;
var DecimalWithNegativeSignOnly_Pattern = /^(-)?\d*[0-9](\.\d*[0-9])?$/;
var LettersSpacesOnly_Pattern = /^[a-z\xA0-\xFF]([ ]?[a-z\xA0-\xFF]+)*[a-z\xA0-\xFF]$/i;
var LettersSpacesHyphensOnly_Pattern = /^[a-z\xA0-\xFF]([-' ]?[a-z\xA0-\xFF]+)*[a-z\xA0-\xFF]*$/i;
var LettersSpacesDotsHyphensOnly_Pattern = /^[a-z\xA0-\xFF]+([-. ]?[a-z\xA0-\xFF]*)*[a-z\xA0-\xFF]*$/i;
var LettersSpacesDotsOnly_Pattern = /^[a-z\xA0-\xFF]([. ]?[a-z\xA0-\xFF]+)*[a-z\xA0-\xFF]*$/i;
var LettersNumbersOnly_Pattern = /^([a-z\xA0-\xFF]|\d)([a-z\xA0-\xFF]*\d*)+([a-z\xA0-\xFF]|\d)+$/i;
var LettersNumberDotsOnly_Pattern = /^([a-z\xA0-\xFF]|\d)([a-z\xA0-\xFF]*[.]?\d*)+([a-z\xA0-\xFF]|\d)+$/i;
var LettersNumberSpacesHyphensOnly_Pattern = /^([a-z\xA0-\xFF]|\d)([a-z\xA0-\xFF]*[ ]?[-]?[\']?\d*)+([a-z\xA0-\xFF]|\d)*$/i;
var LettersNumbersHyphensForwardSlashesSpacesOnly_Pattern = /^([a-z\xA0-\xFF]|\d)([a-z\xA0-\xFF]*[ -\/']?\d*)+([a-z\xA0-\xFF]|\d)*$/i ;

var Address_Pattern = /^([a-z\xA0-\xFF]|\d)+([a-z\xA0-\xFF]*[ -.\/']?\d*)+([a-z\xA0-\xFF]|\d)+$/i ;
//var PhoneNumber_Pattern = /^(\d*[\(\) \.\+-]?\d*)+(\d)+$/i;
var PhoneNumber_Pattern = /^(\(?\+?[0-9]*\)?)?[x0-9_\- \(\)]*$/i;
var IP_Pattern = /^((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})$/ ;	// IPv4 validation pattern
var CreditCard_Pattern = /^(\d)+(\d| )*(\d)+$/ ;	// Credit card consists only number and space
var Postcode_Pattern = /^([a-z\-]*|\d*)+([a-z\-]*|\d*| )+([a-z\-]*|\d*)+$/i ;
var URL_Pattern = /^(http[s]?:\/\/|ftp:\/\/)?(www\.)?[a-zA-Z0-9-\.]+\.(com|org|net|mil|edu|ca|co.uk|com.au|gov)$/ ;
var DateTime_Pattern = /^((0?[1-9]|[12][0-9]|3[01])[/](0?[1-9]|1[012])[/]((19|20)\d\d))?([ ]+(0?[1-9]|1[0-2]):(0?[0-9]|[1-5][0-9])(:(0?[0-9]|[1-5][0-9]))?[ ]+([ap]m))?$/i;
var Time24_Pattern = /^((0?[0-9]|[1][0-9]|2[0-3])[:](0?[0-9]|[1-5][0-9]))$/i; 

// --------------------------------------------------------------------------
function NumericOnly(e) {

	e = e || window.event;

	// IE and Opera use e.keyCode. Firefox uses e.which
	var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
	var clipboard = 0;

	// Restrict key entry in the browser to numerics
	if ((!isNumeric(keyCode) && (!e.ctrlKey || !(clipboard = isClipboard(keyCode)))) || e.shiftKey)
		return false;

	if(clipboard == 1)
		setTimer(e);
	return true;
}

// --------------------------------------------------------------------------
function NumericSpOnly(e) {

	// Restrict key entry in the browser to numerics and a space
	e = e || window.event;

	var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
	var clipboard = 0;

	// Restrict key entry in the browser to numerics and spaces
	if ((!isNumeric(keyCode, 32) && (!e.ctrlKey || !(clipboard = isClipboard(keyCode)))) || e.shiftKey)
		return false;

	if(clipboard == 1)
		setTimer(e, 32);
	return true;
}

// --------------------------------------------------------------------------
function CreditCardDate(e) {

	// Restrict key entry in the browser to numerics and a slash
	e = e || window.event;

	var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
	var clipboard = 0;

	// Keycode 47 == forward slash
	if ((!isNumeric(keyCode, 47) && (!e.ctrlKey || !(clipboard = isClipboard(keyCode)))) || e.shiftKey)
		return false;

	if(clipboard == 1)
		setTimer(e, 47);
	return true;
}

// --------------------------------------------------------------------------
function NumericClipboard() {
	return CheckClipboard();
}

// --------------------------------------------------------------------------
function NumericSpClipboard() {
	return CheckClipboard(32);
}

// --------------------------------------------------------------------------
function CreditCardClipboard() {
	return CheckClipboard(47);
}

// --------------------------------------------------------------------------
function CheckClipboard() {

	var e = window.event;
	var keyCode = e.keyCode;
	var clipboard = 0;

	if (e.shiftKey)
		return false;

	if(e.ctrlKey) {
		// Restrict key entry in the browser to numerics
		if (!(clipboard = isClipboard(keyCode)))
			return false;

		if(clipboard == 1) {
			if(arguments[0])
				setTimer(e, arguments[0]);
			else
				setTimer(e);
		}
	}
	return true;
}

// --------------------------------------------------------------------------
function isNumeric() {

	var extraKey = arguments[1] ? arguments[1] : 0;
	switch(arguments[0]) {
		// Editing functions or cursor movement
		case 8:		// backspace
		case 9:		// tab
		case 37:	// <-
		case 39:	// ->
		case 46:	// delete
		case extraKey:
			return true;

		default:
			// Check the keycode is numeric
			if(arguments[0] >= 48 && arguments[0] <= 57)
				return true;
		}
	return false;
}

// --------------------------------------------------------------------------
function isClipboard(keyCode) {

	switch(keyCode) {
		// Clipboard functions. Note that Opera converts (for instance)
		// Ctrl+c to Ctrl+C ie. it capitalises the letters
		case 86:	// V
		case 118:	// v
			return 1;
		case 67:	// C
		case 88:	// X
		case 99:	// c
		case 120:	// x
			return 2;
	}
	return 0;
}

// -------------------------------------------------------------------------
function setTimer() {

	var okChar = arguments[1] ? arguments[1] : 0;
	var ua = navigator.userAgent.toLowerCase();
	var isIE = ((ua.indexOf('msie') != -1) && (ua.indexOf('opera') == -1));

	if(isIE)
		setTimeout('ensureNumeric(document.forms[0].' + arguments[0].srcElement.name + ', ' + okChar + ');', 1);
	else
		setTimeout('ensureNumeric(document.forms[0].' + arguments[0].target.name + ', ' + okChar + ');', 1);
}

// -------------------------------------------------------------------------
function ensureNumeric(field, okChar) {

	var val = field.value;
	var cc, result = "";

	for(var i = 0; i < val.length; ++i)
	{
		cc = val.charCodeAt(i);
		if((cc >= 48 && cc <= 57) || cc == okChar)
			result += val.charAt(i);
	}
	field.value = result;
}

// -------------------------------------------------------------------------
function isValidText(field, iMinLen, iMaxLen, regexp) {

	var strCheck, len;
	
	field.value = strCheck = field.value.trim();
		
	len = strCheck.length;
	if ((len >= iMinLen && len <= iMaxLen) && (!len || regexp.test(strCheck)))
		return true;
	return false;
}

// --------------------------------------------------------------------------
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/, ''); };

// --------------------------------------------------------------------------
function launchCenter(url, name, height, width, vscroll) {

	var str = "height=" + height + ",innerHeight=" + height + ",width=" + width + ",innerWidth=" + width;

	if (window.screen) {
		var ah = screen.availHeight - 30;
		var aw = screen.availWidth - 10;

		var xc = (aw - width) / 2;
		var yc = (ah - height) / 2;

		str += ",left=" + xc + ",screenX=" + xc;
		str += ",top=" + yc + ",screenY=" + yc;

		str += ",scrollbars=" + vscroll;
		str += ",resizable=yes";
	}

	return window.open(url, name, str);
}

// --------------------------------------------------------------------------
function showHideDiv(div, visibility) {

	document.getElementById(div).style.visibility = visibility;
}

// --------------------------------------------------------------------------
function showErrorMessage(errorMessage) {

	if (document.getElementById) {
		try {
			var divObject = document.getElementById('errorarea');
			showDiv(divObject);
		}
		catch(err) {}

		var htmlErrorMessage = "<ul id='errorlist'>" ;
		var intLoop=0;

		for (intLoop=0; intLoop<errorMessage.length; intLoop++ ) {
			htmlErrorMessage += "<li>" + errorMessage[intLoop] + "</li>"
		}

		htmlErrorMessage += "</ul>" ;
		if(document.getElementById("error_message")) 
			document.getElementById("error_message").innerHTML = htmlErrorMessage;
	}
}

// --------------------------------------------------------------------------
function isEmail(email) {

	if (window.RegExp) {
		var regExp = /^\w[\.\-_'\w]*@\w(\.?[-\w])*\.([a-z]{3,4}(\.[a-z]{2})?|[a-z]{2}(\.[a-z]{2,4})?)$/i;
		if (!regExp.test(email))
			return false;
	}
	else {
		var invalidChars = " /;,:#!$^&*()+";
		var len = invalidChars.length;
		var badChar;
		for (var i = 0; i < len; i++) {
			badChar = invalidChars.charAt(i);
			if (email.indexOf(badChar,0) > -1)
				return false;
		}

		var atPos = email.indexOf("@", 1)

		if (atPos == -1 || email.indexOf("@", atPos+1) != -1)
			return false;

		var periodPos = email.indexOf(".",atPos)
		if (periodPos == -1 || (atPos + 2) > periodPos || (periodPos + 3) > email.length)
			return false;
	}
	return true;
}

// --------------------------------------------------------------------------
function toggleDisplayDiv(divId) {

	var divObject = document.getElementById(divId);

	if ('visible' == divObject.style.visibility) {
		hideDiv(divObject);
	}
	else {
		showDiv(divObject);
	}
}

// --------------------------------------------------------------------------
function hideDiv(divObject){

	divObject.style.visibility = 'collapse';
	divObject.style.display = 'none';
}

// --------------------------------------------------------------------------
function showDiv(divObject){

	divObject.style.visibility = 'visible';
	divObject.style.display = 'block';
}

//-----------------------------------------------------------------------------
function toggleInnerHTML(HtmlObjID, text1, text2) {

	var HtmlObj = document.getElementById(HtmlObjID);

	if (text1 == HtmlObj.innerHTML)
		HtmlObj.innerHTML = text2;
	else
		HtmlObj.innerHTML = text1;
}

//-----------------------------------------------------------------------------
function toggleValue(HtmlObjName, val1, val2) {

	if (val1 == HtmlObjName.value)
		HtmlObjName.value = val2;
	else
		HtmlObjName.value = val1;
}

//-----------------------------------------------------------
function enterPressHandler(e){

	var keycode;
	if(window.event) // IE
		keycode = e.keyCode
	else if(e.which) // Netscape/Firefox/Opera
		keycode = e.which
	else 
		return;
	// Check enter key
	if(keycode == 13){
		submitSearch();
	}
}

// --------------------------------------------------------------------------
function GetXmlHttpObject(handler) {

	var objXmlHttp = null;

	if (navigator.userAgent.indexOf("MSIE")>=0) { 

		var strName="Msxml2.XMLHTTP";
		if (navigator.appVersion.indexOf("MSIE 5.5") >= 0) {
			strName="Microsoft.XMLHTTP";
		}
		try { 
			objXmlHttp = new ActiveXObject(strName);
			objXmlHttp.onreadystatechange = handler;
			return objXmlHttp;
		}
		catch(e) { 
			alert("Error. Scripting for ActiveX might be disabled");
			return;
		}
	}

	if (navigator.userAgent.indexOf("Mozilla") >= 0 ) {
		objXmlHttp = new XMLHttpRequest();
		objXmlHttp.onload = handler;
		objXmlHttp.onerror = handler;
		return objXmlHttp;
	}
}

// --------------------------------------------------------------------------
function clickback() {

	// Detect the browser because the back command is different for the different browsers
	var bName = navigator.appName;
	var bVer = parseInt(navigator.appVersion);
	var NS4 = (bName == "Netscape" && bVer >= 4);
	var IE4 = (bName == "Microsoft Internet Explorer" && bVer >= 4);

	if (NS4) { 
		// load browser specific code to go back
		history.go(-1);
	} 
	else { 
		// Internet Explorer 4.0+
		history.back();
	}
}

// --------------------------------------------------------------------------