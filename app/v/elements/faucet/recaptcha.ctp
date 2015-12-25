<form class="form ohide" id="recaptcha" method="POST">
    <p>Visit <a href="https://www.google.com/recaptcha/intro/index.html">ReCaptcha</a> to set up your api keys.</p>
    
        <div class="form-group">
        <label for="data[faucet_recaptcha_public_key]">ReCaptcha Public Key : </label>
        <input type="text" class="form-control" id="data[faucet_recaptcha_public_key]" name="data[faucet_recaptcha_public_key]" value="{{ @site_settings.faucet_recaptcha_public_key}}" />
        </div>

        <div class="form-group">
        <label for="data[faucet_recaptcha_private_key]">ReCaptcha Private Key : </label>
        <input type="text" class="form-control" id="data[faucet_recaptcha_private_key]" name="data[faucet_recaptcha_private_key]" value="{{ @site_settings.faucet_recaptcha_private_key}}" />
        </div>    
    </form>