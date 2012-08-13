<?php
/*
Template Name: Portfolio
*/
?>
<?php get_header(); ?>
<div id="flashContainer" class="flashContainerClass">
	<div class="flashBackground"></div>
	<div class="flashItemClass">
		<div id="flashItem">Cargando Contenido Flash...</div>
	</div>	
<?php
	$postCustomFields = get_post_custom();
	
	$itemsUrl = parseWordPressMeta($postCustomFields["carrouselImagesUrl"][0], 'src/');	
	$itemsH1 = parseWordPressMeta($postCustomFields["carrouselH1"][0], '');	
	$itemsH2 = parseWordPressMeta($postCustomFields["carrouselH2"][0], '');		
	$itemsLinks = parseWordPressMeta($postCustomFields["carrouselLinks"][0], site_url() . '/' . $pagename . '/');		
	$carrouselRadius = intval($postCustomFields["carrouselRadius"][0]);
	$itemWidth = intval($postCustomFields["carrouselItemWidth"][0]);
	$itemHeight = intval($postCustomFields["carrouselItemHeight"][0]);
	$itemShadow = 'src/' . $postCustomFields["carrouselItemShadow"][0];
	$itemsZoom = $postCustomFields["carrouselZoom"][0];
	
	function parseWordPressMeta($metaData, $path){
		$metaDataReturn = '';
		$metaDataArray = explode('&', $metaData);
		foreach ($metaDataArray as $key => $value){
			$metaDataReturn = $metaDataReturn . $path . $value . '$';
		}
		$metaDataReturn = substr_replace($metaDataReturn,'',-1);
		return $metaDataReturn;
	}
	
?>
</div>
<script type="text/javascript">

	var flashvars = {
		itemsUrl:"<?php echo $itemsUrl; ?>",
		carrouselRadius:<?php echo $carrouselRadius; ?>,
		itemWidth:<?php echo $itemHeight; ?>,
		itemHeight:<?php echo $itemHeight; ?>,
		itemShadow:"<?php echo $itemShadow; ?>",
		itemsH1:"<?php echo $itemsH1; ?>",
		itemsH2:"<?php echo $itemsH2; ?>",
		itemsLinks:"<?php echo $itemsLinks; ?>",
		itemsZoom:"<?php echo $itemsZoom; ?>"
	};
	var params = {
		base: "<?php bloginfo('template_url'); ?>/flash/",
		allowscriptaccess: "always",
		wmode: "transparent",
		scale: "noScale"
	};
	var attributes = {};

	swfobject.embedSWF("<?php bloginfo('template_url'); ?>/flash/galleryWall_003.swf", "flashItem", "1920", "470", "9.0.0","<?php bloginfo('template_url'); ?>/flash/expressInstall.swf", flashvars, params, attributes);

	//jQuery('.preloader').delay(1000).fadeOut(250);
	
</script>
<?php get_footer(); ?>