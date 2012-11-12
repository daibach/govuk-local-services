<div class="introduction">
  <h1>GOV.UK Local Services Explorer</h1>
  <p class="lead">An app to explore and track the URLs used for local services on GOV.UK</p>
</div>

<hr/>

<div class="alert">This app is a work in progress - probably pre-alpha!</div>

<p><a href="https://www.gov.uk">GOV.UK</a> provides many links to local services run by local
  government in the UK (for example <a href="http://www.gov.uk/pay-council-tax">pay your
  council tax</a>). You can read more about how the links to these services are managed on the
  <a href="http://digital.cabinetoffice.gov.uk/2012/02/14/local-services-and-gov-uk/">Government
  Digital Service blog</a>.</p>

<p>This app imports the daily feed of URLs (there are somewhere in the region of 94 thousand of
  them) and runs them through a series of tests, including:</p>
<ul>
  <li>is the URL working (e.g. is it a 404)?</li>
  <li>is the URL reporting that it works, but is actually a 404? (some council websites don't use real 404s)?</li>
  <li>is the website configured to return 404s? (this test is really crude, but useful)</li>
</ul>

<p>The app also keeps a record of the changes to the URLs, and produces lists of URLs that have
  problems.</p>

<p>You can find out a bit more about this app on the <a href="<?php echo site_url('about');?>">about page</a>.</p>
