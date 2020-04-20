import 'jquery-ui/ui/widgets/sortable';
import Toast from "../../../../CoreBundle/assets/scripts/modules/Toast";

class Sortable {
    static init() {
        this.sortComponent();
    }
    static sortComponent() {
        const sortable = $('.aky-sortable-component-container');
        sortable.sortable({
            connectWith: ".aky-sortable-component-container",
            items: ".aky-builder-component-sortable:not(.ui-state-disabled)",
            update: function(event, ui) {
                if (!ui.sender) {
                    let parent = $(ui.item).parents('.aky-builder-component[data-componentid]').data('componentid');
                    let component = $(ui.item).children('.aky-builder-component').data('componentid');
                    let sibblingBeforeComponent = $(ui.item).prevAll();

                    console.log(sibblingBeforeComponent , 'avant');
                    console.log(sibblingBeforeComponent.length , 'avant count');

                    $.ajax({
                        method: 'POST',
                        url: '/admin/builder/component/change-component-position',
                        data: {
                            parent: parent,
                            component: component,
                            position: sibblingBeforeComponent.length,
                        },
                        success: function (res) {
                            console.log(res, 'success');
                            new Toast('Composant déplacé', 'success', 'Succès', 5000 );
                        },
                        error: function(er) {
                            new Toast('Composant non déplacé', 'error', 'Erreur', 5000 );
                        }
                    })
                }
            },
        });
    }
}

export default Sortable