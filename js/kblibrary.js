
var kblibrary = { };

//----------------------------------------------------------------------------------
// Namespace:      utility
// Purpose:        Utility functions
//----------------------------------------------------------------------------------
kblibrary.utility = function() {

	// Check for an element id or css class
	var _elementIdClassExpr = /^([\w-]+)$|\.([\w-]+)$/;
	var _ieVersion = getInternetExplorerVersion();

	//-------------------------------------------------------------------------------------------
	// Function:       getInternetExplorerVersion
	// Purpose:        Returns the version of Internet Explorer or 0 if the browser isn't IE
	// Parameters:     None
	// Return Value:   The version of Internet Explorer
	//-------------------------------------------------------------------------------------------
	function getInternetExplorerVersion()
	{
		var explorerVersion = 0;
		if (navigator.appName == 'Microsoft Internet Explorer')
		{
			var ua = navigator.userAgent;
			var regexp  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
			if (regexp.exec(ua))
				explorerVersion = parseFloat(RegExp.$1);
		}
		return explorerVersion;
	}

	//--------------------------------------------------------------------------------
	// Function:       select
	// Purpose:        Enables logic to select a DOM element with a particular id or
	//                 a set of DOM elements with a given CSS class simply by calling
	//                 $("#elementId") or $(".cssClassName") respectively.
	// Parameters:     1. Either an element id or a CSS class prepended with period.
	//                 2. Optional tag name when selecting elements by class name.
	// Return Value:   The element or array of elements (depending on the type of
	//                 selector passed in)
	//--------------------------------------------------------------------------------
	function select(selector, tagname, element)
	{
		if(selector && typeof selector === "string")
		{
			var match = _elementIdClassExpr.exec(selector);
			if(match)
			{
				// Element ID. eg $("elementId")
				if(match[1])
					return document.getElementById(match[1]);

				// Class. eg $(".cssClass")
				else if(match[2])
					return getElementsByClassName((element ? element : document.body), (tagname ? tagname : "*"), match[2]);
			}
		}
		return null;
	}

	function getElementsByClassName(element, tagname, classname)
	{
		var elements = element.getElementsByTagName(tagname);
		var el, retArray = [];
		var regExp = new RegExp("(^|\\s)" + classname + "(\\s|$)");

		for(var i = 0; i < elements.length; i++)
		{
			el = elements[i];
			if(regExp.test(el.className))
				retArray.push(el);
		}
		return retArray;
	}

	// Add the function to the window object so that users can select elements by simply
	// entering either $("elementId") or $(".cssClass")
	window.$ = select;

	return {

		ieVersion : _ieVersion,

		getPageDimensions : function()
		{
			var xScroll, yScroll, windowWidth, windowHeight;

			if(window.innerHeight && window.scrollMaxY)
			{
				xScroll = window.innerWidth + window.scrollMaxX;
				yScroll = window.innerHeight + window.scrollMaxY;
			}
			else if(document.body.scrollHeight > document.body.offsetHeight)
			{
				// all but Explorer Mac
				xScroll = document.body.scrollWidth;
				yScroll = document.body.scrollHeight;
			}
			else
			{
				// Explorer Mac plus would also work in Explorer 6 Strict, Mozilla and Safari
				xScroll = document.body.offsetWidth;
				yScroll = document.body.offsetHeight;
			}

			if(self.innerHeight)
			{
				// All except Explorer
				if(document.documentElement.clientWidth)
					windowWidth = document.documentElement.clientWidth;
				else
					windowWidth = self.innerWidth;
				windowHeight = self.innerHeight;
			}
			else if(document.documentElement && document.documentElement.clientHeight)
			{
				// Explorer 6 Strict Mode
				windowWidth = document.documentElement.clientWidth;
				windowHeight = document.documentElement.clientHeight;
			}
			else if(document.body)
			{
				// Other Explorers
				windowWidth = document.body.clientWidth;
				windowHeight = document.body.clientHeight;
			}

			// For small pages with total height less then height of the viewport
			if(yScroll < windowHeight)
				pageHeight = windowHeight;
			else
				pageHeight = yScroll;

			// For small pages with total width less then width of the viewport
			if(xScroll < windowWidth)
				pageWidth = xScroll;
			else
				pageWidth = windowWidth;

			return { pageWidth: pageWidth, pageHeight: pageHeight, windowWidth: windowWidth, windowHeight: windowHeight};
		}
	}
}();

