$(document).ready(function() {
    // Delete 
    $('.delete').click(function() {
        var el = this;

        // Delete id
        var deleteid = $(this).data('id');

        // Confirm box
        bootbox.confirm("Vous êtes sur le point de supprimer cet agent, voulez-vous continuer? ", function(result) {

            if (result) {
                // AJAX Request
                $.ajax({
                    url: '../super-admin/delete-ag.php',
                    type: 'POST',
                    data: { id: deleteid },
                    success: function(response) {
                        if (response == 1) {
                            $(el).closest('tr').css('background', 'tomato');
                            $(el).closest('tr').fadeOut(800, function() {
                                $(this).remove();
                            });
                        } else {
                            bootbox.alert('Vous ne pouvez pas supprimer cet opérateur au risque de perdre les informations qu\'il à enrégistrer.').css('color', '#000');
                        }

                    }
                });
            }

        }).css('color', '#000');

    });
});