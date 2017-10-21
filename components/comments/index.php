<div id="top-divider" class="divider hide"></div>
    <p style="margin-bottom: 0;"><strong>Comentarios</strong></p>
    <div id="comments"></div>
<div id="bottom-divider" class="divider hide"></div>

<div id="comment-box" class="row hide">
    <div class="input-field col s12">
        <div class="comment-wrapper">
        	<div class="image-wrapper">
	            <img class="circle publish-image" src=<?= '"' . $_SESSION["Image"] . '"' ?>>
	        </div>
            <textarea id="comment" class="materialize-textarea" placeholder="Agregar un comentario..."></textarea>
        </div>
    </div>
    <a id="publish" class="btn-flat blue-text waves-effect disabled">Publicar</a>
</div>