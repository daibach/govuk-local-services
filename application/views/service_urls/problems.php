<h1>Problem URLs</h1>

<?php if($problem_urls) :?>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>SNAC</th>
        <th>Authority</th>
        <th>Service</th>
        <th>Interaction</th>
        <th>URL</th>
        <th>Problem</th>
        <th>Tools</th>
      </tr>
    </thead>
    <tbody>
  <?php foreach($problem_urls as $url) : ?>
      <tr class="<?php echo will_url_be_used($url->authority_type,$url->provided_district, $url->provided_county, $url->provided_unitary); ?>">
        <td><a href="<?php echo site_url(array('authorities',$url->snac)); ?>"><?php echo $url->snac; ?></a></td>
        <td><?php echo $url->authority; ?> (<?php echo $url->authority_type; ?>)</td>
        <td><a href="<?php echo site_url(array('services','views',$url->lgsl)); ?>" title="<?php echo $url->service; ?>"><?php echo $url->lgsl; ?></a></td>
        <td><?php echo $url->interaction_short.' ('.$url->lgil.')'?></td>
        <td><a href="<?php echo $url->url; ?>" title="<?php echo $url->url; ?>"><?php echo ellipsize($url->url,35, 0.4); ?></a></td>
        <td>
          <?php if($url->http_status != 200) : ?>
            <span class="label label-important">Is <?php echo $url->http_status; ?></span>
          <?php elseif($url->content_looks_like != 200) : ?>
            <span class="label label-warning">Looks like <?php echo $url->content_looks_like; ?></span>
          <?php else : ?>
            <span class="label label-error">Unknown</span>
          <?php endif; ?>
        </td>
        <td><a href="<?php echo site_url(array('service-urls','history',$url->id)); ?>" class="btn">History</a></td>
      </tr>
  <?php endforeach; ?>
    </tbody>
  </table>
<?php else : ?>
  <p>There are no current problems</p>
<?php endif; ?>