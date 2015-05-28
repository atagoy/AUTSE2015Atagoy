/* Copyright (C) 2007 - 2010 YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

jQuery(function($){

	/* Accordion menu */
	$('.menu-accordion').accordionMenu({ mode:'slide' });

	/* Dropdown menu */
	$('#menu').dropdownMenu({ mode: 'height', dropdownSelector: 'div.dropdown', fancy: { mode:'fade', duration: 500, transition: 'easeOutExpo' } }).dropdownMenu('matchUlHeight');

	/* set hover color */
	var hoverColor;
	switch (Warp.Settings.color) {
		case 'lilac':
			hoverColorMenu = '#4B2C54';
			leaveColorMenu = '#5D4661';
			hoverColorSubMenu = '#866D8D';
			leaveColorSubMenu = '#684971';
			break;
		case 'gaming':
			hoverColorMenu = '#272727';
			leaveColorMenu = '#464646';
			hoverColorSubMenu = '#54748D';
			leaveColorSubMenu = '#295171';
			break;
		case 'cooking':
			hoverColorMenu = '#49413D';
			leaveColorMenu = '#584F4A';
			hoverColorSubMenu = '#7DB5BB';
			leaveColorSubMenu = '#5CA2AA';
			break;
		case 'landscape':
			hoverColorMenu = '#2E4355';
			leaveColorMenu = '#496281';
			hoverColorSubMenu = '#9EC059';
			leaveColorSubMenu = '#86B030';
			break;
		case 'orange':
			hoverColorMenu = '#422C23';
			leaveColorMenu = '#543E37';
			hoverColorSubMenu = '#C9923B';
			leaveColorSubMenu = '#BC770A';
			break;
		case 'black':
			hoverColorMenu = '#1F2020';
			leaveColorMenu = '#2F3131';
			hoverColorSubMenu = '#794D55';
			leaveColorSubMenu = '#57212A';
			break;
		case 'brown':
			hoverColorMenu = '#34342D';
			leaveColorMenu = '#49473D';
			hoverColorSubMenu = '#7D796F';
			leaveColorSubMenu = '#5D584B';
			break;
		case 'blue':
			hoverColorMenu = '#233B50';
			leaveColorMenu = '#384E63';
			hoverColorSubMenu = '#71899D';
			leaveColorSubMenu = '#335672';
			break;
		case 'beige':
			hoverColorMenu = '#5B564C';
			leaveColorMenu = '#6E6C65';
			hoverColorSubMenu = '#A1A19D';
			leaveColorSubMenu = '#898984';
			break;
		case 'green':
			hoverColorMenu = '#4E643F';
			leaveColorMenu = '#6B7E5B';
			hoverColorSubMenu = '#A2B18D';
			leaveColorSubMenu = '#8B9E70';
			break;
		case 'red':
			hoverColorMenu = '#502123';
			leaveColorMenu = '#663235';
			hoverColorSubMenu = '#A56669';
			leaveColorSubMenu = '#8F4043';
			break;
		default:
			hoverColorMenu = '#393C45';
			leaveColorMenu = '#4C535A';
			hoverColorSubMenu = '#7A8086';
			leaveColorSubMenu = '#596068';
	}

	/* Morph: main menu - level2 (color) */
	var menuEnter = { 'background-color': hoverColorMenu};
	var menuLeave = { 'background-color': leaveColorMenu};

	$('div#menu .hover-box1').morph(menuEnter, menuLeave,
		{ transition: 'swing', duration: 0, ignore: 'div#menu li li.separator .hover-box1, div#menu .mod-dropdown .hover-box1' },
		{ transition: 'easeInSine', duration: 500 });

	/* Morph: mod-rider sub menu - level1 */
	var submenuEnter = { 'background-color': hoverColorSubMenu };
	var submenuLeave = { 'background-color': leaveColorSubMenu };

	$('div.mod-rider ul.menu a.level1, div.mod-rider ul.menu span.level1').morph(submenuEnter, submenuLeave,
		{ transition: 'swing', duration: 0 },
		{ transition: 'easeInSine', duration: 300 });

	/* Morph: mod-line sub menu - level1 */
	var submenuEnter = { 'color': '#000000', 'padding-left': 5};
	var submenuLeave = { 'color': '#646464', 'padding-left': 0};

	$('div.mod-band ul.menu span.bg').morph(submenuEnter, submenuLeave,
		{ transition: 'easeOutExpo', duration: 100 },
		{ transition: 'easeInSine', duration: 300 });

	/* Smoothscroll */
	$('a[href="#page"]').smoothScroller({ duration: 500 });

	/* Match height of div tags */
	$('div.headerbox div.deepest').matchHeight(20);
	$('div.topbox div.deepest').matchHeight(20);
	$('div.bottombox div.deepest').matchHeight(20);
	$('div.maintopbox div.deepest').matchHeight(20);
	$('div.mainbottombox div.deepest').matchHeight(20);
	$('div.contenttopbox div.deepest').matchHeight(20);
	$('div.contentbottombox div.deepest').matchHeight(20);
	$('div.main-wrapper-1, #left, #right').matchHeight(20);

});