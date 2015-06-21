<h1>URLs updated on <?php echo $date; ?></h1>

<?php if(! empty($urls)) : ?>
<table class="table">
  <thead>
    <tr>
      <th>SNAC</th>
      <th>Council</th>
      <th>LGSL</th>
      <th>LGIL</th>
      <th>Old URL</th>
      <th>New URL</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($urls as $url) : ?>
    <tr>
      <td><a href="<?php echo site_url(array('authorities',$url->snac)); ?>"><?php echo $url->snac; ?></a></td>
      <td><?php echo format_flag($url->country);?> <?php echo $url->name; ?></td>
      <td><a href="<?php echo site_url(array('services',$url->lgsl)); ?>" title="<?php echo $url->description; ?>"><?php echo $url->lgsl; ?></a></td>
      <td><?php echo $url->shortname.' ('.$url->lgil.')'?></td>
      <td><a href="<?php echo $url->original; ?>"><?php echo ellipsize($url->original,30, 0.4); ?></a></td>
      <td><a href="<?php echo $url->new; ?>"><?php echo ellipsize($url->new,30, 0.4); ?></a></td>
      <td>
        <?php
          switch($url->overall_status) {
            case 'unknown' : echo '<span class="label">Unknown</span>'; break;
            case 'warning' : echo '<span class="label label-warning">Warning</span>'; break;
            default: echo '&nbsp;';
          }
        ?>
      </td>
      <td><a href="<?php echo site_url(array('service-urls','history',$url->id)); ?>" class="btn">History</a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else : ?>
  <div class="alert alert-info"><span class="lead">There were no URLs updated on <?php echo $date; ?></span></div>
<?php endif; ?>
