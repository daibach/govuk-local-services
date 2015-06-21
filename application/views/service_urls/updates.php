<h1>Recent URL updates</h1>

<?php if(! empty($dates)) : ?>
<p>This service has imported updated URLs on the following dates:</p>
<ul>
  <?php foreach($dates as $date) : ?>
    <li><a href="<?php echo site_url(array('service-urls','recent-updates',$date->d)); ?>"><?php echo $date->d; ?></a></li>
  <?php endforeach; ?>
</ul>
<?php else : ?>
  <div class="alert alert-info"><span class="lead">There have been no URL updates</span></div>
<?php endif; ?>
