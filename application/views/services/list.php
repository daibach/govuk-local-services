<h1>Service List</h1>

<table class="table table-striped">
  <thead>
    <tr>
      <th>LGSL code</th>
      <th>Service name</th>
      <th>Provided at</th>
    </tr>
  </thead>
  <tbody>
<?php foreach($services as $service) : ?>
    <tr>
      <td><a href="<?php echo site_url(array('services',$service->id)); ?>"><?php echo $service->id; ?></a></td>
      <td><?php echo $service->description; ?></td>
      <td><?php echo format_providing_tiers($service->provided_district, $service->provided_county, $service->provided_unitary); ?></td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>
