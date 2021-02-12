import {Sortable, Plugins} from '@shopify/draggable';
import Toast from "../../../../CoreBundle/assets/scripts/modules/Toast";

const Classes = {
    draggable: 'aky-builder-component-sortable:not(.ui-state-disabled)',
    capacity: 'aky-sortable-component-container',
};

export default class SortableJs {
    static init() {
        this.sortComponent();
    }
    static sortComponent() {
        const containers = document.querySelectorAll('#componentsRenderContainer .builder-component--container');
    
        if (containers.length === 0) {
            return false;
        }
    
        const sortable = new Sortable(containers, {
            draggable: `.${Classes.draggable}`,
            mirror: {
                constrainDimensions: true,
            },
            delay: 200,
            plugins: [Plugins.ResizeMirror],
        });
    
        sortable.on('sortable:stop', (evt) => {
            const parent = $(evt.data.newContainer).parents('.aky-builder-component-sortable').children('.aky-builder-component[data-componentid]')
            let parentId = parent.attr('data-componentid')
            const component = $(evt.data.dragEvent.data.originalSource).children('.aky-builder-component[data-componentid]')
            let componentId = component.attr('data-componentid')
    
            console.log(evt.data.newIndex, evt.data.oldIndex)
            if (evt.data.newIndex !== evt.data.oldIndex) {
                $.ajax({
                    method: 'POST',
                    url: '/admin/builder/component/change-component-position',
                    data: {
                        parent: parentId,
                        component: componentId,
                        position: evt.data.newIndex,
                    },
                    success: function (res) {
                        new Toast('Composant déplacé', 'success', 'Succès', 5000 );
                    },
                    error: function(er) {
                        new Toast('Composant non déplacé', 'error', 'Erreur', 5000 );
                    }
                })
            }
        });
    }
}