//----------------------------------------------------------------------------------
// Namespace:      event
// Purpose:        Event handling functionality
//----------------------------------------------------------------------------------
kblibrary.event = function() {

	var _eventHandlers = [];
	var _pageLoadFunctions = [];
	var _pageLoaded = false;

	function onPageLoad()
	{
		if(!_pageLoaded)
		{
			_pageLoaded = true;
			while(_pageLoadFunctions.length)
			{
				var f = _pageLoadFunctions.shift();
				f();
			}
		}
	}

	function onPageUnload()
	{
		while(_eventHandlers.length)
		{
			var e = _eventHandlers.shift();

			// If we have added functions to call when the page unloads call these now
			if(e.handler)
				e.handler();
			else
			{
				if (e.element.removeEventListener)
					e.element.removeEventListener(e.name, e.observer, e.useCapture);
				else if (e.element.detachEvent)
					e.element.detachEvent('on' + e.name, e.observer);

				if(e.element.tagName == "A" && e.name == "click")
					e.element.onclick = null;
			}
			e.element = null;
		}
	}

	function nullFn()
	{
		return false;
	}

	function setClickFunction(element, name)
	{	
		if(element != null && element.tagName == "A" && name == "click")
		{
			element.onclick = nullFn;
		}
	}

	function addHandler(element, name, observer, useCapture)
	{
		if (element.addEventListener)
			element.addEventListener(name, observer, useCapture);

		else if (element.attachEvent)
			element.attachEvent('on' + name, observer);

		_eventHandlers.push( { element: element, name: name, observer: observer, useCapture : useCapture } );
		element = null;
	}

	addHandler(window, 'load', onPageLoad, false);
	addHandler(window, 'unload', onPageUnload, false);

	return {
		addEventHandler : function(element, name, observer, useCapture)
		{
			if(element){
				setClickFunction(element, name);
				addHandler(element, name, observer, useCapture);
			}
		},

		onPageLoad : function(fn)
		{
			if(_pageLoaded)
				fn();
			else
				_pageLoadFunctions.push(fn);
		},

		onPageUnload : function(fn)
		{
			_eventHandlers.push({ handler : fn });
		}
	};
}();

