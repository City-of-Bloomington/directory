"use strict";
$(function(){
    var menu = new rpdm($('#userDropdownLauncher'), $('#userDropdown'));
    menu.buttonClick().documentClick();

    var settingsMenu = new rpdm($('#siteSettingsLauncher'), $('#siteSettingsDropdown'));
    settingsMenu.buttonClick().documentClick();
});

