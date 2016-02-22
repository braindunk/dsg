<?php
/* 
 * Dynamic Style Guide v1.1
 * by Ethan Wilde, Braindunk LLC
 * Copyright © 2015 Braindunk LLC
 * Free to use under the GNU Public License
 * 
 * braindunk.com
 * 
 * Uses jQuery 1.11, jQuery UI 1.11 (draggable) and jQuery Rotate 2.2
 * 
 * 
 */

// Start the session
session_start();

// configure authenication values
// a simple user/pw scheme to keep unwanted eyes away from DSG
$dsgLogin = 'BRAIN';
$dsgSecret = 'power';

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Braindunk Dynamic Style Guide</title>
	<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
	<link href="jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet">
	<script src="jquery-ui-1.11.4.custom/jquery-ui.js"></script>
	<script src="jQueryRotateCompressed.2.2.js" type="text/javascript"></script>
	<style media="screen">
		/* element rules */
		html {
			height:100%;
		}
		body {
			height:100%;
			margin:0;
			font-family:'Helvetica', sans-serif;
			font-size:0.8em;
		}
		label {
			display:inline-block;
			vertical-align:top;
			margin-right:10px;
			min-width:150px;
			text-align:right;
		}
		input {
			display:inline-block;
			vertical-align:top;
			margin-right:10px;
		}
		
		/* login form */
		#login-form {
			padding:50px;
		}
		#login-form div {
			padding:10px;
			color:#fff;
			font-weight:bold;
		}
		
		/* wrapper */
		#wrapper {
			position:relative;
			height:100%;
			width:100%;
			background-color:rgba(0,0,0,0.75);
		}

		/* iframer */
		#iframer {
			position:relative;
			z-index:1;
			display:block;
			height:100%;
			width:60%;
			min-width:320px;
			border:none;
			margin:0 auto;
			background-color:rgba(255,255,255,0.8);
			overflow:hidden;
		}
		
		/* element selection box */
		.dsg-sel-box {
			position:absolute;
			z-index:2;
			background-color:rgba(255,255,0,0.5);
			box-sizing:border-box;
			border:solid 1px rgba(255,0,0,0.9);
			cursor:pointer;
		}
		
		/* element style blurb box */
		.dsg-style-blurb {
			position:absolute;
			z-index:4;
			padding:10px;
			background-color:rgba(255,255,255,0.5);
			border:1px solid #fff;
			margin:0 5px;
			box-sizing:border-box;
			cursor:move;
			overflow:hidden;
		}
		/* table cell and color div */
		.dsg-style-blurb td, 
		.dsg-style-blurb td div {
			vertical-align:middle;
		}
		.dsg-style-blurb td:first-child {
			white-space: nowrap;
		}
		
		/* line connecting sel box to blurb */
		.dsg-sel-line {
			position:absolute;
			z-index:3;
			width:100px;
			height:1px;
			border:solid thin red;
			background-color:red;
			-webkit-border-radius:1px;
			-moz-border-radius:1px;
			border-radius:1px;
		}
		
		/* page selector ui */
		#dsg-page-selector {
			display:none;
			position:absolute;
			z-index:5;
			top:50px;
			left:10px;
		}
		
		/* branding */
		#branding {
			position:absolute;
			z-index:5;
			font-size:1.3em;
			font-weight:bold;
			color:#fff;
			top:10px;
			left:10px;
		}
		#branding img {
			height:30px;
			vertical-align:middle;
		}
		/* logo for print only */
		#print-logo {
			display:none;
		}
		
		/* page title */
		#dsg-page-title {
			position:absolute;
			z-index:5;
			top:10px;
			right:10px;
			color:#fff;
			font-size:1.2em;
		}
		
		/* ... tools button */
		#dsg-tools {
			display:inline-block;
			vertical-align:middle;
			border-radius:50%;
			width:25px;
			background-color:rgba(255,255,255,0.4);
			border:solid 1px rgba(255,255,255,0.9);
			text-align:center;
			padding:0px 5px 0px 3px;
			box-sizing:border-box;
			height:25px;
			cursor:pointer;
		}
	</style>
	<style media="print">
		
		/* element rules */
		iframe,
		#dsg-tools,
		form {
			display:none;
		}
		body {
			font-family:'Helvetica', sans-serif;
			font-size:0.8em;
		}
		/* logo for print only */
		#print-logo {
			display:block;
			height:30px;
			vertical-align:middle;
		}
	</style>
</head>
<body>
<?php

