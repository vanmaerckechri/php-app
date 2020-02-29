var CVMTOOLS = CVMTOOLS || {};

CVMTOOLS.ToggleNavVisibility = function()
{
	"use strict";
	this.nav;
	this.lastPosition;
};

CVMTOOLS.ToggleNavVisibility.prototype = 
{
	init(navId)
	{
		this.nav = document.getElementById(navId);
		this.updatePosition('lastPosition');
		window.addEventListener('scroll', this.detectDirection.bind(this));
	},

	detectDirection(e)
	{
		if (document.documentElement.scrollTop < this.lastPosition - 5)
		{
			this.display();
		}
		else if (document.documentElement.scrollTop > this.lastPosition + 5)
		{
			this.hide();
		}
		this.updatePosition('lastPosition');
	},

	updatePosition(positionName)
	{
		this[positionName] = document.documentElement.scrollTop;
	},

	hide()
	{
		if (this.lastPosition > this.nav.offsetHeight)
		{
			this.nav.classList.add("hide");
		}
	},

	display()
	{
		this.nav.classList.remove("hide");
	}
};