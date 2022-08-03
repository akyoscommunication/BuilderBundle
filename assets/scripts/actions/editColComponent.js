import Toast from "../../../../CoreBundle/assets/scripts/modules/Toast";

class EditColComponent {
    static changeCol() {
        const colInput = $('.changeColInput');
        colInput.off('change');
        colInput.on('change', function () {
            const parentComponent = $(this).parent().closest('.aky-builder-component');
            const newVal = $(this).val();

            $.ajax({
                method: 'POST',
                url: '/admin/builder/component/edit/col',
                data: {
                    col: $(this).val(),
                    component: parentComponent.data('componentid'),
                },
                success: function (res) {
                    console.log(res, 'success');
                    if (res === 'valid') {
                        new Toast('Composant mis à jour', 'success', 'Succès', 5000);
                        parentComponent.parent().removeClass(function (i, className) {
                            return (className.match(/\bcol-md-\S+/g) || []).join(' ');
                        });
                        parentComponent.parent().addClass('col-md-' + newVal);
                    }
                },
                error: function (er) {
                    new Toast('Composant non mis à jour', 'danger', 'Une erreur s\'est produite...', 5000);
                    console.log(er, 'error');
                }
            });
        });
    }
}

export default EditColComponent