//--------------------------------------------------------------------------------
// Namespace:      overlay
// Purpose:        Adds support for modal dialog boxes
//--------------------------------------------------------------------------------
kblibrary.overlay = function()
{
	var _overlay = null;

	function modalDialogBox(dialogParams)
	{
		// Set default parameters
		this._parameters = {
			dialogBox : null,
			displayButtons : null,
			hideButtons : null,
			dialogWidth : 200,
			dialogHeight : 100,
			fadeTime : 500.0,
			finalOpacity : 1.0,
			overlayVisible : true,
			overlayColour : "#fff" };

		if(this.verifyParameters(dialogParams))
		{
			this.setParameters(dialogParams);

			// Add the display and close button event handlers
			this.addEventHandlers(dialogParams.displayButtons, this.display.bind(this));
			this.addEventHandlers(dialogParams.hideButtons, this.hide.bind(this));
		}
	}

	modalDialogBox.prototype = {

		verifyParameters : function(params)
		{
			if(!params.dialogBox || !this.verifyButtonParams(params.displayButtons) || !this.verifyButtonParams(params.hideButtons))
				return false;
			return true;
		},

		verifyButtonParams : function(buttons)
		{
			if(!buttons || !buttons.length)
				return false;

			for(var i = 0; i < buttons.length; ++i)
			{
				if(!buttons[i])
					return false;
			}
			return true;
		},

		setParameters : function(params)
		{
			for (var key in params)
			{
				this._parameters[key] = params[key];
			}
		},

		addEventHandlers : function(buttons, func)
		{
			var numberOfButtons = buttons.length;
			for(var i = 0; i < numberOfButtons; ++i)
			{
				kblibrary.event.addEventHandler(buttons[i], 'click', func, false);
			}
		},

		display : function()
		{
			// Check we aren't already displaying a dialog box
			if(!_overlay)
			{
				var dimensions = kblibrary.utility.getPageDimensions();
				if(document.all)
					this.hideSelects();

				this.setModalDialogStyle(dimensions);
				this.createOverlay(dimensions, this._parameters.overlayColour);
				this.fade(this._parameters.finalOpacity, this._parameters.fadeTime, null);
			}
		},

		hide : function()
		{
			if(_overlay)
				this.fade(0, this._parameters.fadeTime, this.remove.bind(this));
		},

		setModalDialogStyle : function(dimensions)
		{
			var params = this._parameters;
			var dialogStyle = params.dialogBox.style;
			dialogStyle.opacity = '0';
			dialogStyle.filter = 'alpha(opacity=0)';
			dialogStyle.display = 'block';
			dialogStyle.position = 'absolute';
			dialogStyle.top = parseInt((dimensions.windowHeight - params.dialogHeight)/3) + "px";
			dialogStyle.left = parseInt((dimensions.windowWidth - params.dialogWidth)/2) + "px";
		},

		fade : function(opacity, time, callback)
		{
			if(this._parameters.overlayVisible)
			{
				kblibrary.effect.animate(_overlay, { opacity : opacity }, time, "linear", null);
			}
			kblibrary.effect.animate(this._parameters.dialogBox, { opacity : opacity }, time, "linear", callback);
		},

		hideSelects : function()
		{
		  var selects = document.getElementsByTagName('select');
			for(var i = 0; i < selects.length; i++)
			{
				selects[i].style.visibility = "hidden";
			}
		},

		createOverlay : function(dimensions, overlayColour)
		{
			var style;
			if(!_overlay)
			{
				_overlay = document.createElement("div");
				style = _overlay.style;
				style.position = "absolute";
				style.top = "0px";
				style.left = "0px";
				style.zIndex = "3000";
				style.backgroundColor = overlayColour;
				style.backgroundImage = "url(../images/trans.gif)";
				style.opacity = "0";
				style.filter = "alpha(opacity=0)";
				style.visibility = "hidden"; // IE 8 bug
				style.height = dimensions.pageHeight + "px";
				style.width = dimensions.pageWidth + "px";
				document.body.appendChild(_overlay);
				style.visibility = "visible";
			}
		},

		remove : function()
		{
			document.body.removeChild(_overlay);
			_overlay = null;
		}
	};

	return {
		createModalDialogBox : function(dialogParams)
		{
			return new modalDialogBox(dialogParams);
		}
	};
}();

