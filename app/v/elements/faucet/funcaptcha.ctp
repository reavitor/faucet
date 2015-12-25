<form class="form ohide" id="funcaptcha" method="POST">
    <p>Visit <a href="https://www.funcaptcha.com/">Fun Captcha</a> to set up your api keys.</p>
        <div class="form-group">
        <label for="data[faucet_funcaptcha_public_key]">FunCaptcha Public Key : </label>
        <input type="text" class="form-control" id="data[faucet_funcaptcha_public_key]" name="data[faucet_funcaptcha_public_key]" value="{{ @site_settings.faucet_funcaptcha_public_key }}" />
        </div>

        <div class="form-group">
        <label for="data[faucet_funcaptcha_private_key]">FunCaptcha Private Key : </label>
        <input type="text" class="form-control" id="data[faucet_funcaptcha_private_key]" name="data[faucet_funcaptcha_private_key]" value="{{ @site_settings.faucet_funcaptcha_private_key }}" />
        </div>   
    </form>