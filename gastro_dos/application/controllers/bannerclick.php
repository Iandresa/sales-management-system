<?php
class Bannerclick extends Controller
{
    function __construct()
    {
        parent::__construct('bannerclick');
    }

 

    function click($campaign_id)
    {
        if($campaign_id > 0)
        {
            $campaign = $this->Campaign_model->get_campaign($campaign_id);
            if($campaign->num_rows() == 1)
            {
                $campaign = $campaign->row_array();
                $this->Campaign_model->clicked_one_time($campaign);

                redirect($campaign['link']);
            }
        }
        //Ariel: Cuando $campaign_id=-1 es porque es un anuncio fictisio
        //       y se redirecciona para la página de show_publish_how.
        else
        {
            $lang = $this->config->item('language');
            redirect("login/show_publish_how/$lang");
        }
    }
 
}
?>