//--------------------------------------------------------------------------------
// Namespace:      effect
// Purpose:        Adds support for various element animations
//--------------------------------------------------------------------------------
kblibrary.effect = function()
{
	function animate(element, properties, duration, process, callback)
	{
		this._element = element;
		this._properties = properties;
		this._currentProperties = {};
		this._duration = this._timeLeft = duration;
		this._callback = callback;
		this._fx = new Array();
		this._lastTick = 0;
		this._timerId = 0;
		this._ieVersion = kblibrary.utility.ieVersion;
		this._process = this.setProcess(process);
		this.getCurrentProperties(properties);
		this.setFx(properties);
		this.start();
	}

	animate.prototype = {

		setProcess : function(process)
		{
			if(process.match(/^linear$/i))
				return this.linear;
			else
				return this.swing;
		},

		getCurrentProperties : function(properties)
		{
			var prop;
			var el = this._element;

			for(var p in properties)
			{
				if(this._ieVersion && this._ieVersion < 9)
				{
					if(p == "opacity")
					{
						var match, filterRegex = /^alpha\(opacity\s*=\s*(\d+)\)$/
						prop = el.currentStyle.filter;
						match = filterRegex.exec(prop);
						if(match)
							this._currentProperties.opacity = parseFloat(match[1])/100.0;
						else
							this._currentProperties.opacity = 0;
					}
					else
					{
						prop = el.currentStyle[p];
						if(prop && prop.length)
							this._currentProperties[p] = parseInt(prop);
						else
							this._currentProperties[p] = 0;
					}
				}
				else
				{
					prop = el.style[p];
					if(prop && prop.length)
					{
						if(p == "opacity")
							this._currentProperties[p] = parseFloat(prop);
						else
							this._currentProperties[p] = parseInt(prop);
					}
					else
						this._currentProperties[p] = 0;
				}
			}
		},

		setFx : function()
		{
			for(var p in this._properties)
			{
				switch(p)
				{
					case "opacity":
						this._fx.push( { fn :( this._properties[p] > this._currentProperties[p] ? this.fadeIn : this.fadeOut ), param : null } );
						break;

					default:
						this._fx.push( { fn : this.applyFx, param : p } );
						break;
				}
			}
		},

		start : function()
		{
			// Reset the _timeLeft variable as the animation  may be being re-run
			this._timeLeft = this._duration;
			this._lastTick = new Date().getTime();
			this._timerId = setInterval(this.animate.bind(this), 33);
		},

		animate : function()
		{
			var curTick = new Date().getTime();
			var elapsedTicks = curTick - this._lastTick;
			if(this._timeLeft <= elapsedTicks)
			{
				this.stop();
			}
			else
			{
				var fx = this._fx;
				this._timeLeft -= elapsedTicks;
				this._lastTick = curTick;

				// Apply the selected effects
				for(var i = 0; i < fx.length; ++i)
					fx[i].fn(this, fx[i].param);
			}
		},

		stop : function()
		{
			clearInterval(this._timerId);
			this.finalise();
			if(this._callback)
				this._callback();
		},

		quit : function()
		{
			clearInterval(this._timerId);
		},

		finalise : function()
		{
			for(var p in this._properties)
			{
				switch(p)
				{
					case "opacity":
						this.setOpacity(this._properties.opacity);
						break;

					default:
						this._element.style[p] = this._properties[p] + "px";
						break;
				}
			}
		},

		fadeIn : function(me)
		{
			var opacity = me._properties.opacity;
			var newOpacity = opacity - me._process(me._timeLeft/me._duration, me._currentProperties.opacity, opacity);
			me.setOpacity(newOpacity);
		},

		fadeOut : function(me)
		{
			var opacity = me._properties.opacity;
			var newOpacity = me._process(me._timeLeft/me._duration, opacity, me._currentProperties.opacity);
			me.setOpacity(newOpacity);
		},

		setOpacity : function(value)
		{
			var style = this._element.style;
			if(this._ieVersion && this._ieVersion < 9)
				style.filter = 'alpha(opacity = ' + (value * 100) + ')';
			else
				style.opacity = value;
		},

		applyFx : function(me, param)
		{
			var newValue = me._process(me._timeLeft/me._duration, me._properties[param], me._currentProperties[param] - me._properties[param]);
			me._element.style[param] = parseInt(newValue) + "px";
		},

		linear : function(p, firstNum, diff)
		{
			return firstNum + diff * p;
		},

		swing : function(p, firstNum, diff)
		{
			return ((-Math.cos(p * Math.PI)/2) + 0.5) * diff + firstNum;
		}
	};

	return {
		animate : function(element, properties, duration, process, callback)
		{
			return new animate(element, properties, duration, process, callback);
		}
	};
}();

