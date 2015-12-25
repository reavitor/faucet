<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ @title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/default.css" />
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->    
</head>

<body role="document">

    <include href="elements/header.ctp" />
    
    
        <main class="container" role="main">
            
            {{ stripos(@PATH , '/admin')!==false && @SESSION.user ? '' : @site_settings.pages_top_ad | raw }}
            
            <include if="{{ @SESSION.flash }}" href="elements/message.ctp" />
            <include href="{{ 'elements/' . @nav }}" />
            <include href="{{ @content }}" />
            
            {{ stripos(@PATH , '/admin')!==false && @SESSION.user ? '' : @site_settings.pages_bottom_ad | raw }}
            
        </main>
        <!-- nav class="HolyGrail-nav">nav</nav -->
        <!-- aside class="HolyGrail-ads">ads</aside -->
    
    
    <include href="elements/footer.ctp" />

    <repeat group="{{ @scripts }}" value="{{ @script }}">
        <script src="{{ @script }}"></script>
    </repeat>

    <check if="{{ @scriptBottom }}">
        <script src="{{ @BASE }}{{ @scriptBottom }}"></script>
    </check>    
</body>
</html>
<!-- {{ @PAGE_RENDERED }} -->
