<?php
  if ($this->session->flashdata('report_success')) {
    echo "<div class='alert alert-success'>\n";
    echo $this->session->flashdata('report_success');
    echo "\n</div>\n";
  }
?>

<h1>URL <?php echo $url->id; ?><br/><span>(<?php echo ellipsize($url->url,100, 0.4); ?>)</span></h1>

<?php if($url->overall_status == 'warning') : ?>
  <div class="alert alert-error">
    <h4>WARNING! This URL has issues:</h4>
    <ul>
      <?php if($url->http_status != 200) :?><li>URL is a 404</li><?php endif; ?>
      <?php if($url->content_looks_like != 200) :?><li>Content looks broken</li><?php endif; ?>
      <?php if($url->has_reported_problems) :?><li>Users have reported problems</li><?php endif;?>
    </ul>
  </div>
<?php endif; ?>

<hr/>

<ul class="nav nav-pills">
  <li class="active"><a href="#intro">Introduction &amp; Summary</a></li>
  <li><a href="#checks">URL Checks</a></li>
  <li><a href="#reportedproblems">Reported problems</a></li>
  <li><a href="#urlchanges">URL Changes</a></li>
</ul>

<hr/>

<div class="row" id="intro">
  <div class="span8">
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
            <td><strong>Service</strong></td>
            <td><a href="<?php echo site_url(array('services',$url->lgsl));?>"><?php echo $url->description; ?> (<?php echo $url->lgsl; ?>)</a></td>
          </tr>
          <tr>
            <td><strong>Action</strong></td>
            <td><?php echo $url->interaction_name; ?> (<?php echo $url->lgil; ?>)</td>
          </tr>
          <tr>
            <td><strong>Authority</strong></td>
            <td><a href="<?php echo site_url(array('authorities',$url->snac));?>"><?php echo format_flag($url->authority_country);?> <?php echo $url->snac; ?> <?php echo $url->authority_name; ?></a> (<?php echo $url->authority_type; ?>)</td>
          </tr>
        </tbody>
      </table>
    </div>
    <p><a href="<?php echo site_url(array('service-urls','report',$url->id)); ?>" class="btn btn-warning">Report a problem</a> with this URL</p>
  </div>
  <div class="span4">
    <div class="well">

      <table class="table">
        <thead>
          <tr class="hide">
            <th>Field</th>
            <th>Value</th>
          </tr>
        </thead>
        <tbody>
          <?php if($url->last_tested == '0000-00-00 00:00:00') : ?>
            <tr>
              <td>Last tested</td>
              <td>Currently unchecked</td>
            </tr>
          <?php else : ?>
            <tr>
              <td>Last tested</td>
              <td><?php echo date('d-M-Y H:i:s',mysql_to_unix($url->last_tested)); ?></td>
            </tr>
            <tr>
              <td>HTTP status</td>
              <td><span class="label label-<?php echo(get_status_result('http',$url->http_status)); ?>"><?php echo $url->http_status; ?>&nbsp;-&nbsp;<?php echo get_status_description($url->http_status); ?></span></td>
            </tr>
            <tr>
              <td>Really?</td>
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
            </tr>
            <tr>
              <td>Can 404?</td>
              <td><?php if($url->can_404) { echo '<span class="label label-success">Yes</span>'; } else { echo '<span class="label label-important">No</span>'; }?></td>
            </tr>
            <tr>
              <td>Has reported problems?</td>
              <td><?php if($url->has_reported_problems) { echo '<span class="label label-important">Yes</span>'; } else { echo '<span class="label label-success">No</span>'; }?></td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<hr/>

