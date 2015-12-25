{* { pr(@faucets) } *}
<div class="well">
    <p class="lead">Get even MORE satoshi's at these faucets !!!</p>
<repeat group="{{ @faucets }}" value="{{ @faucet }}">
    <p><a href="{{ @faucet[1] }}" target="_blank">{{ @faucet[0] }}</a></p>
</repeat>
</div>    