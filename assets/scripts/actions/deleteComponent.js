import Toast from "../../../../CoreBundle/assets/scripts/modules/Toast";

class DeleteComponent {
    static deleteComponent() {
        $(document).on('click', '#componentsRenderContainer .aky-builder-component .aky-builder-component-header__delete', function () {
            const parent = $(this).parent().closest('.aky-builder-component');
            parent.addClass('onDeleting');
            parent.append('<div class="loader loader--style1" title="0">\n' +
                '  <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"\n' +
                '   width="40px" height="40px" viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">\n' +
                '  <path opacity="1" fill="#fff" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946\n' +
                '    s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634\n' +
                '    c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"/>\n' +
                '  <path fill="#093EAB" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0\n' +
                '    C22.32,8.481,24.301,9.057,26.013,10.047z">\n' +
                '    <animateTransform attributeType="xml"\n' +
                '      attributeName="transform"\n' +
                '      type="rotate"\n' +
                '      from="0 20 20"\n' +
                '      to="360 20 20"\n' +
                '      dur="0.5s"\n' +
                '      repeatCount="indefinite"/>\n' +
                '    </path>\n' +
                '  </svg>\n' +
                '</div>').show('slow');

            $.ajax({
                method: 'DELETE',
                url: '/admin/builder/component/'+parent.data('componentid'),
                success: function (res) {
                    console.log(res, 'success');
                    if ( res === 'valid'){
                        parent.parent().remove();
                        new Toast('Composant supprimé', 'info', 'Succès', 5000 );
                    } else {
                        parent.find('.loader').fadeOut('slow', function () {
                            $(this).remove();
                            parent.removeClass('onDeleting');
                            new Toast('Composant non supprimé', 'danger', 'Une erreur s\'est produite...', 5000 );
                        });
                    }
                },
                error: function(er) {
                    parent.find('.loader').fadeOut('slow', function () {
                        $(this).remove();
                        parent.removeClass('onDeleting');
                        new Toast('Composant non supprimé', 'danger', 'Une erreur s\'est produite...', 5000 );
                    });
                    console.log(er, 'error');
                }
            })
        });
    }
}

export default DeleteComponent