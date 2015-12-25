<div class="row" id="fb_api">
    <div class="form-group form-inline">
        <label for="data[api_key]">Faucet in a box API key </label>
        <input class="form-control" type="text" id="data[api_key]" name="data[api_key]" value="{{ @site_settings.api_key }}" size="64" />
        
        <label for="data[api_key]">Default Currency </label>
        <select class="form-control" name="data[currency]" id="data[currency]">
            <repeat group="{{ @currencies }}" value="{{ @currency }}">
                <option value="{{ @currency }}" {{ @site_settings.currency==@currency ? 'selected' : '' }}>{{ @currency }}</option>
            </repeat>
        </select>
    </div>
</div>

<div class="row">    
    <p>Last faucet check : {{ @site_settings.last_balance_check }} ({{ date('m-d-Y h:i:s' , @site_settings.last_balance_check)}})</p>
    <p>Faucet balance : {{ @site_settings.balance }}</p>
</div>

<div class="row" id="captchas">
    <div class="form-group form-inline">        
        <label for="data[default_captcha]">Default Captcha </label>
        <select class="form-control" name="data[default_captcha]" id="data[default_captcha]">
            <repeat group="{{ @faucets }}" key="{{ @name }}" value="{{ @faucet }}">
                <option value="{{ @faucet }}" {{ @site_settings.default_captcha==@faucet ? 'selected' : '' }} >{{ @name }}</option>
            </repeat>
        </select>
    </div>

    <div class="well">
    <repeat group="{{ @faucets }}" key="{{ @name }}" value="{{ @faucet }}">
        <include href="{{ 'elements/faucet/' . @faucet . '.ctp' }}" />
    </repeat>
    </div>
        
    <div class="form-group">
        <button class="btn btn-default" id="save_faucet">Save Settings</button>
    </div>
</div>

