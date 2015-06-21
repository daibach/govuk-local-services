<h1>About the GOV.UK Local Services Explorer</h1>

<p><a href="https://www.gov.uk">GOV.UK</a> provides many links to local
  services run by local government in the UK (for example
  <a href="http://www.gov.uk/pay-council-tax">pay your council tax</a>). You
  can read more about how the links to these services are managed on the
  <a href="http://digital.cabinetoffice.gov.uk/2012/02/14/local-services-and-gov-uk/">Government
  Digital Service blog</a>.</p>

<p>This app imports the daily feed of URLs (there are somewhere in the region
  of 94 thousand of them), keeps a record of the changes to them, runs them
  through a series of tests and produces a list of URLs that have problems.</p>


<hr/>

<ul class="nav nav-pills">
  <li class="active"><a href="#thetests">The URL tests</a></li>
  <li><a href="#thelists">The lists &amp; views</a></li>
  <li><a href="#thereports">The reports</a></li>
  <li><a href="#dataupdates">Data updates</a></li>
  <li><a href="#limitations">Limitations</a></li>
  <li><a href="#nations">Wales/Scotland/Northern Ireland</a></li>
  <!--<li><a href="#licences">Licences</a></li>-->
</ul>

<hr/>

<h2 id="thetests">The tests</h2>
<p>Each URL is run through the following tests:</p>
<ul>
  <li><strong>Does the URL work?</strong> - This tests the
    <a href="http://en.wikipedia.org/wiki/List_of_HTTP_status_codes">HTTP
    status</a> code of the URL. If it is not a 200, it is flagged as a problem
    URL.</li>
  <li><strong>Does the URL look broken?</strong> - Lots of local authority
    websites aren't configured to return a 404 status code when a page doesn't
    exist. This test looks for content like 'Page can't be found' and marks it
    as a problem URL</li>
  <li><strong>Can the website return a 404?</strong> - Related to the previous
    test, this one creates a jumbled URL based on the original and checks to
    see if it  returns a 404. This test is very rough around the edges and is
    used as an indicator only.</li>
</ul>

<hr/>

<h2 id="thelists">The lists &amp; views</h2>

<p>There are various views through which you can explore the local services
  data:</p>
<ul>
  <li>by <a href="<?php echo site_url('services'); ?>"><strong>service</strong></a>
    - this lets you view the data by the
    <a href="http://doc.esd.org.uk/ServiceList/4.00.html">Local Government
    Service List (LGSL)</a> that contains everything local government does.
    <em>Note that we only show the services that we use on GOV.UK.</em></li>
  <li>by <a href="<?php echo site_url('authorities');?>"><strong>local
    authority</strong></a> - this lets you view the data by each part of local
    government. <em>Note that we only show the local authorities that have
    provided data.</em></li>
</ul>

<p>For each list (whether service or local authority), we show all of the URLs
  that have been provided along with the result of their tests and which
  <a href="http://doc.esd.org.uk/InteractionList/1.01.html">interaction</a>
  type they are.</p>

<p>We will also provide a list of services/local authorities that are missing
  (i.e. there hasn't been a URL provided).</p>

<h3>Why are some rows in the lists greyed out?</h3>
<p>For each service on GOV.UK, we maintain a mapping to show which level of
  local government is responsible for providing it.  In parts of the UK, there
  is only one level of local government - in that case, we assume all services
  are provided by that authority. In other parts, there are two tiers - a
  district and a county.</p>
<p>Based on the GOV.UK mapping, we grey out URLs provided by local authorities
  that we don't think actually provide that service.</p>

<hr/>

<h2 id="thereports">The reports</h2>
<p>This app currently publishes 2 reports:</p>
<ul>
  <li><a href="<?php echo site_url('service-urls/problem-urls');?>">problem
    URLs</a> - this lists every URL that, based on the tests conducted, we
    think has a problem. <em>Note that we don't include those that fail the
    jumbled URL test in this report.</em></li>
  <li><a href="<?php echo site_url('check-queues'); ?>">check queues</a> -
    this lists the current status of the test queues. Normally this should be
    empty, but could be big on the weekends when we do a full check of all
    94 thousand URLs.</li>
</ul>

<hr/>
<h2 id="dataupdates">Data updates</h2>
<p>The data in this app gets refreshed fairly regularly.<p>
<ul>
  <li>Every morning we import updated local authority contact details,
    the GOV.UK service/council tier mappings and any updated URLs. We then
    trigger a test of any new/updated URLs.</li>
  <li>Every Saturday we trigger a full check of all URLs (even if they haven't
    changed during the week).</li>
</ul>

<hr/>
<h2 id="nations">Wales / Scotland / Northern Ireland</h2>
<p>You might notice that there are no local authorities from Wales, Scotland
  or Northern Ireland in this dataset. Unfortunately, councils from these
  countries don't participate in the Local Directgov programme and so we don't
  get links for those services.</p>

<p>We're looking at how best to curate links from those councils.</p>

<hr/>
<h2 id="limitations">Limitations</h2>
<p>This app has a couple of limitations:</p>
<ul>
  <li>It's not an official GOV.UK/GDS project, so stuff will only get added or
    improved when I have spare time</li>
  <li>It's running from my own personal server, so sorry if it's slow!</li>
  <li>There isn't any monitoring to check that automated things are running</li>
</ul>

<!--
<hr/>
<h2 id="licences">Licences</h2>
<p>Tests and reports for licences are much more experimental than those for
  local services. They also don't use live data as information isn't available
  to download/access via an API at the moment.</p>
<p>I'll update this documentation when licences have received some more love.</p>
-->
