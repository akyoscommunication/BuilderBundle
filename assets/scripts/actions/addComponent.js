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
                    type: $(this).attr('data-type'),
                    typeId: $(this).attr('data-typeid'),
                    componentId: $(this).attr('data-componentid'),
                    parentComponentId: target,
                },
                success: function (res) {
                    clone.attr('data-componentid', res);

                    if (target !== 'main') {
                        const endpoint = $('#componentsRenderContainer').find('.aky-builder-component[data-componentid='+$('#componentTab').attr('data-parentcomponent')+']')
                        endpoint.children('.aky-builder-component-child-render').append('<div class="aky-builder-component--parent aky-builder-component-sortable col-md-12">'+(clone[0].outerHTML)+'</div>').fadeOut().fadeIn();
                    } else {
                        clone.addClass('isParent');
                        $('#componentsRenderContainer > .builder-component--container').append('<div class="aky-builder-component-sortable col-md-12">'+(clone[0].outerHTML)+'</div>').fadeOut().fadeIn();
                    }
                    
                    const dataUrl = clone.find('.aky-builder-component-header .aky-builder-component-header__edit').attr('data-url')
                    
                    clone.find('.aky-builder-component-header .aky-builder-component-header__edit').attr('data-url', dataUrl.replace('%23id%23', res))

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