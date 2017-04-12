            </div>
        </div>
        
        <div id="footer">
            

            <?php echo $this->lang->line('common_you_are_using_phppos').mailto("savia@savia.com")?>   
            
          <!--  <a href="www.saviard.cl" target="_blank">
                <?php echo $this->lang->line('common_website'); ?>
            </a> -->       
        
            <div id="google_translate_element"></div>         
      
        </div>
        <center>
            <?php
                $adds = $this->Campaign_model->get_nextadvises('bottom');

                $ban_margintop = 100;

                echo "<div style='height:0px; margin-top:0px'>";
                for($i = 0 ; $i < count($adds); $i++)
                {
                    $openLink = $this->Adviser->get_campaign_link($adds[$i]);  
                    echo $openLink ;
                    echo "<img border='0' style='margin-right:5px' alt='banner' height='".$this->config->item('banner_bottom_height')."' width='".$this->config->item('banner_bottom_width')."' src='".base_url()."images/banners_pics/".$adds[$i]['image_large']."'/>";
                    echo "</a>";
                }
                echo "</div>";            ?>
        </center>
            
         
 <a href="https://www.instantssl.com" id="comodoTL"></a>

        <script language="JavaScript" type="text/javascript">
            COT("https://www.iandresa.com/images/menubar/ssl.gif", "SC2", "none");
        </script>
        
        <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        <script language="JavaScript" type="text/javascript">
            function googleTranslateElementInit() {
              new google.translate.TranslateElement({
                pageLanguage: '<?php echo ($this->session->userdata('lang')=="spanish")?'es':'en'; ?>'
              }, 'google_translate_element');
            }
            googleTranslateElementInit();
        </script>

      

    </body>
</html>