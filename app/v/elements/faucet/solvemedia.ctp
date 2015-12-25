<form class="form ohide" id="solvemedia" method="POST">
    <p>Visit <a href="http://solvemedia.com/publishers/">SolveMedia</a> to set up your api keys.</p>
    
        <div class="form-group">
        <label for="data[faucet_solvemedia_challenge_key]">Solvemedia Challenge Key : </label>
        <input type="text" class="form-control" id="data[faucet_solvemedia_challenge_key]" name="data[faucet_solvemedia_challenge_key]" value="{{ @site_settings.faucet_solvemedia_challenge_key }}" />
        </div>

        <div class="form-group">
        <label for="data[faucet_solvemedia_verification_key]">Solvemedia Verification Key : </label>
        <input type="text" class="form-control" id="data[faucet_solvemedia_verification_key]" name="data[faucet_solvemedia_verification_key]" value="{{ @site_settings.faucet_solvemedia_verification_key }}" />
        </div> 
    
        <div class="form-group">
        <label for="data[faucet_solvemedia_auth_key]">Solvemedia Auth Key : </label>
        <input type="text" class="form-control" id="data[faucet_solvemedia_auth_key]" name="data[faucet_solvemedia_auth_key]" value="{{ @site_settings.faucet_solvemedia_auth_key }}" />
        </div>     
</form>