// verify authentication, if fails, show form
$logok = false;
if (isset($_POST['login']) || isset($_SESSION["login"])) {
	if ((($_POST['login'] == $dsgLogin) && ($_POST['secret'] == $dsgSecret)) || ($_SESSION["login"] == 'authenticated')) {
		$logok = true;
	}
}
if (! $logok) {
?>
	<div id="wrapper">
		<!-- authentication form -->
		<form id="login-form" method="post">
			<div style="padding-left:100px;font-size:1.2em;">Authenticate</div>
			<div>
				<label for="login">Login</label>
				<input type="text" name="login" id="login">
			</div>
			<div>
				<label for="login">Secret</label>
				<input type="password" name="secret" id="secret">
			</div>
			<div>
				<label for="submit">&nbsp;</label>
				<input type="submit" value="Login">
			</div>
			<div style="font-size:0.8em;font-style:italic;font-weight:normal;">Monitor resolution of 1920 x 1080 or higher recommended for optimal viewing.</div>
		</form>
	</div>
<?php

// authentication passes, so show DSG
} else {
	$_SESSION["login"] = 'authenticated';
?>
	<!-- Notes:
		- each dynamic style guide 'page' consists of:
			1) a title (each one must be unique)
			2) a URL (loaded into an iframe in DSG)
			3) a list of element selectors for which DSG should display style blurbs
			4) a list of css properties each style blurb should display
			5) a notes field to display additional notes
	-->
	<div id="wrapper">
		<div id="branding"><img src="braindunk-logo-only.png" alt="Braindunk"><img src="braindunk-logo-print.png" alt="Braindunk" id="print-logo">&nbsp;DSG&nbsp;<span style="font-size:0.7em;">by Braindunk</span>&nbsp;&nbsp;<span id="dsg-tools" onclick="$('#dsg-page-selector').toggle();">…</span></div>
		<iframe id="iframer" width="50%" height="100%" src=""></iframe>
	</div>
	<script>
		// init basic vars
		var pageNum = 0;
		var selectedTitle = decodeURIComponent(window.location.search);
		selectedTitle = selectedTitle.replace('?','');
		var boxCenterXOffset = 0;
		var boxCenterYOffset = 3;
		var dsgJSON, dsgTitle, dsgURL, dsgElems, dsgProps, dsgNotes;
		// load external json
		function getJSON() {
			// console.log('getJSON');
			$.getJSON('dsg.json',function(dsgJSON) {
				// console.log('json loaded');
				// find current selected page from qs in JSON by title
				var myPagesUI = '<form id="dsg-page-selector"><select size="1" onchange="if(this.selectedIndex>0){window.location.search=this.options[this.selectedIndex].value;}"><option value="">(select page)</option>';
				for (var d=0; d < dsgJSON.pages.length; d++) {
					myPagesUI += '<option value="' + encodeURIComponent(dsgJSON.pages[d].title);
					myPagesUI += '">' + dsgJSON.pages[d].title + '</option>';
					if (dsgJSON.pages[d].title == selectedTitle) {
						pageNum = d;
					}
				}
				myPagesUI += '</select>&nbsp;<input type="button" value="Hide" onclick="doShowHide($(this));"></form>';
				$('#wrapper').append(myPagesUI);
	
				// 1) a Title (must be unique)
				dsgTitle = dsgJSON.pages[pageNum].title;
				// 2) a URL (loaded into an iframe in DSG)
				dsgURL = dsgJSON.pages[pageNum].url;
				// 3) a list of element selectors for which DSG should display style blurbs
				dsgElems = dsgJSON.pages[pageNum].elements;
				// 4) a list of css properties each style blurb should display
				dsgProps = dsgJSON.pages[pageNum].properties;
				// 5) a notes field to display additional notes
				dsgNotes = dsgJSON.pages[pageNum].notes;
		
				var myPageTitle = '<div id="dsg-page-title">' + dsgTitle;
				myPageTitle += '<div style="font-size:0.8em;font-weight:normal;">' + dsgNotes + '</div></div>';
				$('#wrapper').append(myPageTitle);
				getIframe(dsgURL);
			});
		}

		$(document).ready(function() {
			getJSON();
		});
		
		$(window).resize(function() {
			location.reload();
		});
		
		// show/hide all
		function doShowHide($abutton) {
			// console.log('doShowHide');
			$('.dsg-style-blurb,.dsg-sel-line').toggle();
			if ($abutton.attr('value') == 'Show') {
				$abutton.attr('value', 'Hide');
			} else {
				$abutton.attr('value', 'Show');
			}
		}
		
		// load iframe
		function getIframe(aURL) {
			// console.log('getIframe');
			var myIframe = document.getElementById('iframer');
			// set iframe load handler
			myIframe.onload = function() {
				// Safari and Opera need a kick-start. only do this for them!
				if ( 1==2 ) {
					document.getElementById('iframer').src = '';
					document.getElementById('iframer').src = aURL;
				}
				// if user navigates in iframe, onload triggers again, so let's redraw
				$('.dsg-sel-line,.dsg-style-blurb,.dsg-sel-box').remove();
				// resize iframe and parent element height to fit iframe contents
				setTimeout(iResize, 1000);
			}
			// load iframe with url
			$('#iframer').attr('src', aURL);
		}

		// jiggle handle of iframe height if scroll bar still showing
		function iResizeAgain() {
			if ( $('#iframer').hasScrollBar() ) {
				// resize height
				var ih = document.getElementById('iframer').contentWindow.document.body.offsetHeight + 'px';
				// console.log('iframe loaded height ' + ih);
				$('html,body,#wrapper,#iframer').css('height', ih);
				// if iframe has scroll bar, set timer to resize again later
				setTimeout(iResizeAgain, 1000);
			}
		}

		// resize page to match height of fully-loaded iframe and its contents (BUGGY)
		function iResize() {
			// console.log('iResize');
			// resize height
			var ih = document.getElementById('iframer').contentWindow.document.body.offsetHeight + 'px';
			// console.log('iframe loaded height ' + ih);
			$('html,body,#wrapper,#iframer').css('height', ih);
			// if iframe has scroll bar, set timer to resize again later
			setTimeout(iResizeAgain, 1000);
			// once iframe loaded, parse element selectors to display
			var eoc = 0;
			var blurbsOrdered = [];
			for (var e=0; e < dsgElems.length; e++) {
				$('#iframer').contents().find(dsgElems[e]).each(function() {
					// make sure element is not hidden (display:none;)
					if ($(this).is(':visible')) {
						// for each style blurb, loop thru css properties to display
						var esb = '<strong style="font-size:1.1em;">' + formatSel(dsgElems[e]) + '</strong><br>';
						esb += '<table border="0" width="100%">';
						for (var p=0; p < dsgProps.length; p++) {
							var esv = formatVal($(this).css(dsgProps[p]),dsgProps[p]);
							if (esv !== '') {
								var esp = formatProp(dsgProps[p]);
								// if (esp !== '') {
									// esb += esp + ' : ';
									esb += '<tr><td align="right"><strong>' + esp + '</strong></td><td>';
								// }
								esb += esv + '</td></tr>';
							}
						}
						esb += '</table>';
						// calculate screen position for each element and cast a selection box over
						var epos = $(this).offset();
						var eh = $(this).outerHeight();
						var ew = $(this).outerWidth();
						birthSelBox(epos,eh,ew,esb,eoc);
						blurbsOrdered.push( [eoc, epos.top] );
						eoc++;
					}
				});
			}
			// sort
			blurbsOrdered.sort(sortCompareSecondColumn);
			// loop thru all style blurbs, alternating left to right from top down
			for (var b=0; b < blurbsOrdered.length; b++) {
				if ( (b/2) == Math.floor(b/2) ) {
					var msbcss = 'right';
				} else {
					var msbcss = 'left';
				}
				$('#elem-' + blurbsOrdered[b][0]).css(msbcss,'0px');
				updateLine('elem-' + blurbsOrdered[b][0]);
				// uncomment next line to hide all style blurbs at start
				// $('#elem-' + blurbsOrdered[b][0] + ',#elem-' + blurbsOrdered[b][0] + '-line').hide();
			}
		}
		
		// generate a selection (clickable highlight) box for showing an element's styles 
		function birthSelBox(mpos, mh, mw, msb,eo) {
			// console.log('birthSelBox');
			var myleftedge = parseInt($('#iframer').css('margin-left'));
			var mycss = 'top:' + mpos.top + 'px;left:' + (mpos.left + myleftedge) + 'px;height:' + mh + 'px;width:' + mw + 'px;';
			var myclick = '$(\'#elem-' + eo + ',#elem-' + eo + '-line\').toggle();';
			var myhtml = '<div class="dsg-sel-box" id="elem-' + eo + '-sel" style="' + mycss + '" onclick="' + myclick + '"></div>';
			var msbcss = 'top:' + mpos.top + 'px;width:' + (myleftedge - 20) + 'px;';
			var msbdiv = '<div class="dsg-style-blurb" id="elem-' + eo + '" style="' + msbcss + '">' + msb + '</div><div class="dsg-sel-line" id="elem-' + eo + '-line"></div>';
			$('#wrapper').append(myhtml + msbdiv);
			$('#elem-' + eo).draggable({ delay: 0, distance: 0 },{
				drag: function(event, ui) {
					updateLine($(this).attr('id'));
				}
			});
		}
		
		// update line between blurb and sel box
		function updateLine(eid) {
			// console.log('updateLine');
			var thisid = '#' + eid;
			var selid = thisid + '-sel';
			var lineid = thisid + '-line';
			var x1fromleft = $(thisid).offset().left + $(thisid).width();
			var x1fromright = $(thisid).offset().left;
			var x2fromleft = $(selid).offset().left;
			var x2fromright = $(selid).offset().left + $(selid).width();
			if (x1fromright < x2fromleft) {
				var x1 = x1fromleft + boxCenterXOffset + 20;
				var x2 = x2fromleft + boxCenterXOffset;
			} else {
				var x1 = x1fromright + boxCenterXOffset;
				var x2 = x2fromright + boxCenterXOffset;
			}
			var y1 = $(thisid).offset().top + boxCenterYOffset;
			var y2 = $(selid).offset().top + boxCenterYOffset;
			var hypotenuse = Math.sqrt((x1-x2)*(x1-x2) + (y1-y2)*(y1-y2));
			var angle = Math.atan2((y1-y2), (x1-x2)) * (180/Math.PI);
			if(angle >= 90 && angle < 180){
				y1 = y1 - (y1-y2);
			}
			if(angle > 0 && angle < 90){
				x1 = x1 - (x1-x2);
				y1 = y1 - (y1-y2);
			}
			if(angle <= 0 && angle > -90){
				x1 = x1 - (x1-x2);
			}
			$(lineid).queue(function(){
				$(this).offset({top: y1, left: x1});
				$(this).dequeue();
			}).queue(function(){
				$(this).width(hypotenuse);
				$(this).dequeue();
			}).queue(function(){
				$(this).rotate(angle);
				$(this).dequeue();
			});
		
		}

		// format selector for output into a style blurb
		function formatSel(s) {
			if (s.indexOf(':') > -1) {
				s = s.substring(0, s.indexOf(':'));
			}
			return s;
		}
		
		// format text of a CSS property for output into a style blurb
		function formatProp(p) {
			p = p.replace(/-/g,' ');
			return p.toLowerCase().replace(/^(.)|\s(.)/g, 
      function($1) { return $1.toUpperCase(); });
		}
		
		// format element CSS value for output into a style blurb 
		// (or omit in certain conditions)
		function formatVal(v,p) {
			switch (p) {
				case 'background-color':
					if (v == 'rgba(0, 0, 0, 0)') {
						v = '';
					} else {
						v = '<div style="display:inline-block;width:20px;height:20px;border:solid 1px #fff;background-color:' + v + ';"></div>&nbsp;' + v + '<br>';
					}
					break;
				case 'color':
					v = '<div style="display:inline-block;width:20px;height:20px;border:solid 1px #fff;background-color:' + v + ';"></div>&nbsp;' + v + '<br>';
					break;
				case 'font-family':
					v = v.replace(/'/g,'');
					v = v.replace(/"/g,'');
					v = v.replace(', sans-serif','');
					v = v.replace(', serif','');
					v = v + '<br>';
					break;
				case 'font-size':
					// v = v + ' / '; // assumes line-height comes next!
					break;
				case 'font-style':
					// v = v + ' / '; // assumes font-weight comes next!
					if (v == 'normal') {
						v = '';
					}
					break;
				default:
					v = v + '<br>';
					break;
			}
			return v;
		}

		// sort function for 2-d array first column sort
		function sortCompareFirstColumn(a, b) {
			if (a[0] === b[0]) {
				return 0;
			}
			else {
				return (a[0] < b[0]) ? -1 : 1;
			}
		}

		// sort function for 2-d array second column sort
		function sortCompareSecondColumn(a, b) {
			if (a[1] === b[1]) {
				return 0;
			}
			else {
				return (a[1] < b[1]) ? -1 : 1;
			}
		}
		
		// check for element scrollbar
		(function($) {
			$.fn.hasScrollBar = function() {
				console.log(this.get(0).scrollHeight + " / " + this.get(0).clientHeight);
				return this.get(0).scrollHeight > this.get(0).clientHeight;
			}
		})(jQuery);
	</script>
	<noscript>
		<p>This tool requires JavaScript.</p>
	</noscript>
<?php
} // end authentication if
?>
</body>
</html>
