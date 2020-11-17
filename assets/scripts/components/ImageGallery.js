class Gallery {
	
	constructor() {
		this.slideIndex = {};
	}
	
	static init() {
		const that = this;
		if ($(".aky-gallery").length) {
			$('.aky-gallery').each(function() {
				const randomString = $(this).data('gallery');
				const currentIndexString = "slideIndex"+randomString;
				if(typeof that.slideIndex == "undefined") {
					that.slideIndex= {};
				}
				that.slideIndex[currentIndexString] = 1;
				$("#galleryModal"+randomString).appendTo('body');
				$(this).find(".picture").click(function(){
					that.showSlides($(this).data('index'), randomString);
					$("#galleryModal"+randomString).modal("show");
				});
				$(".slides-controls").click(function(){
					let value = $(this).data('value');
					that.changeSlides(value, randomString, currentIndexString);
				});
			})
		}
	}
	
	// Next/previous controls
	static changeSlides(n, randomString, currentIndexString) {
		let that = this;
		that.showSlides(that.slideIndex[currentIndexString] += n, randomString, currentIndexString);
	}
	
	static showSlides(n, randomString, currentIndexString) {
		const galleryModal = $("#galleryModal"+randomString);
		let that = this;
		let i;
		let slides = galleryModal.find(".slide");
		let captionText = galleryModal.find("#caption"+randomString);
		if (n > (slides.length - 1)) {
			that.slideIndex[currentIndexString] = 0
		} else if (n < 0) {
			that.slideIndex[currentIndexString] = (slides.length - 1)
		} else {
			that.slideIndex[currentIndexString] = n;
		}
		for (i = 0; i < slides.length; i++) {
			$(slides[i]).css('display', "none");
		}
		$(slides[that.slideIndex[currentIndexString]]).css('display', "flex");
		let picture = $('.gallery'+randomString).find(".picture[data-index='"+that.slideIndex[currentIndexString]+"']");
		if(picture.data('caption')) {
			captionText.html(" - "+picture.data('caption'));
		}
	}
}

export default Gallery
