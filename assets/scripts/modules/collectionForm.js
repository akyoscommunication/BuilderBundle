class CollectionForm {
    static addCloneForm($collectionHolder) {
        // get the new index
        const index = $collectionHolder.data('index');

        // Get the data-prototype explained earlier
        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        const $prototype = $($collectionHolder.data('prototype').replace(/__name__/g, index));

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // add a delete link to the new form
        this.addCloneFormDeleteLink($prototype);

        // Display the form in the page
        $collectionHolder.append($prototype);
    }

    static addCloneFormDeleteLink($newForm) {
        const $deleteFormLink = $('<a href="#" class="btn btn-outline-danger">Supprimer ce champ <i class="fas fa-times"></i></a>');
        $newForm.append($deleteFormLink);

        $deleteFormLink.on('click', function (e) {
            e.preventDefault();

            // remove the div for the invitationProduct form
            $newForm.remove();
        });
    }
}

export default CollectionForm
