var CVMTOOLS = CVMTOOLS || {};

CVMTOOLS.AjaxRequest = function(requestType, phpFile)
{
	"use strict";
	this.httpRequest;
	this.requestType;
	this.phpFile;
	this.data = null;
};

CVMTOOLS.AjaxRequest.prototype =
{
	getHttpRequest()
	{
		if (window.XMLHttpRequest)
		{
			return new XMLHttpRequest();
		}
		else
		{
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	},

	callServer()
	{
		if (this.httpRequest.readyState === 4 && this.httpRequest.status === 200)
		{
			var container = document.querySelector('.article-container');
			container.innerHTML = this.httpRequest.responseText;
		}

	},

	get()
	{
		this.httpRequest.onreadystatechange = this.callServer();

		this.httpRequest.open(this.requestType, '..\\..\\public\\bridge\\' + this.phpFile, true);
		this.httpRequest.setRequestHeader('X-Requested-With', 'xmlhttprequest');
		this.httpRequest.send(this.data);
	},

	initForm(form, cb)
	{
		var that = this;
		form.addEventListener('submit', function(e)
		{
			e.preventDefault();
			that.data = new FormData(form);
			that.get();
		})
	},

	initRequest(requestType, phpFile)
	{
		this.httpRequest =  this.getHttpRequest();
		this.requestType = requestType;
		this.phpFile = phpFile;
	}
};