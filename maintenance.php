<?php
$protocol = $_SERVER["SERVER_PROTOCOL"];
if ( 'HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol )
        $protocol = 'HTTP/1.0';
header( "$protocol 503 Service Unavailable", true, 503 );
header( 'Content-Type: text/html; charset=utf-8' );
?>
<!DOCTYPE
 html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title><?php echo SITE_NAME; ?> - Website Maintenance</title>
<style type="text/css">
html, body, div, span, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, code, del, dfn, em, img, q, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td {margin:0;padding:0;border:0;font-weight:inherit;font-style:inherit;font-size:100%;font-family:inherit;vertical-align:baseline;}
body {line-height:1.5;}
h1, h2, p, div { font-family: arial, sans-serif; color: #fff; }
body, html {
    background: #23282f url(../images/head-bg.gif) repeat-x top left;
}
h1 { font-size: 2.0em; font-weight: bold; color: #fff; padding: 15px 0 10px 0; }
#header { background: transparent url(../images/swirl.gif) no-repeat top right; padding: 30px 0 10px 0; }
#header .content { width: 900px; padding: 0 10px; margin: auto auto; }
h2 { color: #Fff; font-size: 1.5em; font-weight: bold; margin-bottom: 10px;}
#content { padding: 10px 0; width: 920px; margin: auto auto; }
#content .content { padding: 0 10px; }
#content .content p { margin-bottom: 20px; }
#content .content a { color: #72C5EE; }
#content .content a:hover { color: #fff; }
.clear { clear:both; }

</style>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>
<body>

<div id="header">
  <div class="content">
    <h1><?php echo SITE_NAME; ?></h1>
  </div>
</div>
<div id="content">
  <div class="content">
    <h2>Website Maintenance</h2>
    <p>We are currently undertaking maintenance on the <?php echo SITE_NAME; ?> website.</p>
    <p>We apologise for any inconvenience this may cause.</p>
  </div>
  <div class="clear"></div>
</div>
<div class="clear"></div>
</body>
</html>
<?php die(); ?>
