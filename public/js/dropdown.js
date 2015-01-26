// Revealing Prototype Dropdown Menu, depends on jQuery
var rpdm = function(launcher, menu) {
  this.launcher = launcher;
  this.menu = menu;
};
rpdm.prototype = function() {

	buttonClick = function() {
		var self = this;
		this.launcher.on('click', function(event) {
			var openMenus = $('.site-utilityBar > .ext-links.open');
			openMenus.removeClass('open');
			self.menu.removeClass('closed').addClass('open');
			setTimeout(function(){openMenus.addClass('closed')}, 1000);
			event.stopPropagation();
		});
		return this;
	},

	documentClick = function() {
		var self = this;
		$(document).on('click', function(event) {
			self.menu.removeClass('open');
			setTimeout(function(){self.menu.addClass('closed')}, 300);
		});
		return this;
	};

	return {
		buttonClick: buttonClick,
		documentClick: documentClick
	};
} ();