import Swiper from 'swiper/swiper-bundle';

class ComponentSlider {
	
	constructor() {}
	
	static init() {
		const sliders = $('.component-slider');
		if(sliders.length) {
			sliders.each(function() {
				
				const container = $(this).find('.swiper-container');
				const id = container.attr('id');
				const slides_per_view = container.data('slides_per_view');
				const loop = container.data('loop');
				const spaceBetween = container.data('space-between') ? container.data('space-between') : 0;
				const speed = container.data('speed');
				const slides_per_view_991 = container.data('slides_per_view_991');
				const slides_per_view_767 = container.data('slides_per_view_767');
				console.log(slides_per_view);
				let navigation = container.data('navigation');
				if(navigation) {
					navigation = {
						prevEl: ('#'+id+' .component-slider-prev,'+'#'+id+' ~ .component-slider-prev'),
						nextEl: ('#'+id+' .component-slider-next,'+'#'+id+' ~ .component-slider-next'),
					}
				} else {
					navigation = false;
				}
				let pagination = container.data('pagination');
				if(pagination) {
					pagination = {
						el: ('#'+id+' .component-slider-pagination'),
						type: 'bullets',
					}
				} else {
					pagination = false;
				}
				let scrollbar = container.data('scrollbar');
				if(scrollbar) {
					scrollbar = {
						el: ('#'+id+' .component-slider-scrollbar'),
						draggable: true,
					}
				} else {
					scrollbar = false;
				}
				
				new Swiper(('#'+id), {
					slidesPerView: slides_per_view,
					spaceBetween: spaceBetween,
					loop: loop,
					autoplay: {
						delay: speed
					},
					navigation: navigation,
					pagination: pagination,
					scrollbar: scrollbar,
					breakpoints: {
						991: {
							slidesPerView: slides_per_view_991,
						},
						767: {
							slidesPerView: slides_per_view_767,
						},
					}
				});
			})
		}
	}
}

export default ComponentSlider