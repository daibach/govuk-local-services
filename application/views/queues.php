<h1>Check Queue Status</h1>

<hr />

<ul class="nav nav-pills">
  <li class="active"><a href="#servicechecks">Service Check Queue</a></li>
  <li><a href="#importchecks">Import Check Queue</a></li>
</ul>

<hr/>

<h2 id="servicechecks">Service Check Queue</h2>
<p>Full checks of local services</p>

<?php if($service_check_queue) : ?>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>LGSL</th>
        <th>Service</th>
        <th>Locked</th>
        <th>Created Date</th>
      </tr>
    </thead>
    <tbody>
  <?php foreach($service_check_queue as $service) : ?>
      <tr>
        <td><a href="<?php echo site_url(array('services',$service->lgsl)); ?>"><?php echo $service->lgsl; ?></a></td>
        <td><?php echo $service->description; ?></td>
        <td><?php echo $service->locked; ?></td>
        <td><?php echo date('d-M-Y H:i:s',mysql_to_unix($service->created_date)); ?></td>
      </tr>
  <?php endforeach; ?>
    </tbody>
  </table>
<?php else : ?>
  <div class="alert alert-info">There are no service checks queued</div>
<?php endif; ?>

<hr />

<h2 id="importchecks">Import Check Queue</h2>
<p>Checks of URLs changed on an import</p>

<?php if($import_check_queue) : ?>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Import</th>
        <th>Locked</th>
        <th>Created Date</th>
      </tr>
    </thead>
    <tbody>
  <?php foreach($import_check_queue as $import) : ?>
      <tr>
        <td><?php echo $import->import; ?></td>
        <td><?php echo $import->locked; ?></td>
        <td><?php echo date('d-M-Y H:i:s',mysql_to_unix($import->created_date)); ?></td>
      </tr>
  <?php endforeach; ?>
    </tbody>
  </table>
<?php else : ?>
  <div class="alert alert-info">There are no import checks queued</div>
<?php endif; ?>