class Modal {
    static init() {
        $('.aky-modal .close').click(function () {
            $(this).parents('.aky-modal').removeClass('active');
            Modal.checkModal('#modalEdit', '#modalEditComponent');
        });
    }
    static checkModal(target, content) {
        if (!($(target).hasClass('active'))){
            $(target).find(content).html('<img class="loader" border="0" src="http://www.pictureshack.us/images/16942_Preloader_10.gif" alt="loader" width="128" height="128">');
        }
    }
}

export default Modal