<h1>Local Authorities List</h1>

<table class="table table-striped">
  <thead>
    <tr>
      <th>SNAC code</th>
      <th>Council name</th>
      <th>Type</th>
      <th>Postcode</th>
      <th>Homepage</th>
    </tr>
  </thead>
  <tbody>
<?php foreach($authorities as $council) : ?>
    <tr>
      <td><a href="<?php echo site_url(array('authorities',$council->snac)); ?>"><?php echo $council->snac; ?></a></td>
      <td><?php echo $council->name; ?></td>
      <td><?php echo $council->type; ?></td>
      <td><?php echo $council->postcode; ?></td>
      <td><a href="<?php echo $council->homepage_url; ?>"><?php echo ellipsize($council->homepage_url,60, 0.4); ?></a></td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>
