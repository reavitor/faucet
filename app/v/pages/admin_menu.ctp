
<repeat group="{{ @files }}" value="{{ @file }}">
    <p><a href="{{ @BASE }}/admin/pages/menu?{{ @file }}"><span class="glyphicon glyphicon-file" aria-hidden="true"></span>{{ @file }}</a></p>
</repeat>

<check if="{{ @file_name }}">      
    <form method="post" action="{{ @BASE }}/admin/pages/menu">
        
        <div class="form-group">
        <input type="text" class="form-control" name="data[file_name]" value="{{ @file_name}}" />
        </div>
        
        <div class="form-group">
        <textarea class="form-control" name="data[file_content]" id="data[file_content]" rows="10">{{ @file_content }}</textarea>
        </div>
        
        <input type="submit" class="btn btn-primary" name="save" value="Save File" /> 
    </form>
</check>