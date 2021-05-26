//création de 3 éléments HTMLElement    
var $addCollectionButton = $('<button type="button" class="add_collection_link">Ajouter Module</button>');
var $delCollectionButton = $('<button type="button" class="del_collection_link">Supprimer</button>');
//le premier élément li de la liste (celui qui contient le bouton 'ajouter')
var $newLinkLi = $('<li></li>').append($addCollectionButton);

function generateDeleteButton(){
    var $btn = $delCollectionButton.clone();
    $btn.on("click", function(){//événement clic du bouton supprimer
        $(this).parent("li").remove();
        $collection.data('index', $collection.data('index')-1)
    })
    return $btn;
}
//fonction qui ajoute un nouveau champ li (en fonction de l'entry_type du collectionType) dans la collection
function addCollectionForm($collection, $newLinkLi) {
    
    //contenu du data attribute prototype qui contient le HTML d'un champ
    var newForm = $collection.data('prototype');
    //le nombre de champs déjà présents dans la collection
    var index = $collection.data('index');
    //on remplace l'emplacement prévu pour l'id d'un champ par son index dans la collection
    newForm = newForm.replace(/__name__/g, index);
    //on modifie le data index de la collection par le nouveau nombre d'éléments
    $collection.data('index', index+1);
    //on construit l'élément li avec le champ et le bouton supprimer
    var $newFormLi = $('<li></li>').append(newForm).append(generateDeleteButton());
    //on ajoute la nouvelle li au dessus de celle qui contient le bouton "ajouter"
    $newLinkLi.before($newFormLi);
}
//rendu de la collection au chargement de la page
$(document).ready(function() {
    //on pointe la liste complete (le conteneur de la collection)
    var $collection = $("ul#modules")
    //on y ajoute le bouton ajouter (à la fin du contenu)
    $collection.append($newLinkLi);
    //pour chaque li déjà présente dans la collection (dans le cas d'une modification)
    $(".module").each(function(){
        //on génère et ajoute un bouton "supprimer"
        $(this).append(generateDeleteButton());
    })
    //le data index de la collection est égal au nombre de input à l'intérieur
    $collection.data('index', $collection.find(':input').length);
    $addCollectionButton.on('click', function(e) { // au clic sur le bouton ajouter
        //si la collection n'a pas encore autant d'élément que le maximum autorisé
       // if($collection.data('index') < $("input[maxNb]").val()){
            //on appelle la fonction qui ajoute un nouveau champ
            addCollectionForm($collection, $newLinkLi);
       //  }
       //   else alert("Nb max atteint !")
    });
});