<h2 id="urlchecks">URL check history</h2>
<?php if($urlchecks) : ?>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Date</th>
        <th>URL</th>
        <th>Status</th>
        <th>Really?</th>
        <th>Jumbled URL</th>
        <th>Can 404?</th>
      </tr>
    </thead>
    <tbody>
  <?php foreach($urlchecks as $check) : ?>
      <tr>
        <td><?php echo date('d-M-Y H:i:s',mysql_to_unix($check->created_date)); ?></td>
        <td><a href="<?php echo $check->normal_url; ?>" title="<?php echo $check->normal_url; ?>"><?php echo ellipsize($check->normal_url,35, 0.4); ?></a></td>
        <td><span class="label label-<?php echo(get_status_result('http',$check->http_status)); ?>"><?php echo $check->http_status; ?>&nbsp;-&nbsp;<?php echo get_status_description($check->http_status); ?></span></td>
        <td>
          <?php
            switch($check->looks_like) {
              case 200 : echo '<span class="label label-success">Yes</span>'; break;
              case 404 : echo '<span class="label label-important">No - 404</span>'; break;
              case 500 : echo '<span class="label label-important">No - 50X</span>'; break;
              default: echo '<span class="label label-info">Unknown '.$check->looks_like.'</span>';
            }
          ?>
        </td>
        <td><a href="<?php echo $check->jumbled_url; ?>" title="<?php echo $check->jumbled_url; ?>"><?php echo ellipsize($check->jumbled_url,35, 0.4); ?></a></td>
        <td><?php if($check->jumbled_http_status) { echo '<span class="label label-success">Yes</span>'; } else { echo '<span class="label label-important">No</span>'; }?></td>
      </tr>
  <?php endforeach; ?>
    </tbody>
  </table>
<?php else : ?>
<p>This URL has not yet been checked</p>
<?php endif; ?>

<hr/>

<h2 id="reportedproblems">Reported problems</h2>

<?php if($reports) : ?>
<table class="table table-striped">
  <thead>
    <tr>
      <th>Status</th>
      <th>Date</th>
      <th>Type</th>
      <th>Notes</th>
      <th>Alternative URL</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($reports as $problem) : ?>
      <tr>
        <td>
          <?php
            switch($problem->status) {
              case 'open' : echo '<span class="label label-important">Open</span>'; break;
              case 'superseded' : echo '<span class="label label-inverse">Superseded</span>'; break;
              case 'closed' : echo '<span class="label">Closed</span>'; break;
              default: echo '<span class="label label-info">Unknown '.$problem->status.'</span>';
            }
          ?>
        </td>
        <td><?php echo date('d-M-Y H:i:s',mysql_to_unix($problem->created_date)); ?></td>
        <td><?php echo $problem->report_type; ?></td>
        <td><?php echo $problem->notes; ?>
        <?php if($problem->alternative_url) : ?>
          <td><a href="<?php echo $problem->alternative_url; ?>" title="<?php echo $problem->alternative_url; ?>"><?php echo ellipsize($problem->alternative_url,50, 0.4); ?></a></td>
        <?php else : ?>
          <td>n/a</td>
        <?php endif; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else : ?>
  <p>This URL has no reports</p>
<?php endif; ?>

<hr/>

<h2 id="urlchanges">URL update history</h2>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Date</th>
      <th>URL</th>
      <th>Updated to</th>
    </tr>
  </thead>
  <tbody>
    <?php if($urlhistory) : ?>
      <?php foreach($urlhistory as $history) : ?>
        <tr>
          <td><?php echo date('d-M-Y H:i:s',mysql_to_unix($history->created_date)); ?></td>
          <td><a href="<?php echo $history->original; ?>" title="<?php echo $history->original; ?>"><?php echo ellipsize($history->original,50, 0.4); ?></a></td>
          <td><a href="<?php echo $history->new; ?>" title="<?php echo $history->new; ?>"><?php echo ellipsize($history->new,50, 0.4); ?></a></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
    <tr>
      <td><?php echo date('d-M-Y H:i:s',mysql_to_unix($url->created_date)); ?></td>
      <td>URL first seen</td>
      <td><a href="<?php echo $url->url; ?>" title="<?php echo $url->url; ?>"><?php echo ellipsize($url->url,50, 0.4); ?></a></td>
    </tr>
  </tbody>
</table>
