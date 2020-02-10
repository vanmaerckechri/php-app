var CVMTOOLS = CVMTOOLS || {};

CVMTOOLS.ToggleNavVisibility = function()
{
	"use strict";
	this.nav;
	this.lastPosition;
};

CVMTOOLS.ToggleNavVisibility.prototype.init = function(navId)
{
	this.nav = document.getElementById(navId);
	this.updatePosition('lastPosition');
	window.addEventListener('scroll', this.detectDirection.bind(this));
};

CVMTOOLS.ToggleNavVisibility.prototype.detectDirection = function(e)
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
};

CVMTOOLS.ToggleNavVisibility.prototype.updatePosition = function(positionName)
{
	this[positionName] = document.documentElement.scrollTop;
};

CVMTOOLS.ToggleNavVisibility.prototype.hide = function()
{
	if (this.lastPosition > this.nav.offsetHeight)
	{
		this.nav.classList.add("hide");
	}
};

CVMTOOLS.ToggleNavVisibility.prototype.display = function()
{
	this.nav.classList.remove("hide");
};