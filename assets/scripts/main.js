import addComponent from './actions/addComponent'
import editComponent from './actions/editComponent'
import deleteComponent from './actions/deleteComponent'
import editColComponent from './actions/editColComponent'

import sortableComponent from './modules/sortable'

import collectionForm from './modules/collectionForm'
import modal from './modules/modal'

class Builder {
    constructor() {}

    static init() {
        this.toggleComponentTab();
        this.initFieldsCollectionForm();

        addComponent.addComponent();
        editComponent.editComponent();
        deleteComponent.deleteComponent();
        editColComponent.changeCol();

        sortableComponent.init();
        modal.init();
    }

    static initFieldsCollectionForm() {

        const collectionHolder = $('#component_template_componentFields');
        collectionHolder.after('<button id="add_component" class="btn btn-outline-primary">Ajouter un champ</button>');
        const addFieldLink = $('#add_component');
        collectionHolder.data('index', collectionHolder.children('.form-group').length);

        collectionHolder.children('.form-group').each(function() {
            collectionForm.addCloneFormDeleteLink($(this));
        });

        addFieldLink.on('click', function(e) {
            e.preventDefault();
            collectionForm.addCloneForm(collectionHolder);
        });
    }

    static toggleComponentTab() {
        $('.toggleComponentTab').off('click');
        $('.toggleComponentTab').on('click', function () {
            const modal = $('#componentTab');
            modal.toggleClass('active');
            modal.attr('data-parentcomponent', $(this).parents('[data-componentid]').data('componentid'));
        });
    }
}
export default Builder

jQuery(document).ready(function () {
    Builder.init();
});

