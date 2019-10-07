<?php 
/**
 * Compliance Libraries Class
 *
 * This is a Compliance class for Compliance Opration 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__.'/../../helpers/custom_helper.php');
require_once( __DIR__.'/../LogClass.php');
require_once( __DIR__.'/../shared/SharedClass.php');  
require_once( __DIR__.'/../customer/CustomerClass.php');  

/**
 * Compliance Libraries Class
 *
 * This is a Compliance class for Compliance Opration  
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Compliance
 * @filesource          ComplianceClass.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 class ComplianceClass extends MY_Model
{
    /**
    * Log class 
    * 
    * @var class
    */
    private $LogClass;
    
    /**
    * Shared class 
    * 
    * @var class
    */
    private $sharedClass;
    
    
    /**
    * customer class 
    * 
    * @var class
    */
    private $customerClass;

    
    /**
    * Class constructor
    *
    * @return  void
    */
    function __construct()
    {
        parent::__construct();
        $this->LogClass= new LogClass('jobtracker', 'ComplianceClass');
        $this->sharedClass = new SharedClass();
        $this->customerClass = new CustomerClass();
    }
       
    
    /**
    * This function use for get Compliance Caption
    * 
    * @param integer $customerid - logged user contact id
    * @return array
    */
    public function getComplianceCaption($customerid) {
 
         $sql = "SELECT cgd.caption, sum(cd.has_startdate) as has_startdate, sum(cd.has_number) as has_number, sum(cd.track_expiry) as has_expiry, 1 AS has_doclink  "
                 . "FROM compliancegroupdetail cgd "
                 . " INNER JOIN compliancegroup cg ON cg.compliance_group_id=cgd.compliance_group_id "
                 . " INNER JOIN compliance_itemtype cit ON cgd.compliance_itemtype_id=cit.id"
                 . " INNER JOIN document d ON d.doctype=cgd.cdtype "
                 . " INNER JOIN contact c ON d.xrefid=c.contactid "
                 . " INNER JOIN customer cu ON c.customerid=cu.customerid"
                 . " INNER JOIN compliancedocs cd ON cd.cdtype=d.doctype"
                 . " INNER JOIN compliance_itemtype ci ON ci.id=cgd.compliance_itemtype_id "
                 . " INNER JOIN compliance_contact_customer ccc ON c.contactid=ccc.contactid AND ccc.customerid=cg.customerid "
				 . " INNER JOIN compliance_contact_status  ccs ON ccc.compconstatusid = ccs.id "	
                 
                 . " WHERE cg.customerid=$customerid AND cgd.showinclientportal=1 AND cit.name = 'document'"
            	 . " AND ccs.isactive=1 AND ccs.name = 'CURRENT' "
                 . " GROUP BY cgd.caption "
                 . " UNION  "
                 . " SELECT  cgd.caption, sum(lt.has_startdate) as has_startdate, sum(lt.has_number) as has_number, sum(lt.has_expiry) as has_expiry, sum(lt.has_doclink) as has_doclink "
                 . " FROM compliancegroupdetail cgd  "
                 . " INNER JOIN compliancegroup cg ON cg.compliance_group_id=cgd.compliance_group_id"
                 . " INNER JOIN compliance_itemtype cit ON cgd.compliance_itemtype_id=cit.id "
                 . " INNER JOIN contractor_licence cl ON cl.licencetypeid=cgd.cd_id "
                 . " INNER JOIN licencetype lt ON lt.id=cl.licencetypeid "
                 . " INNER JOIN contact c ON cl.contactid=c.contactid "
                 . " INNER JOIN customer cu ON c.customerid=cu.customerid "
                 . " LEFT JOIN document d ON cl.documentid=d.documentid "
                 . " INNER JOIN compliance_contact_customer ccc ON c.contactid=ccc.contactid AND ccc.customerid=cg.customerid "
				 . " INNER JOIN compliance_contact_status  ccs ON ccc.compconstatusid = ccs.id "	
                 . " WHERE cg.customerid=$customerid"
                 . " AND cgd.showinclientportal=1 "
                 . " AND cit.name = 'licence' "
                 . " AND ccs.isactive=1 AND ccs.name = 'CURRENT' "
                 . " GROUP BY cgd.caption"
                 . " ORDER BY caption ";
        $query = $this->db->query($sql);

        $data = $query->result_array();
        
        $this->LogClass->log('Get Compliance Caption Data Query : '. $this->db->last_query());

		//aklog($this->db->last_query());
        return $data; 
    }
    
                
    
    /**
    * This function use for get Compliance Data
    * 
    * @param integer $customerid - logged user contact id
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
    * @return type
    */
    public function getComplianceData($customerid, $size, $start, $field, $order, $filter, $params) {
 
        //$captionData = $this->getComplianceCaption($customerid);
//        $formatedCaptionData = array();
//        foreach ($captionData as $key => $value) {
//
//            $captionData[$key]['name'] = str_replace(' ', '_', strtolower($value['caption']));
//            $captionData[$key]['name'] = preg_replace('/[^A-Za-z0-9\-]/', '', $captionData[$key]['name']);
//            $formatedCaptionData[$captionData[$key]['name']] =  $captionData[$key];
//        }
        
        $extrawhere = '';
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $extrawhere .= " and (" . $fn ." in ('". implode("','", $fv) ."'))";
                }
            }
            else {
                if ($fv != '') {
                    $extrawhere .= " and (" . $fn ." ='".$fv ."')";
                }
            }
        }
                
        
        if ($filter != '') {
             $extrawhere .= " and (c.firstname LIKE '%".$this->db->escape_str($filter)."%' or cu.companyname LIKE '%".$this->db->escape_str($filter)."%' or c.suburb LIKE '%".$this->db->escape_str($filter)."%'  or c.state LIKE '%".$this->db->escape_str($filter)."%' or cu.industrysector LIKE '%".$this->db->escape_str($filter)."%' or cu.origin LIKE '%".$this->db->escape_str($filter)."%')";
        }
        
        $sql = "SELECT c.contactid, c.firstname, IF(cu.origin='internal','DCFM',cu.companyname) AS company, c.suburb, c.state, cu.industrysector AS trade, cu.origin AS type ";
        
