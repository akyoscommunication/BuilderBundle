import Modal from "../modules/modal";
import Toast from "../../../../CoreBundle/assets/scripts/modules/Toast";
import FixCKEDitor from "../../../../CoreBundle/assets/scripts/modules/FixCKEditor";
import FileManager from "../../../../FileManagerBundle/assets/scripts/main";

class EditComponent {
    static editComponent() {
        // $('.aky-builder-component .aky-builder-component-header__edit').off('click');
        $(document).on('click', '.aky-builder-component-header__edit', function () {
            const componentid = $(this).parents('.aky-builder-component').data('componentid');
            const path = '/admin/builder/component/'+componentid+'/edit'
            const parent = $(this).parents('.aky-builder-component-view')
            const comp = parent.parent()
            
            $('#modalEdit').addClass('active');

            fetch(path)
                .then(function (res) {
                    return res.text()
                        .then(function (response) {
                            const modal = $('#modalEditComponent');
                            modal.html(response);
                        })
                        .then(function () {
                            FileManager.initFileCollection();
                            FixCKEDitor.init();
                            $('#modalEditComponent > form').submit(function (e) {
                                e.preventDefault();

                                $.ajax({
                                    method: 'POST',
                                    url: path,
                                    data: $(this).serialize(),
                                    success: function (res) {
                                        const cloneParent = parent.clone()
                                        parent.remove(comp)
                                        const newComp = $(res)
                                        comp.replaceWith(newComp)
                                        $(newComp)[0].appendChild(cloneParent[0])
                                        $(newComp).addClass('aky-builder-component-sortable position-relative')
                                        $('#modalEdit').removeClass('active');
                                        Modal.checkModal('#modalEdit', '#modalEditComponent');
                                        new Toast('Composant édité', 'success', 'Succès', 5000 );
                                    },
                                    error: function(er) {
                                        console.log(er, 'error');
                                        new Toast('Une erreur s\'est produite...', 'error', 'L\'édition du composant n\'a pas eu lieu', 5000 );
                                    }
                                });
                            });
                        });
                })
        });
    }
}

export default EditComponent