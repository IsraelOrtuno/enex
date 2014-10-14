$(function(){
	$('.slider')._TMS({
		prevBu:'.prev',
		nextBu:'.next',
		playBu:'.play',
		duration:800,
		pauseOnHover:true,
		easing:'',
		pagination:true,
		slideshow:8000,
		numStatus:false,
		reverseWay:false,
		bannerMeth:'',
		banners:true,
		preset:'diagonalExpand',
		bannerShow:function(banner){
		banner
		 .hide()
		 .fadeIn(800)
	   },
	   bannerHide:function(banner){
		banner
		 .show()
		 .fadeOut(800)
	   }
	})
})