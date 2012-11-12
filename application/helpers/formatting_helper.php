<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Various helpers for assisting with text formatting
 *
 * @package Helpers
 * @category Formatting
 * @author Dafydd Vaughan (@dafyddbach)
 * @link http://www.dafyddvaughan.co.uk
 * @version 1.0
 */

// ------------------------------------------------------------------------

/**
 * Format providing tiers
 *
 * @access  public
 * @param   bool  $district is provided at district level?
 * @param   bool  $county   is provided at county level?
 * @param   bool  $unitary  is provided at unitary level?
 * @return  string  formatted output to display
 */
if ( ! function_exists('format_providing_tiers')) {
  function format_providing_tiers($district,$county,$unitary) {
    $provided_by = array();
    if($district) { array_push($provided_by,'district'); }
    if($county) { array_push($provided_by,'county'); }
    if($unitary) { array_push($provided_by,'unitary, london borough, metropolitan district'); }
    return implode(', ',$provided_by);
  }
}

function get_status_result($type,$status) {
  if($type==='http') {
    switch($status) {
      case 200 : return "success"; break;
      case 404 : return "important"; break;
      case 0 : return "default"; break;
      default: return "warning";
    }
  } else {
    switch($status) {
      case 200 : return "important"; break;
      case 404 : return "success"; break;
      case 0 : return "default"; break;
      default: return "warning";
    }
  }
}
function get_status_description($status) {
  switch($status) {
    case 0    : return "Unchecked"; break;
    case 200  : return "OK"; break;
    case 400  : return "Bad Request"; break;
    case 403  : return "Forbidden"; break;
    case 404  : return "Not Found"; break;
    case 410  : return "Gone"; break;
    case 500  : return "Error"; break;
    default:    return "Unknown";
  }
}

function will_url_be_used($authority_type,$provided_district,$provided_county,$provided_unitary) {

  if(is_service_valid(
    $authority_type,
    $provided_district,
    $provided_county,
    $provided_unitary
  )) {
    return 'active';
  } else {
    return 'archived';
  }
}
function is_service_valid($authority_type,$service_is_district,$service_is_county,$service_is_unitary) {

  $authority_is_district = FALSE;
  $authority_is_county = FALSE;
  $authority_is_unitary = FALSE;

  switch($authority_type) {
    case 'DIS': $authority_is_district = TRUE; break;
    case 'CTY': $authority_is_county = TRUE; break;
    case 'LBO': $authority_is_unitary = TRUE; break;
    case 'MTD': $authority_is_unitary = TRUE; break;
    case 'UTA': $authority_is_unitary = TRUE; break;
    default: $authority_is_county = TRUE;
  }

  if($authority_is_district && $service_is_district) {
    return TRUE;
  } elseif ($authority_is_county && $service_is_county) {
    return TRUE;
  } elseif ($authority_is_unitary && $service_is_unitary) {
    return TRUE;
  } else {
    return FALSE;
  }

}
/* End of file formatting_helper.php */
/* Location: ./application/helpers/formatting_helper.php */