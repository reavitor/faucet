<form class="form ohide" id="captchme" method="POST">
    <p>Visit <a href="http://www.captchme.com/en/">CaptchMe</a> to set up your api keys.</p>
        <div class="form-group">
        <label for="data[faucet_captchme_public_key]">CaptchMe Publisher Key : </label>
        <input type="text" class="form-control" id="data[faucet_captchme_public_key]" name="data[faucet_captchme_public_key]" value="{{ @site_settings.faucet_captchme_public_key }}" />
        </div>

        <div class="form-group">
        <label for="data[faucet_captchme_private_key]">CaptchMe Private Key : </label>
        <input type="text" class="form-control" id="data[faucet_captchme_private_key]" name="data[faucet_captchme_private_key]" value="{{ @site_settings.faucet_captchme_private_key }}" />
        </div>
        
        <div class="form-group">
        <label for="data[faucet_captchme_authentication_key]">CaptchMe Authentication Key : </label>
        <input type="text" class="form-control" id="data[faucet_captchme_authentication_key]" name="data[faucet_captchme_authentication_key]" value="{{ @site_settings.faucet_captchme_authentication_key }}" />
        </div>        
    </form>