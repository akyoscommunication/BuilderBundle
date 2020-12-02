import editColComponent from './editColComponent'
import builder from '../builderjs'
import Toast from "../../../../CoreBundle/assets/scripts/modules/Toast";

class AddComponent {
    static addComponent() {
        $('.aky-builder-components').on('click', '.aky-builder-component', function () {
            let clone = $(this).clone();
            
            const target = ($(this).parents('#componentTab').attr('data-parentcomponent') !== undefined ? $(this).parents('#componentTab').attr('data-parentcomponent') : 'main')

            $.ajax({
                method: 'POST',
                url: '/admin/builder/save/instance',
                data: {
                    type: $(this).data('type'),
                    typeId: $(this).data('typeid'),
                    componentId: $(this).data('componentid'),
                    parentComponentId: target,
                },
                success: function (res) {
                    clone.attr('data-componentid', res);
                    clone.addClass('active aky-builder-component-sortable');

                    if (target !== 'main') {
                        $('#componentsRenderContainer').find('.aky-builder-component[data-componentid='+$('#componentTab').attr('data-parentcomponent')+']').children('.aky-builder-component-child-render').append('<div class="aky-builder-component--parent">'+(clone[0].outerHTML)+'</div>').fadeOut().fadeIn();
                    } else {
                        clone.addClass('isParent');
                        $('#componentsRenderContainer > .builder-component--container').append('<div class="aky-builder-component--parent">'+(clone[0].outerHTML)+'</div>').fadeOut().fadeIn();
                    }

                    new Toast('Ajout d\'un composant', 'success', 'Succès', 5000 );

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