console.log(1);
    console.log(12);

        $('#add-image').click(function () { // je recupere le numero des futurs champs que je vais créer
            const index = +$('#widgets-counter').val();
            // Je récupère le prototype des entrées

            const tmpl = $('#annonce_images').data('prototype').replace(/__name__/g, index);
            console.log(tmpl);

            // J'injecte ce code au sein de la div

            $('#annonce_images').append(tmpl);

            $('#widgets-counter').val(index + 1);
            

                // Je gère le bouton supprimer
                handleDeleteButtons();
            
            }
        }); 
        
        function handleDeleteButtons() {
                $('button[data-action="delete"]').click(function(){
                    const target = this.dataset.target;
                    console.log(target);
                    $(target).remove();
                });
        }
            function updateCounter() {
                const count = +$('$annonce_images div.form-group')
                $('#widgets-counter').val(count);
            }
        updateCounter();    
        handleDeleteButtons();