//--------------------------------------------------------------------------------
// Namespace:      ajax
// Purpose:        Ajax functionality
//--------------------------------------------------------------------------------
kblibrary.ajax = function()
{
	var _callback = null;
	var _errorCallback = null;
	var _xmlHttp = null;
	var _url;
	var _responseXML;
	var _errorText = "";
	var _nullFunction = function() {};

	function initialise(requestURL, callbackFn, errorCallbackFn)
	{
		_responseXML = null;

		// Set the _url to call
		setURL(requestURL);

		// Save the _callback functions
		setCallbacks(callbackFn, errorCallbackFn);

		// Get the XmlHttpRequest object
		if(_xmlHttp = getXmlHttpObject())
			_errorText = "";
		else
			_errorText = "Unable to create xmlHttp object";
	}

	function setURL(requestURL)
	{
		// We always append the date to the querystring to
		// prevent older versions of IE from caching the
		// result of the call and returning this result
		// for new calls to the same URL.
		if(-1 != requestURL.indexOf('?'))
			_url = requestURL + "&ms=" + new Date().getTime();
		else
			_url = requestURL + "?ms=" + new Date().getTime();
	}

	function setCallbacks(callbackFn, errorCallbackFn)
	{
		_callback = getFunction(callbackFn);
		_errorCallback = getFunction(errorCallbackFn);
	};

	function getFunction(fn)
	{
		if(undefined != fn)
			return fn;
		return null;
	}

	function getXmlHttpObject()
	{
		// Firefox, Opera 8.0+, Safari, IE7+
		if(typeof XMLHttpRequest != undefined)
		{
			return new XMLHttpRequest();
		}
		else if(window.ActiveXObject)
		{
			// Internet Explorer 5 and 6
			var versions = [ "MSXML2.XMLHttp.6.0", "MSXML2.XMLHttp.3.0", "MSXML2.XMLHTTP" , "Microsoft.XMLHTTP" ];
			var _xmlHttp;
			for(var i = 0; i < versions.length; ++i)
			{
				try
				{
					_xmlHttp = new ActiveXObject(versions[i]);
					return _xmlHttp;
				}
				catch(e)
				{
					// Do nothing
				}
			}
		}
		return null;
	}

	function stateChanged()
	{
		if(_xmlHttp.readyState == 4)
		{
			// Save the response
			try
			{
				if(_xmlHttp.status == 200)
					_responseXML = _xmlHttp.responseXML;
				else
					_errorText += "Error: XMLHttpRequest returned status " + _xmlHttp.status + "\n";
			}
			catch(e)
			{
				_errorText += "Error: " + e.toString() + "\n";
			}
			finally
			{
				// Clean up
				releaseResources();

				// Call the response handler function
				callResponseHandler();
			}
		}
	}

	function releaseResources()
	{
		delete (_xmlHttp.onreadystatechange);
		_xmlHttp.onreadystatechange = _nullFunction;
		delete _xmlHttp;
		_xmlHttp = null;
	}

	function callResponseHandler()
	{
		if(!_errorText.length)
		{
			if(_callback)
				_callback();
		}
		else
			callErrorHandler();
	}

	function callErrorHandler()
	{
		if(_errorCallback)
		{
				_errorCallback(_errorText);
		}
	}

	return {
		open : function(_url, _callback, _errorCallback)
		{
			initialise(_url, _callback, _errorCallback);

			if(_xmlHttp)
			{
				try
				{
					_xmlHttp.open("GET", _url, true);
					_xmlHttp.onreadystatechange = stateChanged;
					_xmlHttp.send(null);
				}
				catch(e)
				{
					_errorText += "Error: " + e.toString() + "\n";
					callErrorHandler();
				}
			}
			else
				callErrorHandler();
		},

		getElementValue : function(element)
		{
			if(_responseXML)
			{
				var result = _responseXML.getElementsByTagName(element);
				if(result.length)
				{
					var returnValue = [];
					for(var i = 0; i < result.length; ++i)
					{
						returnValue[i] = result[i].childNodes[0].nodeValue;
					}
					return returnValue;
				}
			}
			return null;
		}
	};
}();

Function.prototype.bind = function()
{
	var fn = this;
	var object = arguments[0];

	return function()
	{
		return fn.apply(object, arguments);
	}
};
