<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ @title }}</title>
    <meta name="keywords" content="WTF is a bitcoin , altcoin , virtual currency , faucet , free sotware" />
    <meta name="description" content="Lost faucet is a free downloadable software package to run a bitcoin/virtual currency faucet powered by fat-free-framework(F3) , bootstrap and faucetbox api." />
    <meta name="generator" content="faucet.is-lost.com" />
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ @BASE }}/css/default_flex.css" />
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->     
</head>

<body class="HolyGrail">
{* { pr(get_defined_vars()) } *}
    <include href="elements/header.ctp" />
    
    <div class="HolyGrail-body">
        <main class="HolyGrail-content">
            <div class="container">
            {{ stripos(@PATH , '/admin')!==false && @SESSION.admin ? '' : @site_settings.pages_top_ad | raw }}
            
            <include if="{{ @SESSION.flash }}" href="elements/message.ctp" />
            <include href="{{ 'elements/' . @nav }}" />
            <include href="{{ @content }}" />
            
            {{ stripos(@PATH , '/admin')!==false && @SESSION.admin ? '' : @site_settings.pages_bottom_ad | raw }}
            </div>
        </main>
        <!-- nav class="HolyGrail-nav">nav</nav -->
        <!-- aside class="HolyGrail-ads">ads</aside -->
    </div>
    
    <include href="elements/footer.ctp" />

    <repeat group="{{ @scripts }}" value="{{ @script }}">
        <script src="{{ @script }}"></script>
    </repeat>

    <check if="{{ @scriptBottom }}">
        <script src="{{ @BASE }}{{ @scriptBottom }}"></script>
    </check>    
</body>
</html>
<!-- {{ microtime(true) - @TIME }} -->