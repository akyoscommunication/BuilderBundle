class Button {
	
	constructor() {
		this.slideIndex = {};
	}
	
	static init() {
		const that = this;
		if ($(".component-button-anchor").length) {
			$('.component-button-anchor').each(function() {
				const href = $(this).attr('href');
				const anchor = $(href);
				anchor.prepend('<div class="'+href+'" style="position: absolute;top:-40px;"></div>');
				anchor.style('position', 'relative');
				$(this).click(function(e) {
					e.preventDefault();
					$("html, body").stop().animate( { scrollTop: anchor.offset().top }, 1500);
				})
			})
		}
	}
}

export default Button
