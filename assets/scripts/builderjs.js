import addComponent from './actions/addComponent'
import editComponent from './actions/editComponent'
import deleteComponent from './actions/deleteComponent'
import editColComponent from './actions/editColComponent'

import SortableJs from './modules/sortable'

import collectionForm from './modules/collectionForm'
import modal from './modules/modal'

class Builder {
    constructor() {}

    static init() {
        this.toggleComponentTab();
        this.initFieldsCollectionForm();
        this.initFieldsOptionsCollectionForm();

        addComponent.addComponent();
        editComponent.editComponent();
        deleteComponent.deleteComponent();
        editColComponent.changeCol();
        
        modal.init();
        
        if ($('.visual-editor').length > 0) {
            $('#componentsRenderContainer .aky-builder-component[data-componentid]').each(function (i) {
                const next = $(this).next()
                next.addClass('aky-builder-component-sortable position-relative')
        
                if($(next)[0]){
                    $(next)[0].appendChild(this)
                }
            })
        }
        
        setTimeout(() => {
            SortableJs.init();
        }, 800)
    }

    static initFieldsCollectionForm() {
        const that = this;
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
            const lastChild = collectionHolder.children('.form-group').last();
            that.newFieldOptionsCollectionForm(lastChild);
        });
    }
    
    static newFieldOptionsCollectionForm($newField) {
        
        const collectionHolder = $newField.find('.options_collection');
        collectionHolder.after('<button id="add_'+collectionHolder.attr('id')+'" class="btn btn-outline-primary">Ajouter une option</button>');
        const addFieldLink = $('#add_'+collectionHolder.attr('id'));
        collectionHolder.data('index', collectionHolder.children('.form-group').length);
    
        collectionHolder.children('.form-group').each(function() {
            collectionForm.addCloneFormDeleteLink(collectionHolder);
        });
    
        addFieldLink.on('click', function(e) {
            e.preventDefault();
            collectionForm.addCloneForm(collectionHolder);
        });
    }
    
    static initFieldsOptionsCollectionForm() {
        
        const collectionHolder = $('.options_collection');
        collectionHolder.each( function() {
            const currentOption = $(this);
            $(this).after('<button id="add_'+currentOption.attr('id')+'" class="btn btn-outline-primary">Ajouter une option</button>');
            const addFieldLink = $('#add_'+currentOption.attr('id'));
            collectionHolder.data('index', collectionHolder.children('.form-group').length);
    
            currentOption.children('.form-group').each(function() {
                collectionForm.addCloneFormDeleteLink($(this));
            });
            
            addFieldLink.on('click', function(e) {
                e.preventDefault();
                collectionForm.addCloneForm(currentOption);
            });
        })
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

