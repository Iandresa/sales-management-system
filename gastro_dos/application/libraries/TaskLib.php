<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TaskLib
 *
 * @author Ariel F. Cabañas
 * 
 */
class TaskLib 
{
   
    function DailyTask_Checker()
    {
        $CI =& get_instance();
        $conf = $CI->db->select("DATE_FORMAT(daily_cheked, '%d %m %Y') as daily_cheked", false);
        $conf = $CI->db->get("phppos_general_config");
        $conf = $conf->row();
        $today = date('d m Y');

        if( $conf->daily_cheked != $today)
        {
            //Llamadas a los metodes que chequean cosas diarias:
            $this->_Check_Pedidos_Entrega_Reportes_Gratis();
            $this->_Check_Pedidos_Entrega_Reportes_ExpireDate();
            $this->_Check_Inactivity();
            $this->_Reset_Campaigs_Daily_Count();
            $this->_check_item_stock();//HL 2013-04-22
            $this->_check_subsidary_cycle();//HL 2013-07-25
            //==================================================

            //Marcar que ya se reviso hoy;
            $conf = $CI->db->query("update phppos_general_config set daily_cheked = NOW()");
        }

        $this->_check_daily_cash();//HL 2013-08-29
    }
    
    function _Reset_Campaigs_Daily_Count() 
    {
        $CI =& get_instance();
        $CI->db->update('phppos_campaigns', array('daily_count'=> 0));
    }
    
    function _check_item_stock() //HL 2013-04-22 
    {
        $CI =& get_instance();
        $CI->load->model('Item');
        $CI->load->library('email');
        
        $CI->db->where("quantity < quantity_to_use OR quantity = 0", null, false);
       
        $items = $CI->db->get('phppos_items');
       
        foreach($items->result() as $i)
        {
            $sub = $CI->Subsidary->get_info($i->subsidary_id);
            $managers = $CI->Enterprise->get_enterprise_managers($sub->enterprise_id);
          
            foreach($managers->result() as $m)
            {
                $l = "english";
                if($sub)
                    $l = $sub->language;
                $this->Sent_Letter_To_Person('item_low_stock', $m->email, $l,
                     $m->first_name.' '.$m->last_name,NULL,NULL,NULL,NULL,NULL,$i->name,$sub->company);
            }
        } 
    }
    
    function _check_subsidary_cycle() //HL 2013-07-25 
    {
        $CI =& get_instance();
        $CI->load->model('Subsidary');
        
        $expire_cycle = 0;
       
        $subs = $CI->db->get('phppos_subsidaries_cycles');
       
        foreach($subs->result() as $s)
        {
            $sub = $CI->Subsidary->get_info($s->subsidary_id);
            
            $expire_cycle = $s->daily_count + 1;
            $CI->db->update('phppos_subsidaries_cycles', array('daily_count'=> $expire_cycle));
            
            if($expire_cycle == ($sub->closing_cycle*30))
                $CI->db->update('phppos_subsidaries_cycles', array('is_completed'=> true));
        } 
    }

    function _check_daily_cash() //HL 2013-08-29
    {
        $CI =& get_instance();
        $CI->load->model('Subsidary');

        $sec_in_a_day = 86400; // 24*60*60
        $day = date('d-m-Y H:i:s', date('U') - $sec_in_a_day);
        //echo $day;
        $daily_cash = $CI->db->get('phppos_daily_cash');

        //$yesterday= mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
        //echo $yesterday;

        //$startTime = mktime() - 24*3600;
        //$endTime = mktime();

        foreach($daily_cash->result() as $dc)
        {
            //$d = date('d-m-Y H:i:s',strtotime($dc->date_time));
            //echo mktime(0,0,0,$d['m'],$d['d'],$d['Y']);
            //echo $d;

            $CI->db->where("DATE_FORMAT(date_time,'%d-%m-%Y %H:%i:%s') <= '$day' and is_last = 1", null, false);
            $CI->db->update('phppos_daily_cash', array('is_completed'=> true));
        }
    }
    
    //Acere tu sabes algo de como usar una consola php en donde se puedan tirar comandos?
    
