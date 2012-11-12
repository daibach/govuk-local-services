<h1>Licence List</h1>

<div class="alert alert-error">
  <h4>WARNING! This is currently using testing data and is inaccurate!</h4>
</div>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Status</th>
      <th>Licence ID</th>
      <th style="width: 350px;">Name</th>
      <th>Transaction URL</th>
      <th>Type</th>
    </tr>
  </thead>
  <tbody>
<?php foreach($licences as $licence) : ?>
    <tr>
      <td>
        <?php
          switch($licence->overall_status) {
            case 'unknown' : echo '<span class="label">Unknown</span>'; break;
            case 'warning' : echo '<span class="label label-warning">Warning</span>'; break;
            default: echo '&nbsp;';
          }
        ?>
      </td>
      <td><?php echo $licence->licence_identifier; ?></td>
      <td><a href="https://www.gov.uk/<?php echo $licence->slug; ?>"><?php echo $licence->name; ?></a></td>
      <td>
        <?php if($licence->licence_type == 'unknown') :?><span class="label">Unknown</span>
        <?php elseif($licence->licence_type != 'non-local') : ?>n/a
        <?php elseif($licence->licence_type == 'non-local' && $licence->transaction_url == '') : ?><span class="label label-important">Not provided</span>
        <?php else : ?><a href="<?php echo $licence->transaction_url; ?>" title="<?php echo $licence->transaction_url; ?>"><?php echo ellipsize($licence->transaction_url,35, 0.4); ?></a>
        <?php endif; ?>
      </td>
      <td><?php echo format_licence_type($licence->licence_type); ?></td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>