//        foreach ($formatedCaptionData as $key => $value) {
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',d.documentid,null) as ". $key."_documentid";
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',d.docname,null) as ". $key."_docname";
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',d.docnote,null) as ". $key."_number";
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',d.dateadded,null) as ". $key."_sdate";
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',d.expirydate,null) as ". $key."_edate";
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',cd.has_startdate,null) as ". $key."_has_startdate";
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',cd.has_number,null) as ". $key."_has_number";
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',cd.track_expiry,null) as ". $key."_has_expiry";
//            $sql .= ", 1 as ". $key."_has_doclink"; 
//            
//        }

      
        $sql .= " FROM compliancegroupdetail cgd "
                . " INNER JOIN compliancegroup cg ON cg.compliance_group_id = cgd.compliance_group_id "
                . " INNER JOIN compliance_itemtype cit ON cgd.compliance_itemtype_id=cit.id "
                . " INNER JOIN document d ON d.doctype=cgd.cdtype "
                . " INNER JOIN contact c ON d.xrefid=c.contactid "
                . " INNER JOIN customer cu ON c.customerid=cu.customerid "
                . " INNER JOIN compliancedocs cd ON cd.cdtype=d.doctype "
                . " INNER JOIN compliance_itemtype ci ON ci.id=cgd.compliance_itemtype_id "
                . " INNER JOIN compliance_contact_customer ccc ON c.contactid=ccc.contactid AND ccc.customerid=cg.customerid "
				. " INNER JOIN compliance_contact_status  ccs ON ccc.compconstatusid = ccs.id "	
                . " WHERE cg.customerid=$customerid AND cgd.showinclientportal=1 AND cit.name = 'document' AND ccs.isactive=1 AND ccs.name = 'CURRENT' $extrawhere ";
       
         $sql .=" UNION "
                . " SELECT c.contactid, c.firstname, IF(cu.origin='internal','DCFM',cu.companyname) AS company, c.suburb, c.state, cu.industrysector AS trade, cu.origin AS type ";
