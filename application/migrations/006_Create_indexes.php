<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_indexes extends CI_Migration {

  public function up()
  {
    $sql = "CREATE INDEX lgsl ON ".$this->db->dbprefix('service_urls')." (lgsl)";
    $this->db->query($sql);

    $sql = "CREATE INDEX snac ON ".$this->db->dbprefix('service_urls')." (snac)";
    $this->db->query($sql);

    $sql = "CREATE INDEX snac ON ".$this->db->dbprefix('local_authorities')." (snac)";
    $this->db->query($sql);

  }

  public function down()
  {
    $sql = "DROP INDEX lgsl ON ".$this->db->dbprefix('service_urls');
    $this->db->query($sql);

    $sql = "DROP INDEX snac ON ".$this->db->dbprefix('service_urls');
    $this->db->query($sql);

    $sql = "DROP INDEX snac ON ".$this->db->dbprefix('local_authorities');
    $this->db->query($sql);
  }

}