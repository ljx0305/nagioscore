/*****************************************************************************
* Filename:    nagjs.js
* Description: contains objects and functions used in the Nagios(R) Core(SM)
*              website.
* Requires:    jquery
* Contents:    popup, getCookie, post_cmd
* License:     This program is free software; you can redistribute it and/or
*              modify it under the terms of the GNU General Public License
*              version 2 as published by the Free Software Foundation.
*****************************************************************************/


/* --------------------------------------------------------------------------
* Object:      popup
* Description: A simple popup box
* Usage Sample:
*	<script>
*		var	Popup;
*		$(document).ready(function () {
*			Popup = new popup();
*			Popup.centerText(true);
*			Popup.swapClass("popupNormal","popupWarning");
*			Popup.show("<img class='loading' src='images/loading-bk.gif'>
*						"Testing <b>1...2...3</b><br>" +
*						"This is a test. This is only a test.",
*						{ x:"400px", y:"200px" }, null, 5000);
*		});
*	</script>
* --------------------------------------------------------------------------*/

popup = function()
{
	this.box = null;
	this.showing = false;
	this.timer = null;

	this.box = $("<div/>", {
		'class':"popupContainer popupNormal"
	});
	$('body').append(this.box);
}

popup.prototype.show = function(text, pos, size, timeout)
{
	var This = this;

	if (this.showing && this.timer) {
		window.clearTimeout(this.timer);
		this.hide(true);
	}

	this.box.html(text);

	if (pos && pos.x && pos.y) {
		this.box.css("left", pos.x + "px");
		this.box.css("top", pos.y + "px");
	}

	if (size && size.cx && size.cy) {
		this.box.width(size.cx);
		this.box.height(size.cy);
	}

	this.showing = true;
	this.box.fadeIn();

	if (timeout) {
		this.timer = window.setTimeout(function(){This.hide();}, timeout);
	}
}

popup.prototype.hide = function(noclear)
{
	var This = this;

	this.showing = false;
	this.box.fadeOut(null, function(){
		This.timer = null;
		if (noclear)
			return;
		This.box.html("");
		This.box.css("left", "");
		This.box.css("top", "");
		This.box.width("");
		This.box.height("");
		This.box.removeClass("popupError popupSuccess popupWarning");
		This.box.addClass("popupNormal");
	});
}

popup.prototype.setPos = function(pos)
{
	if (pos && pos.x && pos.y) {
		this.box.css("left", pos.x + "px");
		this.box.css("top", pos.y + "px");
	}
}

popup.prototype.centerText = function(doit)
{
	if (doit)
		this.box.css("text-align", "center");
	else
		this.box.css("text-align", "left");
}

popup.prototype.addClass = function(ClassName)
{
	if (typeof this.box.className === 'undefined')
        return;
    if (this.box.hasClass(ClassName))
        return;
    this.box.addClass(ClassName);
}

popup.prototype.removeClass = function(ClassName)
{
	if (typeof this.box.className === 'undefined')
        return;
    if (this.box.hasClass(ClassName))
        return;
	this.box.removceClass(ClassName);
};

popup.prototype.swapClass = function(ClassName1, ClassName2)
{
    if (this.box.hasClass(ClassName1))
		this.box.removeClass(ClassName1).addClass(ClassName2);
	else if (this.box.hasClass(ClassName2))
		this.box.removeClass(ClassName2).addClass(ClassName1);
};

popup.prototype.toggleClass = function(ClassName)
{
    if (this.box.hasClass(ClassName))
		this.box.removeClass(ClassName);
	else
		this.box.addClass(ClassName);
};


/* --------------------------------------------------------------------------
* Function:    getCookie
* Description: Gets the value of a cookie
* Usage Sample:
*	<script>
*	var c = getCookie("NagFormId");
*	</script>
* --------------------------------------------------------------------------*/

getCookie = function(Name)
{
	var re = new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(Name)
			.replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$");
	if (!Name) { return null; }
	return decodeURIComponent(document.cookie.replace(re, "$1")) || null;
}


/* --------------------------------------------------------------------------
* Object:      post_cmd
* Description: Sends a 'POST' to cmd.cgi
* Usage Sample:
*	<script>
*	function subCommand(evt, cmd_id) {
*		var data, fid;
*		fid = getCookie('NagFormId');
*		data={nagFormId:fid,cmd_typ:cmd_id,cmd_mod:2,btnSubmit:'Commit'}
*		post_cmd('COMMAND_CGI', data, Popup);
*	}
*	</script>
* --------------------------------------------------------------------------*/

post_cmd = function(url, data, popupObj)
{
	if (popupObj) {
		popupObj.show("<img class=loading src='../images/loading-wh.gif'>" +
				"Please wait.<br>Your request is being processed.");
	}

	$.ajax({
		url:url,
		method:"POST",
		timeout:10000,
		cache:false,
		data:data,
		global:false,
		dataType:"html",
		context:popupObj,
		success:post_cmd_success,
		error:post_cmd_error
	});
}

post_cmd_success = function(data, txtStatus, jqXHR)
{
	var	txt, tmp, div, htm = $.parseHTML(data);

	tmp = $("<div/>");
	$(tmp).append(htm);

	div = $(tmp).find(".infoMessage");
	if (div)
		txt = $(div).html();
	if (txt) {
		if (this.swapClass)
			this.swapClass('popupNormal','popupSuccess');
	} else {
		div = $(tmp).find("div.errorMessage");
		if (div)
			txt = $(div).html();
		if (txt) {
			if (this.swapClass)
				this.swapClass('popupNormal','popupError');
		}
	}

	if (!txt) {
		if (this.swapClass)
			this.swapClass('popupNormal','popupWarning');
		txt = "An unknown error has occurred.<br>Please try again later.";
	}
	txt = txt.replace(/<br><br>\n<a href=.*/i, "");

	if (this.show)
		this.show(txt, null, null, 5000);
}

post_cmd_error = function(jqXHR, txtStatus, errorThrown)
{
	if (this.swapClass)
		this.swapClass('popupNormal','popupError');

	if (jqXHR.status >= 400)
		txt = "The requested location was not found!";
	else if (txtStatus == "timeout") {
		this.swapClass('popupError','popupWarning');
		txt = "Your request was not processed in a timely manner.<br>" +
			"It may still execute, as the server may be temporarily busy.";
	} else
		txt = "An error occurred processing your request: " +
			txtStatus + "<br>" + errorThrown;
	if (this.show)
		this.show(txt, null, null, 5000);
}