    function _Check_Pedidos_Entrega_Reportes_Gratis(){
        
        $expire_days = 30;
        $first_warning = 20;
        $second_warning = 25;
        $sec_in_a_day = 86400; // 24*60*60
        
        $CI =& get_instance();
        $CI->load->model('Enterprise'); 
        $CI->load->library('email'); 
        
        //====== Verificar 10 dias antes de vencer =======================
        $day = date('d m Y', date('U') - $first_warning * $sec_in_a_day);
 
        $CI->db->where("DATE_FORMAT(creation_time,'%d %m %Y')='$day' AND
            (permi_gr_reports = '2' OR permi_uncomplete_sale = '2' OR permi_delivery = '2')", null, false);
        $enterprises = $CI->db->get('phppos_enterprises');
   
        foreach($enterprises->result() as $e)
        {
             $managers = $CI->Enterprise->get_enterprise_managers($e->enterprise_id);
             foreach($managers->result() as $m)
             {
                 $sub = $CI->Subsidary->get_info($m->subsidary_id);
                 $l = "english";
                 if($sub)
                    $l = $sub->language;
                 $this->Sent_Letter_To_Person('warning_module_trial_expire', $m->email, $l,
                         $m->first_name.' '.$m->last_name, NULL,NULL, 10,NULL,NULL,NULL,NULL);
               
             }
        }
        //===============================================================

        //====== Verificar 5 dias antes de vencer =======================
        $day = date('d m Y', date('U') - $second_warning * $sec_in_a_day);
        $CI->db->where("DATE_FORMAT(creation_time,'%d %m %Y')='$day' AND
            (permi_gr_reports = '2' OR permi_uncomplete_sale = '2' OR permi_delivery = '2')", null, false);
        $enterprises = $CI->db->get('phppos_enterprises');
        
        foreach($enterprises->result() as $e)
        {
             $managers = $CI->Enterprise->get_enterprise_managers($e->enterprise_id);
             foreach($managers->result() as $m)
             {
                 $sub = $CI->Subsidary->get_info($m->subsidary_id);
                 $l = "english";
                 if($sub)
                    $l = $sub->language;
                 $this->Sent_Letter_To_Person('warning_module_trial_expire', $m->email, $l,
                         $m->first_name.' '.$m->last_name,NULL,NULL,5,NULL,NULL,NULL,NULL);
                 
             }
        }
        //===============================================================
  
        //====== Desactivar modulos que estan en trial =======================
        //$day = date('d m Y', date('U') - $expire_days * $sec_in_a_day);
        $day = date('U') - $expire_days * $sec_in_a_day;
        $CI->db->where("UNIX_TIMESTAMP(creation_time)<='$day' AND
            (permi_gr_reports = '2' OR permi_uncomplete_sale = '2' OR permi_delivery = '2')", null, false);
        $enterprises = $CI->db->get('phppos_enterprises');
        
        foreach($enterprises->result() as $e)
        {
             $CI->Enterprise->set_permit_trial_off($e->enterprise_id);
            
             $managers = $CI->Enterprise->get_enterprise_managers($e->enterprise_id);
             foreach($managers->result() as $m)
             {
                 $sub = $CI->Subsidary->get_info($m->subsidary_id);
                 $l = "english";
                 if($sub)
                    $l = $sub->language;
                 $this->Sent_Letter_To_Person('module_trial_expired', $m->email, $l,
                         $m->first_name.' '.$m->last_name,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
             }
        }
        //===============================================================
    }
    
    function _Check_Pedidos_Entrega_Reportes_ExpireDate(){
        
        $CI =& get_instance();
        $CI->load->model('Enterprise'); 
        $CI->load->library('email'); 
        
        $date = getdate();
        $today = mktime(23, 59, 59, $date['mon'], $date['mday'], $date['year']);

        $CI->db->where("UNIX_TIMESTAMP(permi_uncomplete_sale_expiredate) <= $today && permi_uncomplete_sale = '1'", null, false);
        $enterprises = $CI->db->update('phppos_enterprises', array('permi_uncomplete_sale' => '0'));
        
        $CI->db->where("UNIX_TIMESTAMP(permi_gr_reports_expiredate) <= $today && permi_gr_reports = '1'", null, false);
        $enterprises = $CI->db->update('phppos_enterprises', array('permi_gr_reports' => '0'));
                
        $CI->db->where("UNIX_TIMESTAMP(permi_delivery_expiredate) <= $today && permi_delivery = '1'", null, false);
        $enterprises = $CI->db->update('phppos_enterprises', array('permi_delivery' => '0'));
    }
    
    function _Check_Inactivity()
    {
        $inactivity_days = 30;
        $sec_in_a_day = 86400; // 24*60*60
        
        $CI =& get_instance();
        $CI->load->model('Enterprise');
        $CI->load->library('email');
        
        
        $CI->db->where("mod(DATEDIFF(NOW(), last_activity_time), 30) = 0 AND DATEDIFF(NOW(), last_activity_time)/30 < 4 AND DATEDIFF(NOW(), last_activity_time)/30 <> 0", null, false);
       
        $enterprises = $CI->db->get('phppos_enterprises');
        //echo $CI->db->last_query();
        
        foreach($enterprises->result() as $e)
        {
             $managers = $CI->Enterprise->get_enterprise_managers($e->enterprise_id);
             
             foreach($managers->result() as $m)
             {
                 $sub = $CI->Subsidary->get_info($m->subsidary_id);
                 $l = "english";
                 if($sub)
                    $l = $sub->language;
                 $this->Sent_Letter_To_Person('inactivity_account', $m->email, $l,
                         $m->first_name.' '.$m->last_name,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
             }
        }
    }
    
    function Sent_Letter_To_Person($stdletter_id_name, $email, $lang,
            $client_name,
            $username,
            $password, 
            $days_trial, 
            $purchased_module,
            $impresion_amount,
            $item_name,
            $subsidary_name) {//HL 2013-04-22 
        
        if(!$lang) 
            $lang="english";
        
        $CI =& get_instance();
        $CI->load->library('email');
        
        $std = $CI->db->get_where('phppos_std_letter', array('stdletter_id_name'=>"$stdletter_id_name", 'stdletter_lang'=> "$lang"));
        //echo $CI->db->last_query();

        if($std->num_rows == 1)
        { 
            $std = $std->row();
            $body = $std->body;
            
            //echo $body;
            
            $body = str_replace("{client_name}", $client_name, $body);
            $body = str_replace("{username}", $username, $body);
            $body = str_replace("{password}", $password, $body);
            $body = str_replace("{days_trial}", $days_trial, $body);
            $body = str_replace("{purchased_module}", $purchased_module, $body);
            $body = str_replace("{impresion_count}", $impresion_amount, $body);
            $body = str_replace("{item_name}", $item_name, $body);//HL 2013-04-22
            $body = str_replace("{subsidary_name}", $subsidary_name, $body);//HL 2013-04-22
           
            $body = str_replace("{base_url}", base_url(), $body);
            $body = str_replace("{compra}", "{compra}", $body);
            $body = str_replace("{recuperar}", "index.php/login/recover_User_And_Password", $body);
            $body = str_replace("{resuperar_nombre}", "index.php/login/recover_User_And_Password", $body);
            
            $headers = "Content-Type: text/html; charset=utf-8 \r\n";
            $headers .= "From: support@iandresa.com";
			//echo $stdletter_id_name;
            
            if(!mail($email, $std->subject, $body, $headers ))
                echo "Leter name: $stdletter_id_name<br>Email: $email<br>Client name: $client_name";

             //echo base_url();
            
//            $config['mailtype'] = 'text';
//            $config['smtp_host'] = '10.10.0.3';
//            $config['wordwrap'] = false;
//            $config['charset'] = 'utf-8';
//            
//            $CI->email->clear();
//            $CI->email->initialize($config);
//            $CI->email->from($std->email_from);
//            $CI->email->to("ariel@icid.cu");
//            $CI->email->subject($std->subject);
//            $CI->email->message($body);
//            //$CI->email->set_alt_message("hole: this is alt text"); 
//            $CI->email->send();
            
//           
//            
//             
//            $headers = "From: " . strip_tags($_POST['req-email']) . "\r\n";
//            $headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
//            $headers .= "CC: susan@example.com\r\n";
//            $headers .= "MIME-Version: 1.0\r\n";

//             $from = "Sandra Sender <sender@example.com>";
//             $subject = "Hi!";
//             $host = "10.10.0.3";
//             $username = "migue";
//             $password = "****";
//
//             $headers = array ('From' => $from,
//               'Subject' => $subject);
//             $smtp = factory('smtp',
//               array ('host' => $host,
//                 'auth' => true,
//                 'username' => $username,
//                 'password' => $password));
//            
//            $mail = $smtp->send("ariel@icid.cu", $headers, $body);
        }
    }
    
}

?>
