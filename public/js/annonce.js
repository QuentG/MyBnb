function deleteBtn() {
    $('button[data-action="delete"]').click(function() {
        const target = this.dataset.target;
        $(target).remove();
    });
}

function updateCounter() {
    const counter = +$('#annonce_images div.form-group').length;
    $('#widgets_counter').val(counter);
}

$('#add-img').click(function() {
    // Récupération des futurs champs que l'on va créer
    // On met un "+" devant pour l'interpréter comme un nombre
    const index = +$('#widgets_counter').val();
    // Récupération des prototypes des entrées
    const proto = $('#annonce_images').data('prototype').replace(/__name__/g, index);
    // Injection du code au sein de la div
    $("#annonce_images").append(proto);

    $('#widgets_counter').val(index + 1);

    deleteBtn();
});

updateCounter();
deleteBtn();
