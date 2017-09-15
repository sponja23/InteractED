<div id="top-divider" class="divider hide"></div>
    <div id="comments"></div>
<div id="bottom-divider" class="divider"></div>

<div id="comment-box" class="row">
    <div class="input-field col s12">
        <div class="comment-wrapper">
            <img class="circle publish-image" <?= 'src="' . $_SESSION["Image"] . '"' ?>>
            <textarea id="comment" class="materialize-textarea" placeholder="Agregar un comentario..."></textarea>
        </div>
    </div>
    <a id="publish" class="btn-flat blue-text waves-effect disabled">Publicar</a>
</div>