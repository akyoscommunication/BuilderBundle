import editComponent from './editComponent'
import deleteComponent from './deleteComponent'
import editColComponent from './editColComponent'
import builder from '../main'
import Toast from "../../../../CoreBundle/assets/scripts/modules/Toast";

class AddComponent {
    static addComponent() {
        // $('.aky-builder-components .aky-builder-component').off('click');
        $(document).on('click', '.aky-builder-component', function () {
            let clone = $(this).clone();

            $.ajax({
                method: 'POST',
                url: '/admin/builder/save/instance',
                data: {
                    type: $(this).data('type'),
                    typeId: $(this).data('typeid'),
                    componentId: $(this).data('componentid'),
                    parentComponentId: $(this).parents('#componentTab').attr('data-parentcomponent'),
                },
                success: function (res) {
                    console.log(res, 'success');
                    clone.attr('data-componentid', res);
                    clone.addClass('active');

                    if ($('#componentTab').attr('data-parentcomponent') !== 'main') {
                        $('#componentsRenderContainer').find('.aky-builder-component[data-componentid='+$('#componentTab').attr('data-parentcomponent')+']').children('.aky-builder-component-child-render').append('<div class="aky-builder-component-sortable col-md-12">'+clone[0].outerHTML+'</div>').fadeOut().fadeIn();
                    } else {
                        clone.addClass('isParent');
                        $('<div class="aky-builder-component-sortable col-md-12">'+clone[0].outerHTML+'</div>').insertBefore('#componentsRenderContainer > #componentsRenderContainerAdd').fadeOut().fadeIn();
                    }

                    new Toast('Ajout d\'un composant', 'success', 'Succès', 5000 );

                    // $('.aky-builder-components').removeClass('active');
                    //
                    // deleteComponent.deleteComponent();
                    // editComponent.editComponent();
                    editColComponent.changeCol();
                    builder.toggleComponentTab();
                },
                error: function(er) {
                    console.log(er, 'error');
                }
            })
        });
    }
}

export default AddComponent