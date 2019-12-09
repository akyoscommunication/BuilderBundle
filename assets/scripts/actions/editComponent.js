import Modal from "../modules/modal";
import Toast from "../../../../CoreBundle/assets/scripts/modules/Toast";

class EditComponent {
    static editComponent() {
        // $('.aky-builder-component .aky-builder-component-header__edit').off('click');
        $(document).on('click', '.aky-builder-component-header__edit', function () {
            $('#modalEdit').addClass('active');

            const type = $(this).parents('.aky-builder-component').data('type');
            const typeid = $(this).parents('.aky-builder-component').data('typeid');
            const componentid = $(this).parents('.aky-builder-component').data('componentid');

            fetch('/admin/builder/component/'+componentid+'/edit')
                .then(function (res) {
                    return res.text()
                        .then(function (response) {
                            const modal = $('#modalEditComponent');
                            modal.html(response);
                        })
                        .then(function () {
                            $('#modalEditComponent > form').submit(function (e) {
                                e.preventDefault();

                                $.ajax({
                                    method: 'POST',
                                    url: '/admin/builder/component/'+componentid+'/edit',
                                    data: $(this).serialize(),
                                    success: function (res) {
                                        console.log(res, 'success');
                                        if ( res === 'valid'){
                                            $('#modalEdit').removeClass('active');
                                            Modal.checkModal('#modalEdit', '#modalEditComponent');
                                            new Toast('Composant édité', 'success', 'Succès', 5000 );
                                        } else {
                                            new Toast('Une erreur s\'est produite...', 'error', 'L\'édition du composant n\'a pas eu lieu', 5000 );
                                        }
                                    },
                                    error: function(er) {
                                        console.log(er, 'error');
                                    }
                                });
                            });
                        });
                })
        });
    }
}

export default EditComponent