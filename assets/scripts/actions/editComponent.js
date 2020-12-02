import Modal from "../modules/modal";
import Toast from "../../../../CoreBundle/assets/scripts/modules/Toast";
import FixCKEDitor from "../../../../CoreBundle/assets/scripts/modules/FixCKEditor";
import FileManager from "../../../../FileManagerBundle/assets/scripts/main";
import SortableJs from "../modules/sortable";

class EditComponent {
    static editComponent() {
        // $('.aky-builder-component .aky-builder-component-header__edit').off('click');
        $(document).on('click', '.aky-builder-component-header__edit', function () {
            const c = $(this).parents('.aky-builder-component[data-componentid]')
            const componentid = c.data('componentid')
            const type = c.data('type')
            const typeId = c.data('typeId')
            const path = '/admin/builder/component/'+componentid+'/edit?type='+type+'&typeId='+typeId
            const parent = $(this).parents('.aky-builder-component[data-componentid]')
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
                                        const newComp = $(res)
                                        
                                        /**
                                         * Clone les btn pour les re append dans le nouveau composant
                                         */
                                        const cloneParent = parent.clone()
                                        parent.remove(comp)
    
                                        /**
                                         * Remet les btns des enfants dans leurs composants respectifs
                                         * Avant de remplacer le composant parent édité
                                         */
                                        newComp.find('#componentsRenderContainer .aky-builder-component[data-componentid]').each(function (i) {
                                            const next = $(this).next()
                                            next.addClass('aky-builder-component-sortable position-relative')
    
                                            if($(next)[0]){
                                                $(next)[0].appendChild(this)
                                            }
                                        })
                                        
                                        comp.replaceWith(newComp)
                                        $(newComp)[0].appendChild(cloneParent[0])
                                        $(newComp).addClass('aky-builder-component-sortable position-relative')
                                        if ($('.visual-editor').length > 0) {
                                            cloneParent.addClass('position-absolute')
                                        }
                                        
                                        $('#modalEdit').removeClass('active');
    
                                        SortableJs.init();
                                        
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