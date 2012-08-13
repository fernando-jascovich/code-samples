<?php
/*
Template Name: Project
*/
?>
<?php get_header(); ?>
<div class="stripes"></div>
<div id="projectContentContainer" class="projectContent">
	<div id="projectSlidesContainer"></div>
	<div class="projectPreloader">
		<img src="<?php bloginfo('template_url'); ?>/img/preloader-project.gif" alt="Loading..." />
	</div>
	<div class="projectPreviousSlide"></div>
	<div class="projectNextSlide"></div>
</div>
<script type="text/javascript">

	var projectContents;
	var slideWidth = jQuery('.projectContent').width();
	var slideHeight = jQuery('.projectContent').height();
	var currentSlide;

	getContents();

	function fillProjectContents(response){
		projectContents = response;
		for(var i = 0; i < projectContents.posts.length; i++){
			var itemContent = projectContents.posts[i].content;
			var output = '<div class="projectSlide" style="left:' + slideWidth*i + 'px"><div class="projectSlideContent">' + itemContent + '</div></div>';
			jQuery(output).appendTo('#projectSlidesContainer');			
		}
		jQuery('.projectPreloader').fadeOut(500, function(){
			for(var i = 0; i < projectContents.posts.length; i++){
				parseContent(jQuery('.projectSlide')[i]);
				centerContent(jQuery('.projectSlide')[i]);
			}
			jQuery('.projectSlide img').unwrap();
			currentSlide = 0;
			jQuery('.projectPreviousSlide').bind('click', projectPreviousSlide);
			jQuery('.projectNextSlide').bind('click', projectNextSlide);
			jQuery('.projectNextSlide, .projectSlideContent').fadeIn(250);
		});
	}
	
	function getContents(){
		jQuery.getJSON('<?php bloginfo('wpurl'); ?>?json=get_category_posts&category_name=<?php echo $pagename; ?>&order=ASC&orderby=title', fillProjectContents);
	}

	function parseContent(content){
		var imgWidth = jQuery(content).find('img').width();
		var imgHeight = jQuery(content).find('img').height();
		var contentHeight = jQuery(content).children().height();
		if(imgWidth < 1){

		} else if(imgWidth > 1 && imgWidth < 500){
			jQuery(content).find('img').addClass('projectSlideImgRight');
			//var imgPosY = ((contentHeight-imgHeight)-(imgHeight/2))/2;
			var imgPosY = (contentHeight-imgHeight)-(imgHeight/2)-10;
			jQuery(content).find('img').css({
				'top':imgPosY + 'px'
			});
			jQuery(content).children().children().not('img').css({
				'width':((slideWidth-80)-(imgWidth-20))-60 + 'px'
			});
		} else if(imgWidth > 500){
			jQuery(content).find('img').addClass('projectSlideImgBottom');
		} 		
	}
		
	function centerContent(foo){
		var padding = jQuery('.projectSlideContent').css('padding-top');
		padding = padding.substring(0, 2);
		padding = parseInt(padding);
		var fooHeight = jQuery(foo).children().height();
		var newHeight = ((slideHeight/2)-(fooHeight/2))-padding;
		jQuery(foo).children().css({
			'top':newHeight + 'px'
		});
	}

	function projectPreviousSlide(){
		if(currentSlide > 0){
			jQuery('#projectSlidesContainer').animate({
				'left':'+=' + slideWidth + 'px'
			}, 500);
			currentSlide--;
			jQuery('.projectNextSlide').show();
			if(currentSlide == 0){
				jQuery('.projectPreviousSlide').hide();
			}
		}
	}

	function projectNextSlide(){
		if(currentSlide < projectContents.posts.length-1){
			jQuery('#projectSlidesContainer').animate({
				'left':'-=' + slideWidth + 'px'
			}, 500);
			currentSlide++;
			jQuery('.projectPreviousSlide').show();
			if(currentSlide == projectContents.posts.length-1){
				jQuery('.projectNextSlide').hide();
			}
		}
	}
	
</script>
<?php get_footer(); ?>