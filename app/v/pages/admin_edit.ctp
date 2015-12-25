
    <form method="post" action="{{ @BASE }}/admin/pages/edit">
        
        <div class="form-group">
            <label for="data[file_name]">File Name : </label>
            <input type="text" class="form-control" name="data[file_name]" value="{{ @file_name}}" placeholder="somefile.ctp" />
        </div>
        
        <div class="form-group">
        <textarea class="form-control" name="data[file_content]" id="data[file_content]">{{ @file_content }}</textarea>
        </div>
        <!-- input type="submit" class="btn btn-primary" name="save" value="Save File" / --> 
    </form>