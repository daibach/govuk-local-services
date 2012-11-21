<h1><?php echo $service->description; ?> (LGSL <?php echo $service->id; ?>)</h1>

<div class="well">
  <table class="table">
    <thead>
      <tr class="hide">
        <th>Field</th>
        <th>Value</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><strong>Service provided by</strong></td>
        <td><?php echo format_providing_tiers($service->provided_district, $service->provided_county, $service->provided_unitary); ?></td>
      </tr>
    </tbody>
  </table>
</div>

<hr/>

<ul class="nav nav-pills">
  <li class="active"><a href="#urllist">Provided URLs</a></li>
  <li><a href="#missingauthorities">Missing authorities</a></li>
</ul>

<hr/>

<h2 id="urllist">Authorities providing URLs</h2>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Status</th>
      <th>SNAC</th>
      <th>Authority</th>
      <th>Interaction</th>
      <th>URL</th>
      <th>Status</th>
      <th>Really?</th>
      <th>Can 404?</th>
      <td>Tools</td>
    </tr>
  </thead>
  <tbody>
<?php foreach($urls as $url) : ?>
    <tr class="<?php echo will_url_be_used($url->type,$service->provided_district, $service->provided_county, $service->provided_unitary); ?>">
      <td>
        <?php
          switch($url->overall_status) {
            case 'unknown' : echo '<span class="label">Unknown</span>'; break;
            case 'warning' : echo '<span class="label label-warning">Warning</span>'; break;
            default: echo '&nbsp;';
          }
        ?>
      </td>
      <td><?php echo $url->snac; ?></td>
      <td><?php echo $url->authority; ?> (<?php echo $url->type; ?>)</td>
      <td><?php echo $url->interaction_short.' ('.$url->lgil.')'?></td>
      <td><a href="<?php echo $url->url; ?>" title="<?php echo $url->url; ?>"><?php echo ellipsize($url->url,35, 0.4); ?></a></td>
      <?php if($url->http_status) : ?>
        <td><span class="label label-<?php echo(get_status_result('http',$url->http_status)); ?>"><?php echo $url->http_status; ?>&nbsp;-&nbsp;<?php echo get_status_description($url->http_status); ?></span></td>
        <td>
          <?php
            switch($url->content_looks_like) {
              case 200 : echo '<span class="label label-success">Yes</span>'; break;
              case 404 : echo '<span class="label label-important">No - 404</span>'; break;
              case 500 : echo '<span class="label label-important">No - 50X</span>'; break;
              default: echo '<span class="label label-info">Unknown '.$url->content_looks_like.'</span>';
            }
          ?>
        </td>
        <td><?php if($url->can_404) { echo '<span class="label label-success">Yes</span>'; } else { echo '<span class="label label-important">No</span>'; }?></td>
      <?php else :?>
        <td><span class="label label-default">Unchecked</span></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      <?php endif; ?>
      <td><a href="<?php echo site_url(array('service-urls','history',$url->url_id)); ?>" class="btn">History</a></td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>

<hr/>

<h2 id="missingauthorities">Authorities not providing URLs</h2>

<table class="table table-striped">
  <thead>
    <tr>
      <th>SNAC</th>
      <th>Authority</th>
    </tr>
  </thead>
  <tbody>
<?php foreach($missing_authorities as $authority) : ?>
    <tr class="<?php echo will_url_be_used($authority->type,$service->provided_district, $service->provided_county, $service->provided_unitary); ?>">
      <td><?php echo $authority->snac; ?></td>
      <td><?php echo $authority->name; ?> (<?php echo $authority->type; ?>)</td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>