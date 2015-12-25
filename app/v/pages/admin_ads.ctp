
<div class="row">
    <form class="form">

        <div class="form-group form-inline">
            <select class="form-control" name="data[page_ads]" id="data[page_ads]">
                <option value="pages_top_ad">Pages Top Ad</option>
                <option value="pages_faucet_ad">Pages Faucet Ad</option>
                <option value="pages_bottom_ad">Pages Bottom Ad</option>
            </select>
        </div>

        <div class="form-group">
            <textarea id="data[page_ad]" name="data[page_ad]" class="form-control" rows="10">{{ @site_settings.pages_top_ad }}</textarea>
        </div>
        
        <div class="form-group">
            <button id="save" class="btn btn-primary">Save Ad</button>
        </div>
    </form>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Ad Preview</h3>
    </div>
    
    <div class="panel-body" id="preview">
        {{ @site_settings.pages_top_ad | raw }}
    </div>
</div>
