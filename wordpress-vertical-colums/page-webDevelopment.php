<?php
/*
Template Name: Web Development
*/
?>
<?php get_header(); ?>

<div class="mainSection">
	<div class="limiter">
		<img src="<?php bloginfo('template_url'); ?>/img/bigIcon_dev.png" alt="Desarrollo Web" />
		<h1><?php
			$page_id = 63;
			$thePage = get_page($page_id); 
			echo $thePage->post_title; 
		?></h1>
		<p><?php echo $thePage->post_content; ?></p>
	</div>	
</div>
<div class="limiter">	
	<?php get_sidebar( 'single' ); ?>
	<div class="portfolioContainer">
		<?php 
			$args = array( 'category' => 6, 'numberposts' => 0);
			$lastposts = get_posts( $args );
			$iterations = floor(sizeof($lastposts)/4);
			$col0 = array();
			$col1 = array();
			$col2 = array();
			$col3 = array();
			$i = 0;
			while($i < $iterations){
				array_push($col0,$lastposts[$i]);
				array_push($col1,$lastposts[$i+1]);
				array_push($col2,$lastposts[$i+2]);
				array_push($col3,$lastposts[$i+3]);
				$i = $i + 4;
			}
			$rest = sizeof($lastposts) - $i;
			if($rest > 0){
				array_push($col0,$lastposts[$i]);
			} 
			if($rest > 1){
				array_push($col1,$lastposts[$i+1]);
			}
			if($rest > 2){
				array_push($col2,$lastposts[$i+2]);
			}
		?>
		<div class="col">
			<?php foreach($col0 as $post) : setup_postdata($post); ?>
				<?php global $more;	$more = 0; ?>
				<div class="portFolioItem">
					<a href="<?php echo get_permalink(); ?>"><?php the_content(); ?></a>			
				</div>
			<?php endforeach; ?>
		</div>
		<div class="col">
			<?php foreach($col1 as $post) : setup_postdata($post); ?>
				<?php global $more;	$more = 0; ?>
				<div class="portFolioItem">
					<a href="<?php echo get_permalink(); ?>"><?php the_content(); ?></a>			
				</div>
			<?php endforeach; ?>
		</div>
		<div class="col">
			<?php foreach($col2 as $post) : setup_postdata($post); ?>
				<?php global $more;	$more = 0; ?>
				<div class="portFolioItem">
					<a href="<?php echo get_permalink(); ?>"><?php the_content(); ?></a>			
				</div>
			<?php endforeach; ?>
		</div>
		<div class="col">
			<?php foreach($col3 as $post) : setup_postdata($post); ?>
				<?php global $more;	$more = 0; ?>
				<div class="portFolioItem">
					<a href="<?php echo get_permalink(); ?>"><?php the_content(); ?></a>			
				</div>
			<?php endforeach; ?>
		</div>
		<script type="text/javascript">

			portFolioItems = jQuery('.portFolioItem');
			for(var i = 0; i < portFolioItems.length; i++)
			{
				var source = jQuery(portFolioItems[i]).children('p').children('a:first-child').html();
				var target = jQuery(portFolioItems[i]).children('a');
				jQuery('<p>' + source + '</p>').appendTo(target);
				jQuery(portFolioItems[i]).children('p').remove();
			}
			jQuery('<img src="<?php bloginfo('template_url'); ?>/img/portFolioItemPlus.png" alt="moreInfo" class="moreInfo" />').appendTo('.portFolioItem > a');
			jQuery('.portFolioItem').mouseenter(function(){
				var itemHeight = (jQuery(this).children().height()-202)+6;
				jQuery(this).css({ 'background-color':'#cccccc' });
				jQuery(this).animate({ 'padding-bottom':itemHeight+'px' }, 500);
			});
			jQuery('.portFolioItem').mouseleave(function(){
				jQuery(this).css({ 'background-color':'#ffffff' });
				jQuery(this).animate({ 'padding-bottom':'6px' }, 500);
			});
			
		</script>
	</div>
</div>


<?php get_footer(); ?>