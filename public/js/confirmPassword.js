var CVMTOOLS = CVMTOOLS || {};

CVMTOOLS.ConfirmPassword = function()
{
	"use strict";
	this.form;
	this.pwds;
};

CVMTOOLS.ConfirmPassword.prototype = 
{
	init(formId)
	{
		this.form = document.getElementById(formId);
		this.pwds = form.querySelectorAll('[type=password]');
		var validation = form.querySelector('[type=submit]');
		var that = this;

		validation.addEventListener('click', function(e)
		{
			that.check(e);
		});
	},

	check(e)
	{
		if (this.pwds[0].value === this.pwds[1].value)
		{
			this.form.submit();
		}
		else
		{
			e.preventDefault();
			this.deleteOldMessage();
			this.sendErrorMessage();
		}
	},

	sendErrorMessage()
	{
		var lang = document.documentElement.lang;
		var sms = {
			'fr': 'Le mot de passe de confirmation ne correspond pas',
			'en': 'Confirmation password does not match'
		}
		sms = sms[lang];
		var newTag = this.domCreate('p', {'id': 'confirmPasswordSms', 'class': 'sms-error'}, sms);
		this.pwds[1].parentNode.insertBefore(newTag, this.pwds[1].nextSibling);
	},

	deleteOldMessage()
	{
		var oldMessage = document.getElementById('confirmPasswordSms');
		if (oldMessage)
		{
			oldMessage.parentNode.removeChild(oldMessage);
		}
	},

	domCreate(tag, attributes, content)
	{
		var elem = document.createElement(tag);
		for (var prop in attributes)
		{
			elem.setAttribute(prop, attributes[prop]);
		}
		elem.textContent = content;
		return elem;
	}
};