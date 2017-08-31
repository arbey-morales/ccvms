function onAddTag(tag) {
    alert("Agregar: " + tag);
    }

    function onRemoveTag(tag) {
    alert("Eliminar: " + tag);
    }

    function onChangeTag(input, tag) {
    alert("Cambiar: " + tag);
    }

    $(document).ready(function() {
    $('.tags_phone').tagsInput({
        width: 'auto'
    });
});