class Modal {
    static init() {
        $('.aky-modal .close').click(function () {
            $(this).parents('.aky-modal').removeClass('active');
            Modal.checkModal('#modalEdit', '#modalEditComponent');
        });
    }

    static checkModal(target, content) {
        if (!($(target).hasClass('active'))) {
            $(target).find(content).html('<spinning-dots />');
        }
    }
}

export default Modal