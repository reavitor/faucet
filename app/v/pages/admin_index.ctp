
<div class="row">
    <repeat group="{{ @files }}" value="{{ @file }}">
        
        <p><a href="{{ @BASE }}/admin/pages/edit?{{ @file }}"><span class="glyphicon glyphicon-file" aria-hidden="true"></span>{{ @file }}</a></p>
        
    </repeat>        
</div>

<div class="form-group">
    <button class="btn btn-default"><a href="{{ @BASE }}/admin/pages/edit?">New Page</a></button>    
</div>