//        foreach ($formatedCaptionData as $key => $value) {
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',d.documentid,null) as ". $key."_documentid";
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',d.docname,null) as ". $key."_docname";
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',cl.licencenumber,null) as ". $key."_number";
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',cl.startdate,null) as ". $key."_sdate";
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',cl.expirydate,null) as ". $key."_edate";
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',lt.has_startdate,null) as ". $key."_has_startdate";
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',lt.has_number,null) as ". $key."_has_number";
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',lt.has_expiry,null) as ". $key."_has_expiry";
//            $sql .= ", if(cgd.caption='". $value['caption'] ."',lt.has_doclink,null) as  ". $key."_has_doclink"; 
//            
//        }
        $sql .= " FROM compliancegroupdetail cgd "
                . " INNER JOIN compliancegroup cg ON cg.compliance_group_id=cgd.compliance_group_id "
                . " INNER JOIN compliance_itemtype cit ON cgd.compliance_itemtype_id=cit.id "
                . " INNER JOIN contractor_licence cl ON cl.licencetypeid=cgd.cd_id "
                . " INNER JOIN licencetype lt ON lt.id=cl.licencetypeid "
                . " INNER JOIN contact c ON cl.contactid=c.contactid "
                . " INNER JOIN customer cu ON c.customerid=cu.customerid "
                . " LEFT JOIN document d ON cl.documentid=d.documentid "
                . " WHERE cg.customerid=$customerid AND cgd.showinclientportal=1 AND cit.name = 'licence' $extrawhere ";
       
        $query1 = $this->db->query($sql);

        $trows = count($query1->result_array());
        //$sql .=" GROUP BY c.contactid, c.firstname, IF(cu.origin='internal','DCFM',cu.companyname), c.suburb, c.state, cu.industrysector, cu.origin ";
       
        $sql .="  GROUP BY c.contactid";
        if ($field != '') {
            $sql .=" ORDER BY $field $order ";
        }
        else{
            $sql .=" ORDER BY firstname";
        }
          
        if($size != NULL){
            $sql .=" LIMIT $start, $size";
        }
        
        $query = $this->db->query($sql);
        
        $data = array(
            'trows' => $trows, 
            'data' => $query->result_array()
        );

        $this->LogClass->log('Get Asset Data Query : '. $sql);

        return $data;
    }
    
    /**
    * This function use for get Compliance Caption Data
    * 
    * @param integer $customerid - logged user Customer id
    * @param integer $contacid - selected contactid
    * @param string $caption - caption 
    * @return type
    */
    public function getComplianceCaptionData($customerid, $contacid, $caption) {
   
        $sql = "SELECT d.documentid, d.docname, d.docnote AS number, d.startdate AS startdate, d.expirydate, cd.has_startdate, cd.has_number, cd.track_expiry AS has_expiry, 1 AS has_doclink"
                . " FROM compliancegroupdetail cgd "
                . " INNER JOIN compliancegroup cg ON cg.compliance_group_id = cgd.compliance_group_id "
                . " INNER JOIN compliance_itemtype cit ON cgd.compliance_itemtype_id=cit.id "
                . " INNER JOIN document d ON d.doctype=cgd.cdtype "
                . " INNER JOIN contact c ON d.xrefid=c.contactid "
                . " INNER JOIN customer cu ON c.customerid=cu.customerid "
                . " INNER JOIN compliancedocs cd ON cd.cdtype=d.doctype "
                . " INNER JOIN compliance_itemtype ci ON ci.id=cgd.compliance_itemtype_id "
                . " WHERE cg.customerid=$customerid AND c.contactid=$contacid AND cgd.caption='$caption' AND cgd.showinclientportal=1 AND cit.name = 'document' "
                . " UNION "
                . " SELECT d.documentid, d.docname, cl.licencenumber AS number, cl.startdate AS startdate, cl.expirydate, lt.has_startdate, lt.has_number, lt.has_expiry, lt.has_doclink"
                . " FROM compliancegroupdetail cgd "
                . " INNER JOIN compliancegroup cg ON cg.compliance_group_id=cgd.compliance_group_id "
                . " INNER JOIN compliance_itemtype cit ON cgd.compliance_itemtype_id=cit.id "
                . " INNER JOIN contractor_licence cl ON cl.licencetypeid=cgd.cd_id "
                . " INNER JOIN licencetype lt ON lt.id=cl.licencetypeid "
                . " INNER JOIN contact c ON cl.contactid=c.contactid "
                . " INNER JOIN customer cu ON c.customerid=cu.customerid "
                . " LEFT JOIN document d ON cl.documentid=d.documentid "
                . " WHERE cg.customerid=$customerid AND c.contactid=$contacid AND cgd.caption='$caption' AND cgd.showinclientportal=1 AND cit.name = 'licence' ";
        
        $query = $this->db->query($sql);
        
        $data =  $query->row_array();
         
        return $data;
    }
   
}


/* End of file ComplianceClass.php */