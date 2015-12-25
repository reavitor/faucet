<div class="row">
    <p>Last Balance Check : {{ @site_settings.last_balance_check != '' ? date('Y-m-d h:i:s' , @site_settings.last_balance_check) : 'Never' }}</p>
    <include if="{{ @site_settings.api_key }} == ''" href="elements/admin/settings_fbapi.ctp">
        
    </include>
</div>

<div id="settings" class="row">
    <repeat group="{{ @settings }}" value="{{ @setting }}">
        <form id="setting_{{ @setting.param }}" class="form form-inline">
            <input type="text" class="form-control" style="width:49%;" name="data[param]" value="{{ @setting.param }}" readonly="readonly" />
            <input type="text" class="form-control" style="width:49%;" name="data[val]" value="{{ @setting.val }}" />
        </form>
    </repeat>
</div>


<div class="row">
    <div class="form-group">
    <button type="button" id="new_setting" class="btn btn-primary">New Setting</button>
    </div>
</div>
