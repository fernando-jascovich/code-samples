package  {
	import org.papervision3d.objects.DisplayObject3D;
	import org.papervision3d.objects.primitives.Plane;
	import org.papervision3d.materials.BitmapFileMaterial;
	import org.papervision3d.materials.BitmapColorMaterial;
	import flash.display.MovieClip;
	import flash.filters.BitmapFilter;
	import flash.filters.BitmapFilterQuality;
	import flash.filters.BlurFilter;
	import org.papervision3d.materials.MovieMaterial;
	import flash.display.Bitmap;
	import flash.display.BitmapData;
	import flash.display.Loader;
	import flash.events.Event;
	import flash.net.URLRequest;
	import flash.events.MouseEvent;
	import caurina.transitions.Tweener;
	import flash.text.*;
	import flash.net.navigateToURL;
	import flash.ui.Mouse;
	
	public class Carrousel3d {
		
		protected var totalItems:Number;
		protected var circleRadius:Number;
		protected var actualItem:Number = -1;
		protected var planeWidth:Number;
		protected var planeHeight:Number;
		protected var loadCount:Number = 0;
		
		protected var mcs:Array = new Array();
		protected var planes:Array = new Array();
		protected var planesShadow:Array = new Array();
		protected var materials:Array = new Array();
		protected var fileMaterialUrl:Array = new Array();
		protected var overH1:Array = new Array();
		protected var overH2:Array = new Array();
		protected var urlLinks:Array = new Array();
		
		protected var shadowFileUrl:String = new String();
		
		protected var shadowMaterial:BitmapFileMaterial;
		
		protected var h1Format:TextFormat = new TextFormat();
		protected var h2Format:TextFormat = new TextFormat();
		
		public var planesHolder:DisplayObject3D = new DisplayObject3D();
		
		public function Carrousel3d(numItems:Number, radius:Number, filesUrl:Array, itemWidth:Number, itemHeight:Number, shadowUrl:String, h1:Array, h2:Array, links:Array) {
			totalItems = numItems;
			circleRadius = radius;
			fileMaterialUrl = filesUrl;
			planeWidth = itemWidth;
			planeHeight = itemHeight;
			shadowFileUrl = shadowUrl;
			overH1 = h1;
			overH2 = h2;
			urlLinks = links;
			prepareTextFormats();
			loadExternalContent();
		}
		
		protected function prepareTextFormats():void{
			h1Format.font = "Arial";
			h1Format.color = 0xffffff;
			h1Format.size = 16;
			h2Format.font = "Arial";
			h2Format.color = 0xffffff;
			h2Format.size = 12;
		}
		
		protected function loadExternalContent():void{
			for(var i:uint = 0; i < totalItems; i++){				
				var mcInner:MovieClip = new MovieClip();
				mcInner.graphics.beginFill(0x000000, 0.8);
				mcInner.graphics.drawRect(0, 0, planeWidth, planeHeight * 0.5);
				mcInner.graphics.endFill();
				mcInner.name = "mcInner";
				mcInner.alpha = 0;
				mcInner.addEventListener(MouseEvent.CLICK, goToUrl);
				
				var h1:TextField = new TextField();
				h1.text = overH1[i];
				h1.setTextFormat(h1Format);
				h1.x = 10;
				h1.y = 10;
				h1.width = planeWidth-20;
				h1.antiAliasType = AntiAliasType.ADVANCED;
				h1.thickness = 200;
				h1.sharpness = 100;
				h1.multiline = true;
				mcInner.addChild(h1);
				
				var h2:TextField = new TextField();
				h2.text = overH2[i];
				h2.setTextFormat(h2Format);
				h2.x = 10;
				h2.y = 24;
				h2.width = planeWidth-20;
				h2.antiAliasType = AntiAliasType.ADVANCED;
				h2.thickness = 100;
				h2.sharpness = 100;
				h2.multiline = true;
				mcInner.addChild(h2);
				
				var contentLoader:Loader = new Loader();
				contentLoader.contentLoaderInfo.addEventListener(Event.COMPLETE, processContent);
				contentLoader.load(new URLRequest(fileMaterialUrl[i]));
				
				var mc:MovieClip = new MovieClip();
				mcs.push(mc);
				mc.name = "mc" + i + "_mc";
				mc.doubleClickEnabled = true;
				mc.addChild(contentLoader);
				mc.addChild(mcInner);
				mc.addEventListener(MouseEvent.CLICK, clickItem);
				mc.addEventListener(MouseEvent.MOUSE_OVER, overItem);
				mc.addEventListener(MouseEvent.MOUSE_OUT, outItem);
			}
		}
		
		protected function processContent(e:Event):void{
			loadCount++;
			if(loadCount == totalItems){
				createMaterials();
			}
		}
		
		protected function createMaterials():void{
			for(var i:uint = 0; i < totalItems; i++){
				//var bm:BitmapFileMaterial = new BitmapFileMaterial(fileMaterialUrl[i]);
				var mm:MovieMaterial = new MovieMaterial(mcs[i]);
				mm.interactive = true;
				mm.animated = true;
				mm.smooth = true;
				mm.doubleSided = true;
				materials.push(mm);
			}
			shadowMaterial = new BitmapFileMaterial(shadowFileUrl);
			shadowMaterial.smooth = true;
			createChildren();
		}
		
		protected function createChildren():void{
			for(var i:uint = 0; i < totalItems; i++){
				var plane:Plane = new Plane(materials[i], planeWidth, planeHeight, 2, 2);
				mcs[i].getChildByName("mcInner").y = planeHeight;
				plane.name = "plane" + i;
				planes.push(plane);
				planesHolder.addChild(plane);
				var shadowPlane:Plane = new Plane(shadowMaterial, planeWidth, planeHeight, 2, 2);
				planesShadow.push(shadowPlane);
				planesHolder.addChild(shadowPlane);
			}
			placeChildren();
		}
		
		protected function placeChildren():void{
			for(var i:uint = 0; i < totalItems; i++){
				var angle:Number = Math.PI * 2 / totalItems * i;
				var plane:Plane = planes[i];
				plane.x = Math.cos(angle) * circleRadius;
				plane.z = Math.sin(angle) * circleRadius;
				plane.rotationY	= -360 / totalItems * i - 90;
				var planeShadow:Plane = planesShadow[i];
				planeShadow.z = plane.z;
				planeShadow.x = plane.x;
				planeShadow.rotationY = plane.rotationY;
				planeShadow.rotationX = 90;
				planeShadow.y = planeHeight * -0.5;
			}
			planesHolder.z = circleRadius * 1.5;
		}
		
		protected function overItem(e:Event):void{
			var currentMc = parseMcNumber(e.currentTarget.name);
			actualItem = currentMc;
			Tweener.addTween(mcs[currentMc].getChildByName("mcInner"), {
				y:(planeHeight - planeHeight*0.3),
				alpha:1,
				time:0.15,
				transition:"easeInOutSine"
			});
		}
		
		protected function outItem(e:Event):void{
			var currentMc = parseMcNumber(e.currentTarget.name);
			actualItem = -1;
			Tweener.addTween(mcs[currentMc].getChildByName("mcInner"), {
				y:planeHeight,
				alpha:0,
				time:0.3,
				transition:"easeInOutSine"
			});
		}

		protected function clickItem(e:MouseEvent):void{
			var currentMc = parseMcNumber(e.currentTarget.name);
			Tweener.addTween(planesHolder, {
				rotationY:360 - (planesHolder.getChildByName("plane" + currentMc).rotationY + 360*2),
				z: 550,
				time: 1,
				transition:"easeInOutSine"
			});
		}
		
		protected function parseMcNumber(mcName:String):Number{
			var mcNameIt1 = mcName.split("_");
			var mcNameIt2 = mcNameIt1[0].split("mc");
			var mcNameIt3 = mcNameIt2[1];
			return mcNameIt3;
		}
		
		protected function goToUrl(e:Event):void{
			if(actualItem > -1){
				navigateToURL(new URLRequest(urlLinks[actualItem]), "_self");
			}
		}

	}
	
}
