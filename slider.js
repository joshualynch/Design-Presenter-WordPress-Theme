// JavaScript Document

// Slider to hide navigation and footer
var j = jQuery.noConflict();
j(document).ready(function() {
 // shows the header as soon as the DOM is ready
  j('#header').show();
  
 // shows and hides and toggles the header on click  
  j('#header-show').click(function() {
    j('#header').show('slow');
    return false;
  });
  j('#header-hide').click(function() {
    j('#header').hide('fast');
    return false;
  });
  j('#header-toggle').click(function() {
    j('#header').toggle(400);
    return false;
  });

 // slides down, up, and toggle the header on click    
  j('#header-down').click(function() {
    j('#header').slideDown('slow');
    return false;
  });
  j('#header-up').click(function() {
    j('#header').slideUp('fast');
    return false;
  });
  j('#header-slidetoggle').click(function() {
    j('#header').slideToggle(400);
	j('#author-meta').slideToggle(400);
	j(this).text(j(this).text() == 'Show Navigation' ? 'Hide Navigation' : 'Show Navigation');
    return false;
  });
  
});

// Internal scroll between anchor links
var j = jQuery.noConflict();
j(document).ready(function(){
	j(".scroll").click(function(event){
		//prevent the default action for the click event
		event.preventDefault();

		//get the full url
		var full_url = this.href;

		//split the url by # and get the anchor target name
		var parts = full_url.split("#");
		var trgt = parts[1];

		//get the top offset of the target anchor
		var target_offset = j("#"+trgt).offset();
		var target_top = target_offset.top;

		// go to that anchor by setting the body scroll top to anchor top
		j('html, body').animate({scrollTop:target_top}, 500);
	});
});

// Page Navigation

var j = jQuery.noConflict();
	j(document).ready(function () {
									
		j('.jnav-head').mouseover(function () {								
	
		j('ul.jnav').slideDown('fast');							   
	
	});
});

var j = jQuery.noConflict();
	j(document).ready(function () {
	
		j('#container').mouseover(function () {								
		
		j('ul.jnav').slideUp('800');							   
	
	});
});	