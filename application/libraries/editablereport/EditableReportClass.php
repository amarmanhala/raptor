<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once __DIR__.'/../../helpers/custom_helper.php';
require_once __DIR__.'/../LogClass.php';
require_once __DIR__.'/../shared/SharedClass.php';
/**
 * EditableReport Libraries Class
 *
 * This is a Quote class for Quote Opration 
 *
 * @category   EditableReport
 * @package    Raptor
 * @subpackage Libraries
 * @filesource EditableReportClass.php
 * @author     Alison Morris <itglobal3@dcfm.com.au>
 * 
 */
class EditableReportClass extends MY_Model 
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
    
    public function __construct()
    {
        parent::__construct();
        $this->LogClass= new LogClass('jobtracker', 'EditableReportClass');
        $this->sharedClass = new SharedClass();
    }

    public function add_photo(&$data)
    {
        $document_record = $this->getRecord(
            array(
                'areatype_guid'), 
            array(
                'report_id' => $data['report_id'], 
                'guid' => $data['area_value_guid']), 
            'rep_area_value');

        $data = array_merge($data, $document_record);

        $sortorder = $this->getMax(array(
            'field' => 'sortorder', 
            'condition_fields_values' => array(
                'scope_guid' => $data['areatype_guid']), 
            'table' => 'document'));
        ++$sortorder;

        // create document table entry
        $data['documentid'] = $this->insertRecord(array(
            'table' => 'document',
            'fields' => array(
                'xrefid' => $data['job_id'], 
                'scope_guid' => $data['areatype_guid'], 
                'docformat' => $data['extension'], 
                'sortorder' => $sortorder,
                'xreftable' => 'jobs', 
                'doctype' => 'pre-work'
                ) 
            ));

        return $data['documentid'];
    }

    public function get_report_area(&$data)
    {
        $this->db
            ->select('a.ins_sitereport_id, 
                    b.id, 
                    b.areatype_id, 
                    b.guid, 
                    b.area_description')
            ->from('ins_sitereport a')
            ->join('rep_area_value b', 'a.ins_sitereport_id=b.report_id', 'left')
            ->where('a.ins_sitereport_id', $data['report_id']);

        // if 'all' Areas hasn't been selected
        if ($data['areavalue_guid'] != 'all') {
            $this->db->where('b.guid', $data['areavalue_guid']);
        }

        $query = $this->db->get();
        
        if($data['job_id'] == '' || $data['job_id'] == 0) {
            $data['rows'] = array();
        } else {
            $data['rows'] = $query->result_array();
        }
        
        $this->LogClass->log("GET REPORT AREA");
        $this->LogClass->log($this->db->last_query());
        $this->LogClass->log($data['rows']);
    }

    public function get_report_area_topic(&$data)
    {
        foreach ($data['rows'] as $index => $row) {
            //$this->LogClass->log($index);
            $data['rows'][$index]['rows'] = $this->get_report_area_topic_value($data['rows'][$index]['guid']);
            //$this->LogClass->log($data['rows']);
            $data['rows'][$index]['photos'] = $this->get_report_area_topic_photo($data['rows'][$index]['guid'], $data['job_id']);
            //$this->LogClass->log($data['rows']);
            $data['rows'][$index]['assets'] = $this->get_report_area_asset($data['rows'][$index]['guid']);
            //$this->LogClass->log($data['rows']);
        }
    }

    public function get_report_area_asset($areavalue_guid)
    {
        $this->db
            ->select('
                a.id, 
                b.category_id, 
                c.name as category_name, 
                b.description, 
                b.description, 
                b.category_id, 
                b.asset_criticality, 
                b.desired_condition, 
                b.actual_condition, 
                b.remaining_life_expectancy')
            ->from('rep_area_value_asset a')
            ->join('rep_asset b', 'a.asset_id=b.id', 'left')
            ->join('rep_asset_category c', 'b.category_id=c.id', 'left')
            ->where('a.areavalue_guid', $areavalue_guid);

        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_report_area_topic_photo($areavalue_guid, $job_id)
    {
        $this->db
            ->select("a.documentid as id, 
                0 as grouptype, 
                a.scope_guid, 
                a.scope_guid as groupid, 
                a.documentdesc, 
                a.doctype, 
                a.docformat, 
                a.docnote, 
                CONCAT('img', a.documentid) as target,    
                CONCAT('/InfomaniacDocs/jobdocs/', a.documentid, '.', a.docformat) as url", false)
            ->from('document a')
            ->join('rep_area_value b', 'a.scope_guid=b.areatype_guid')
            ->where('a.xreftable', 'jobs')
            ->group_start()
                ->like('a.doctype', 'pre-work', 'both')
                ->or_like('a.doctype', 'post-work', 'both')
            ->group_end()
            ->where('b.guid', $areavalue_guid)
            ->where('a.xrefid', $job_id)
            ->where('a.isdeleted !=', 1)
            ->order_by('a.sortorder, a.documentid');

        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_report_area_topic_value($areavalue_guid)
    {
        $this->db
            ->select('a.id, a.area_type_id, b.name as areatopic_name, a.area_topic_id, a.dirty, a.dirty_rating, f.minrating, f.maxrating, a.damaged, a.damaged_rating, a.description as topic_description, a.remedial_action, a.ynna')
            ->from('rep_report_areatypetopic_value a')
            ->join('rep_area_topic b', 'b.id=a.area_topic_id', 'left')
            ->join('ins_sitereport e', 'e.ins_sitereport_id=a.report_id', 'left')
            ->join('rep_areatype_areatopic f', 'f.areatype_id = a.area_type_id AND f.areatopic_id = b.id AND f.reporttype_id = e.report_type_id', 'left')
            ->where('a.isdeleted <', 1)
            ->where('a.areavalue_guid', $areavalue_guid);

        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_jobid_list()
    {
        $this->db
            ->select('a.jobid as id, jobid as name')
            ->distinct()
            ->from('ins_sitereport a')
            ->join('rep_reporttype b', 'a.report_type_id=b.id', 'left')
            ->where('b.has_topicvalue', 1);

        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_report_list($job_id)
    {
        $this->db
            ->select('a.ins_sitereport_id as id, b.name as report_type')
            ->from('ins_sitereport a')
            ->join('rep_reporttype b', 'a.report_type_id=b.id', 'left')
            ->where('a.jobid', $job_id);

        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_area_list($report_id)
    {
        $this->db
            ->select('b.areatype_id, b.guid, b.area_description as name')
            ->from('ins_sitereport a')
            ->join('rep_area_value b', 'a.ins_sitereport_id=b.report_id', 'left')
            ->where('a.ins_sitereport_id', $report_id);

        $query = $this->db->get();

        $aAreas = array(array('guid' => 'all', 'name' => 'All Areas'));
        $aAreas = array_merge($aAreas, $query->result_array());

        return $aAreas;
    }

    public function get_report_photo(&$data)
    {
        $sql = "SELECT a.documentid as id, a.documentdesc, a.doctype,  
                    a.docnote as caption, 
                    CONCAT(a.documentid, '.', a.docformat) as image, 
                    CONCAT('/InfomaniacDocs/jobdocs/', a.documentid, '.', a.docformat) as url    
                    FROM document a 
                    JOIN rep_area_value b ON (a.scope_guid=b.areatype_guid)
                    JOIN rep_area_type c ON (c.id=b.areatype_id)
                    WHERE a.xreftable = 'jobs' 
                    AND  (a.doctype LIKE '%pre-work%' OR a.doctype LIKE '%post-work%')  
                    AND a.xrefid = ?
                    AND a.isdeleted <> 1
                    ORDER BY a.sortorder, a.documentid;";

        $query = $this->db->query($sql, array($data['job_id']));

        $data['photos'] = $query->result_array();
    }

    public function get_areatypetopicvalue_list($query_type, &$data,  $limit = null, $start = null)
    {
        // SELECT Clause
            switch ($query_type) {
                case 0:
                    $sql 
                        = 'SELECT 
                            b.id, 
                            b.report_id, 
                            e.report_type_id, 
                            f.minrating, 
                            f.maxrating, 
                            b.area_type_id, 
                            c.id as areatype_id, 
                            c.name as areatype_name, 
                            b.area_topic_id, 
                            d.name as areatopic_name, 
                            b.dirty, 
                            b.dirty_rating, 
                            b.damaged, 
                            b.damaged_rating, 
                            b.description, 
                            b.remedial_action, 
                            b.ynna, 
                            b.dirty, 
                            b.damaged, 
                            b.dirty_rating ';
                    break;
                case 1:
                    // record count
                    $sql 
                        = 'SELECT 
                            COUNT(a.id) as record_count ';
                    break;
                case 2:
                    //export rows
                    $sql 
                        = 'SELECT 
                            a.id, 
                            a.name, 
                            a.reportFolder, 
                            a.filespec, 
                            a.yn_caption, 
                            a.sortorder ';
                    break;
            }

        $sql .= ' FROM rep_report_areatypetopic_value b
                     LEFT JOIN rep_area_type c ON (c.id=b.area_type_id)
                     LEFT JOIN rep_area_topic d ON (d.id=b.area_topic_id) 
                     LEFT JOIN ins_sitereport e ON (e.ins_sitereport_id=b.report_id)
                     LEFT JOIN rep_areatype_areatopic f ON (f.areatype_id = b.area_type_id AND f.areatopic_id = b.area_topic_id AND f.reporttype_id = e.report_type_id) ';

        $sql .= ' WHERE b.report_id='.$data['report_id'];

        // ORDER Clause
        switch ($query_type) {
            case 0:
            case 2:
                // data & export rows
                //$sql .= " ORDER BY a.start_date ";
                break;
        }

        // LIMIT Clause
        switch ($query_type) {
            case 0:
                // data rows
            //$sql .= " LIMIT $start,$limit";
                break;
        }

        $query = $this->db->query($sql);

        // Return
        switch ($query_type) {
            case 0:
                // data rows
                $data['rows'] = $query->result_array();
                //echo "<P>$sql<P>";
                break;
            case 1:
                // record count
                $row = $query->row_array();
                $data['filter']['totalitems'] = $row['record_count'];

                return $row['record_count'];
                break;
            case 2:
                //export rows
            $result = $query->result_array();
            // prepend column names to exported data
            $fields = array(array_keys($result[0]));
            $result = array_merge($fields, $result);

            return $result;
                break;
        }
    }

    public function get_where_clause_reporttype($data)
    {
        $sql = ' WHERE true';

        if ($data['inputName']['value'] != '') {
            $sql .= " AND a.name LIKE '%".$data['inputName']['value']."%'";
        }

        return $sql;
    }

    public function writeXEditableValues(&$data)
    {
        $showbuttons = 'bottom';
        $display = null;
        $ajaxurl = null;

        foreach ($data['rows'] as $index => $row) {

            $tablenum = 660;
            $data['rows'][$index]['x_editable']['area_description'] 
                = $this->getXEditableValue(0, $row['id'], $row['area_description'], $row['area_description'], $tablenum, 'text', null, $ajaxurl, 'editable_target', 'editable_medium', '', $showbuttons);

            $tablenum = 650;
            foreach ($row['rows'] as $index2 => $row2) {
                $data['rows'][$index]['rows'][$index2]['x_editable']['dirty'] 
                    = $this->getXEditableValue(0, $row2['id'], $row2['dirty'], $display, $tablenum, 'select', 'cleandirty', $ajaxurl, 'editable_target', 'editable_xnarrow', '', $showbuttons);
                $data['rows'][$index]['rows'][$index2]['x_editable']['dirty_rating'] 
                    = $this->getXEditableValue(1, $row2['id'], $row2['dirty_rating'], $display, $tablenum, 'select', 'intselect/'.$row2['minrating'].'/'.$row2['maxrating'], $ajaxurl, 'editable_target', 'editable_xnarrow', '', $showbuttons);
                $data['rows'][$index]['rows'][$index2]['x_editable']['damaged'] 
                    = $this->getXEditableValue(2, $row2['id'], $row2['damaged'], $display, $tablenum, 'select', 'undamageddamaged', $ajaxurl, 'editable_target', 'editable_mnarrow', '', $showbuttons);
                $data['rows'][$index]['rows'][$index2]['x_editable']['topic_description'] 
                    = $this->getXEditableValue(4, $row2['id'], $row2['topic_description'], $row2['topic_description'], $tablenum, 'textarea', null, $ajaxurl, 'editable_target spellcheck', 'editable_medium', '', $showbuttons);
                $data['rows'][$index]['rows'][$index2]['x_editable']['remedial_action'] 
                    = $this->getXEditableValue(5, $row2['id'], $row2['remedial_action'], $row2['remedial_action'], $tablenum, 'textarea', null, $ajaxurl, 'editable_target', 'editable_medium', '', $showbuttons);
                $data['rows'][$index]['rows'][$index2]['x_editable']['ynna'] 
                    = $this->getXEditableValue(6, $row2['id'], $row2['ynna'], $display, $tablenum, 'select', 'yesno', $ajaxurl, 'editable_target', 'editable_xxnarrow', '', $showbuttons);
            }

            $tablenum = 500;
            foreach ($row['photos'] as $index2 => $photo) {
                $data['rows'][$index]['photos'][$index2]['x_editable']['documentdesc'] 
                    = $this->getXEditableValue(0, $photo['id'], $photo['documentdesc'], $photo['documentdesc'], $tablenum, 'textarea', null, $ajaxurl, 'editable_target', 'editable_medium', '', $showbuttons);
                $data['rows'][$index]['photos'][$index2]['x_editable']['docnote'] 
                    = $this->getXEditableValue(1, $photo['id'], $photo['docnote'], $photo['docnote'], $tablenum, 'textarea', null, $ajaxurl, 'editable_target', 'editable_medium', '', $showbuttons);
            }

            $tablenum = 670;
            foreach ($row['assets'] as $index2 => $asset) {
                $data['rows'][$index]['assets'][$index2]['x_editable']['description'] 
                    = $this->getXEditableValue(
                        1, 
                        $asset['id'], 
                        $asset['description'], 
                        $asset['description'], 
                        $tablenum, 'textarea', 
                        null, 
                        $ajaxurl, 
                        'editable_target', 
                        'editable_medium', 
                        '', 
                        $showbuttons);
                $data['rows'][$index]['assets'][$index2]['x_editable']['asset_criticality'] 
                    = $this->getXEditableValue(2, $asset['id'], $asset['asset_criticality'], $asset['asset_criticality'], $tablenum, 'textarea', null, $ajaxurl, 'editable_target', 'editable_medium', '', $showbuttons);
                $data['rows'][$index]['assets'][$index2]['x_editable']['desired_condition'] 
                    = $this->getXEditableValue(3, $asset['id'], $asset['desired_condition'], $asset['desired_condition'], $tablenum, 'textarea', null, $ajaxurl, 'editable_target', 'editable_medium', '', $showbuttons);
                $data['rows'][$index]['assets'][$index2]['x_editable']['actual_condition'] 
                    = $this->getXEditableValue(4, $asset['id'], $asset['actual_condition'], $asset['actual_condition'], $tablenum, 'textarea', null, $ajaxurl, 'editable_target', 'editable_medium', '', $showbuttons);
                $data['rows'][$index]['assets'][$index2]['x_editable']['remaining_life_expectancy'] 
                    = $this->getXEditableValue(5, $asset['id'], $asset['remaining_life_expectancy'], $asset['remaining_life_expectancy'], $tablenum, 'text', null, $ajaxurl, 'editable_target', 'editable_medium', '', $showbuttons);
            }
        }
    }

    public function getXEditableValue($fieldnum, $id, $value, $display, $tablenum, $type, $source, $ajaxurl, $class, $inputclass, $xetitle, $showbuttons)
    {
        $data['id'] = $id;
        $data['fieldnum'] = $fieldnum;
        $data['value'] = $value;
        $data['display'] = $display;
        $data['tablenum'] = $tablenum;
        $data['type'] = $type;
        $data['source'] = $source;
        $data['ajaxurl'] = $ajaxurl;
        $data['class'] = $class;
        $data['xetitle'] = $xetitle;
        $data['inputclass'] = $inputclass;
        $data['showbuttons'] = $showbuttons;
        
        return $data;
    }

    public function writeXEditableValue($fieldname, $fieldnum, $id, $value, $display, $tablenum, $type, $source, $ajaxurl, $class, $inputclass, $xetitle, $index, $showbuttons, &$data, $xrule='', $xrulemessage='', $xplaceholder='')
    {
        $data['rows'][$index]['x_editable'][$fieldname]['id'] = $id;
        $data['rows'][$index]['x_editable'][$fieldname]['fieldnum'] = $fieldnum;
        $data['rows'][$index]['x_editable'][$fieldname]['value'] = $value;
        $data['rows'][$index]['x_editable'][$fieldname]['display'] = $display;
        $data['rows'][$index]['x_editable'][$fieldname]['tablenum'] = $tablenum;
        $data['rows'][$index]['x_editable'][$fieldname]['type'] = $type;
        $data['rows'][$index]['x_editable'][$fieldname]['source'] = $source;
        $data['rows'][$index]['x_editable'][$fieldname]['ajaxurl'] = $ajaxurl;
        $data['rows'][$index]['x_editable'][$fieldname]['class'] = $class;
        $data['rows'][$index]['x_editable'][$fieldname]['xetitle'] = $xetitle;
        $data['rows'][$index]['x_editable'][$fieldname]['inputclass'] = $inputclass;
        $data['rows'][$index]['x_editable'][$fieldname]['showbuttons'] = $showbuttons;
        $data['rows'][$index]['x_editable'][$fieldname]['xrule'] = $xrule; 
        $data['rows'][$index]['x_editable'][$fieldname]['xrulemessage'] = $xrulemessage;
        $data['rows'][$index]['x_editable'][$fieldname]['xplaceholder'] = $xplaceholder;            
    }

    public function add($name)
    {
        $data = array('table' => 'rep_reporttype', 'fields' => array('name' => $name));
        $id = $this->insertRecord($data);
    }

    public function propertyreport_actionimage($id, $groupid, $actiontype){   
        
        switch ($actiontype) {
            // Send Top
            case 0:
                //echo "Send Top $id<br>";
                $sortorder = $this->getMin(array('field'=>'sortorder', 'condition_fields_values' => array('scope_guid' => $groupid), 'table'=>'document'));
                $sortorder--;
                $this->updateRecords(array('documentid' => $id), array('sortorder' => $sortorder), 'document');
                break;
            // Send Bottom
            case 1:
                //echo "Send Bottom $id";
                $sortorder = $this->getMax(array('field'=>'sortorder', 'condition_fields_values' => array('scope_guid' => $groupid), 'table'=>'document'));
                $sortorder++;
                $this->updateRecords(array('documentid' => $id), array('sortorder' => $sortorder), 'document');
                break;
            // Up One
            case 2:
                //echo "Up One documentid=$id<P>";
                $current_record = $this->getRecord(array('sortorder'), array('documentid' => $id), 'document');
                //echo "current_record:sortorder=" .   $current_record['sortorder'] . "<P>";
                
                // get all the records above
                $sql = "SELECT a.documentid, a.sortorder
                FROM document a
                WHERE a.scope_guid= ?
                AND ( a.sortorder < ? OR (a.sortorder = ? AND a.documentid < ? ))
                ORDER BY a.sortorder DESC, a.documentid DESC;";

                $query = $this->db->query($sql, array($groupid, $current_record['sortorder'], $current_record['sortorder'], $id));
                
                $sortorder_diff = 0;
                foreach ($query->result_array() as $key=>$value){
                    
                    if($key==0){
                        //echo "record above $key<br>";
                        //echo $value['documentid'] . "  " . $value['sortorder'] . "<br>";
                        $sortorder_diff = $value['sortorder'] - $current_record['sortorder'];
                        $sortorder_diff--;
                        //echo "sortorder_diff=$sortorder_diff<br>";
                        
                        // update moving record
                        $newsort_order = $value['sortorder'] + $sortorder_diff;
                        //echo "$newsort_order<br>";
                        $this->updateRecords(array('documentid' => $id), array('sortorder' => $newsort_order), 'document');
                        $sortorder_diff--;
                    } else {
                        //echo "records higher $key<br>";
                        //echo $value['documentid'] . "  " . $value['sortorder'] . "<br>";
                        // update moving record
                        $newsort_order = $value['sortorder'] + $sortorder_diff;
                        //echo "$newsort_order<br>";
                        $this->updateRecords(array('documentid' => $value['documentid']), array('sortorder' => $newsort_order), 'document');
                    }
                }
                
                
                break;
            // Down One
            case 3:
                //echo "Down One documentid=$id<P>";
                
                $current_record = $this->getRecord(array('sortorder'), array('documentid' => $id), 'document');
                //echo "current_record:sortorder=" .   $current_record['sortorder'] . "<P>";
                
                 // get all the records above
                $sql = "SELECT a.documentid, a.sortorder
                FROM document a
                WHERE a.scope_guid= ?
                AND ( a.sortorder > ? OR (a.sortorder = ? AND a.documentid > ? ))
                ORDER BY a.sortorder ASC, a.documentid ASC;";

                $query = $this->db->query($sql, array($groupid, $current_record['sortorder'], $current_record['sortorder'], $id));
                $sortorder_diff = 0;
                foreach ($query->result_array() as $key=>$value){
                    
                    if ($key==0){
                        //echo "record below $key<br>";
                        //echo $value['documentid'] . "  " . $value['sortorder'] . "<br>";
                        $sortorder_diff = $value['sortorder'] - $current_record['sortorder'];
                        $sortorder_diff++;
                        //echo "sortorder_diff=$sortorder_diff<br>";
                        
                        // update moving record
                        $newsort_order = $value['sortorder'] + $sortorder_diff;
                        //echo "$newsort_order<br>";
                        $this->updateRecords(array('documentid' => $id), array('sortorder' => $newsort_order), 'document');
                        $sortorder_diff++;
                        //echo  "<P>";
                    } else {
                        // update moving record
                        $newsort_order = $value['sortorder'] + $sortorder_diff;
                        $this->updateRecords(array('documentid' => $value['documentid']), array('sortorder' => $newsort_order), 'document');
                        //echo  "<P>";
                    }
                }                
                break;
        }
        
    }

    public function get_int_select($min = 0, $max = 10)  
    {        
        for ($x = $min; $x <= $max; $x++) {
            $data["$x"] = "$x";
        }
        
        return $data;
    }

    /*** Generic CRUD functions ***/
    public function getMax($data) {
        $this->db->select_max($data['field']);
        $this->db->where($data['condition_fields_values']);
        $query = $this->db->get($data['table']);
        $max_record = $query->row_array();
        return $max_record[$data['field']];
    }

    public function getMin($data) {
        $this->db->select_min($data['field']);
        $this->db->where($data['condition_fields_values']);
        $query = $this->db->get($data['table']);
        $min_record = $query->row_array();

        return $min_record[$data['field']];
    }

    public function countRecords($data) {
        $query = $this->db->get_where($data['table'], $data['conditions']);
        return $query->num_rows();
    }    

    public function insertRecord($data) {
        //var_dump($data);
        $this->db->insert($data['table'], $data['fields']);
        return $this->db->insert_id();
    }
    
    public function insertBatchRecords($table, $data) {
     
        return $this->db->insert_batch($table, $data); 
    }

    
    public function getRecord($select_fields, $condition_fields_values, $table) {
        $this->db->select($select_fields);
        $this->db->where($condition_fields_values);
        $this->db->from($table);
        $query = $this->db->get();

        return $query->row_array();
    }

    public function getRecords($select_fields, $condition_fields_values, $table) {
        $this->db->select($select_fields);
        $this->db->where($condition_fields_values);
        $this->db->from($table);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function deleteRecords($condition_fields_values, $table) {
        $this->db->where($condition_fields_values);
        $this->db->delete($table);
    }

    public function updateRecords($condition_fields_values, $update_fields_values, $table) {
        $this->db->where($condition_fields_values);
        $this->db->update($table, $update_fields_values);
    }
    
    public function get_data_from_sqlQuery($sql) {
     
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function delete_data_from_sqlQuery($sql) {

        $query = $this->db->query($sql);

    }

    /*** END Generic CRUD functions ***/
    
    /**
    * This function use for Job Approval
    * @param array $params - the params is array of Job detail and contactid(LoggedUser)
    * @return array
    */
    public function shareReport($params)
    {
        //1 - load multiple models
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php'); 
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        
         //2 - initialize instances
        $JobNoteChain = new JobNoteChain();
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected
        $JobNoteChain->setSuccessor($EmailChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $jobData = $params['jobdata'];     
        $report = $this->get_report($params['jobid'], $params['reportid']);
        
        //Create job notes data array
        $jobNoteData = array( 
            'jobid'      => $params["jobid"],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => 'Report '.$params["jobid"].'_'.$params["reportid"].'.pdf '.$report['report_type'].' emailed to '.$params["email"],
            'ntype'      => 'client',
            'notetype'   => 'client',
            'userid'     => $loggedUserData['email'] 
        );
        
        $emailData = array();
        $emailData[]  = array(
            'recipient'   => $params["email"], 
            'cc'          => "dcfm@dcfm.com.au", 
            'customerid'  => $jobData['customerid'], 
            'subject'     => 'subject',
            'message'     => $params["notes"]

        );        
                
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData,
            'jobData'           => $jobData,
            'jobNoteData'       => $jobNoteData,
            'emailData'         => $emailData
        );
 
        $JobNoteChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    public function get_report($job_id, $report_id)
    {
        $this->db
            ->select('a.ins_sitereport_id as id, b.name as report_type, a.documentid')
            ->from('ins_sitereport a')
            ->join('rep_reporttype b', 'a.report_type_id=b.id', 'left')
            ->where('a.jobid', $job_id)
            ->where('a.ins_sitereport_id', $report_id);

        $query = $this->db->get();

        return $query->row_array();
    }
   
}

/* End of file EditableReportClass.php */
/* Location: ./application/models/EditableReportClass.php */