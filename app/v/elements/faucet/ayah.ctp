<form class="form ohide" id="ayah" method="POST">
    <p>Visit <a href="http://areyouahuman.com/">Are You a Human</a> to set up your api keys.</p>
        <div class="form-group">
        <label for="data[faucet_ayah_publisher_key]">Are You Human Publisher Key : </label>
        <input type="text" class="form-control" id="data[faucet_ayah_publisher_key]" name="data[faucet_ayah_publisher_key]" value="{{ @site_settings.faucet_ayah_publisher_key}}" />
        </div>

        <div class="form-group">
        <label for="data[faucet_ayah_scoring_key]">Are You Human Scoring Key : </label>
        <input type="text" class="form-control" id="data[faucet_ayah_scoring_key]" name="data[faucet_ayah_scoring_key]" value="{{ @site_settings.faucet_ayah_scoring_key}}" />
        </div>   
    </form>