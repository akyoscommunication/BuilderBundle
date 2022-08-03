class Button {

    constructor() {
        this.slideIndex = {};
    }

    static init() {
        const that = this;
        if ($(".component-button-anchor").length) {
            $('.component-button-anchor').each(function () {
                const href = $(this).find('a').attr('href');
                const target = $(href);
                target.prepend('<div class="anchor' + href.replace('#', '') + '" style="position: absolute;top:-100px;"></div>');
                target.css('position', 'relative');
                const anchor = $('.anchor' + href.replace('#', ''));
                $(this).click(function (e) {
                    e.preventDefault();
                    $("html, body").stop().animate({scrollTop: anchor.offset().top}, 1500);
                })
            })
        }
    }
}

export default Button
