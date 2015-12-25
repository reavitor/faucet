<div class="alert alert-{{ @SESSION.flash.type }} alert-dismissible fade in">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    
    {{ @SESSION.flash.message | raw }}
</div>