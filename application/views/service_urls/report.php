<h1>Report a problem with URL <?php echo $url->id; ?><br/><span>(<?php echo ellipsize($url->url,100, 0.4); ?>)</span></h1>

<hr/>

<?php
  if ($this->session->flashdata('error')) {
    echo "<div class='alert alert-error'>\n";
    echo $this->session->flashdata('error');
    echo "\n</div>\n";
  }
  if (validation_errors()) {
    echo "<div class='alert alert-error'>\n";
    echo "  <h4>Sorry, something went wrong:</h4>\n";
    echo "  <ul>\n";
    echo validation_errors();
    echo "  </ul>\n";
    echo "</div>\n";
  }
  if ($this->session->flashdata('success')) {
    echo "<div class='alert alert-success'>\n";
    echo $this->session->flashdata('success');
    echo "\n</div>\n";
  }
?>

<?php $hidden = array(
  'url_id' => $url->id
);
echo form_open(
  site_url(array('service-urls','log-report',$url->id)),
  array('class'=>'form-horizontal'),
  $hidden
);?>
  <div class="control-group">
    <label class="control-label" for="inputProblemType">Type of problem</label>
    <div class="controls">
      <?php $options = array(
        'broken'    => 'Broken URL',
        'wrong_url' =>'Incorrect URL',
        'other'     =>'Other'
      ); echo form_dropdown(
        'inputProblemType',
        $options,
        set_value('inputProblemType'),
        'id="inputProblemType"'); ?>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputNotes">Notes</label>
    <div class="controls">
      <?php $options = array(
        'id'    => "inputNotes",
        'name'  => "inputNotes",
        'class' => "input-xxlarge",
        'value' => set_value('inputNotes')
      ); echo form_input($options); ?>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputAlternativeURL">Alternative URL</label>
    <div class="controls">
      <?php $options = array(
        'id'    => "inputAlternativeURL",
        'name'  => "inputAlternativeURL",
        'class' => "input-xlarge",
        'value' => set_value('inputAlternativeURL')
      ); echo form_input($options); ?>
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn btn-primary">Submit Report</button>
    </div>
  </div>
</form>
