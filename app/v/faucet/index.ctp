{* { pr([$_SESSION , $_COOKIE]) } *}
<div class="row-flex">
    <div class="col-md-8">
        <form id="faucet" class="form"  method="post" action="{{ @BASE }}/faucet">
            <input type="hidden" name="faucet[time]" id="faucet[time]" />
            <p class="lead">Possible Rewards:
                <repeat group="{{ @rewards }}" value="{{ @reward }}">
                    <span> {{ @reward.satoshi }} ({{ @reward.chance }}%) </span>
                </repeat>
            <span class="pull-right"> every {{ @site_settings.timer }} minute(s)</span>
            </p>

            <div class="form-group">
                <label for="faucet[address]">Address:</label>
                <input type="text" class="form-control" id="faucet[address]" name="faucet[address]" value="{{ @SESSION.address }}" placeholder="19wyy7r7E7yurNWnar7j7jKx1Y8n8inTsK" size="48" />
            </div>

            <div class="text-center">
                <div class="form-group">
                    {{ @captcha | raw }}
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-primary"  id="get_satoshi">Get Satoshi</button>
                    <script type="text/javascript">
                        var endtime = {{ @SESSION.u ? : 0 }};
                    </script>
                    <div id="clock"></div>
                </div>

                <p>
                    Referrals get {{ @site_settings.referral }}% commission.<br />
                    <a href="{{ @SCHEME }}://{{ @HOST }}{{ @BASE }}/?r={{ @SESSION.address}}">{{ @SCHEME }}://{{ @HOST }}{{ @BASE }}/?r={{ @SESSION.address}}</a>
                </p>
            </div>       
        </form>
    

    </div>
    
    <div class="col-md-4 text-center" id="right_ad">

        {{ @site_settings.pages_faucet_ad | raw }}

    </div>
    
</div>


    