var CVMTOOLS = CVMTOOLS || {};

CVMTOOLS.InstantLoadImg = function()
{
	"use strict";
	this.input;
	this.output;
};

CVMTOOLS.InstantLoadImg.prototype =
{
	init(inputId, outputId)
	{
		this.input = document.getElementById(inputId);
		this.output = document.getElementById(outputId);
		this.input.addEventListener('change', this.loadImg.bind(this, this));
	},
	
	loadImg(that, event)
	{
		var reader = new FileReader();
		reader.onload = function()
		{
			var dataURL = reader.result;
			that.output.src = dataURL;
		}
		reader.readAsDataURL(that.input.files[0]);
	}
};