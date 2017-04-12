<html>
    <head>
        <link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/combo.css" />
        <link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/carousel.css" />
        <link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/yui.css" />
        
        
        <script src="<?php echo base_url();?>js/combo.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
        <script src="<?php echo base_url();?>js/carousel.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
    
    
        <style type="text/css">
            .carousel-component { 
                padding:8px 16px 4px 16px;
                margin:0px;
            }

            .carousel-component .carousel-list li { 
                margin:4px;
                width:79px; /* img width is 75 px from flickr + a.border-left (1) + a.border-right(1) + 
                               img.border-left (1) + img.border-right (1)*/
                height:93px; /* image + row of text (87) + border-top (1) + border-bottom(1) + margin-bottom(4) */
                /*	margin-left: auto;*/ /* for testing IE auto issue */
            }

            .carousel-component .carousel-list li a { 
                display:block;
                border:1px solid #e2edfa;
                outline:none;
            }

            .carousel-component .carousel-list li a:hover { 
                border: 1px solid #aaaaaa; 
            }

            .carousel-component .carousel-list li img { 
                border:1px solid #999;
                display:block; 
            }

            .carousel-component .carousel-prev { 
                position:absolute;
                top:40px;
                z-index:3;
                cursor:pointer; 
                left:5px; 
            }

            .carousel-component .carousel-next { 
                position:absolute;
                top:40px;
                z-index:3;
                cursor:pointer; 
                right:5px; 
            }
        </style>

        <script type="text/javascript">

        /**
         * Custom button state handler for enabling/disabling button state. 
         * Called when the carousel has determined that the previous button
         * state should be changed.
         * Specified to the carousel as the configuration
         * parameter: prevButtonStateHandler
         **/
        var handlePrevButtonState = function(type, args) {

            var enabling = args[0];
            var leftImage = args[1];
            if(enabling) {
                leftImage.src = "../images/left-enabled.gif";	
            } else {
                leftImage.src = "../images/left-disabled.gif";	
            }

        };

        /**
         * Custom button state handler for enabling/disabling button state. 
         * Called when the carousel has determined that the next button
         * state should be changed.
         * Specified to the carousel as the configuration
         * parameter: nextButtonStateHandler
         **/
        var handleNextButtonState = function(type, args) {

            var enabling = args[0];
            var rightImage = args[1];

            if(enabling) {
                rightImage.src = "../../images/right-enabled.gif";
            } else {
                rightImage.src = "../../images/right-disabled.gif";
            }

        };


        /**
         * You must create the carousel after the page is loaded since it is
         * dependent on an HTML element (in this case 'mycarousel'.) See the
         * HTML code below.
         **/
        var carousel; // for ease of debugging; globals generally not a good idea
        var pageLoad = function() 
        {
            carousel = new YAHOO.extension.Carousel("mycarousel", 
                {
                    numVisible:        3,
                    animationSpeed:    0.15,
                    scrollInc:         3,
                    navMargin:         20,
                    prevElement:     "prev-arrow",
                    nextElement:     "next-arrow",
                    size:              7,
                    prevButtonStateHandler:   handlePrevButtonState,
                    nextButtonStateHandler:   handleNextButtonState
                }
            );

        };

        YAHOO.util.Event.addListener(window, 'load', pageLoad);

        </script>
        
    </head>
    <body>
        
        <div style="width: 301px; display: block;" id="mycarousel" class="carousel-component">
            <div class="carousel-prev">
                <img id="prev-arrow" class="left-button-image" src="../../images/left-enabled.gif" alt="Previous Button">
            </div>
            <div class="carousel-next">
                <img id="next-arrow" class="right-button-image" src="../../images/right-enabled.gif" alt="Next Button">
            </div>
            <div style="width: 261px;" class="carousel-clip-region">
                <ul style="position: relative; left: -261px; top: 0px;" class="carousel-list carousel-horizontal">
<?php
                    $id = 1;
                    foreach($allowed_modules->result() as $module)
                    {
            //                echo "<div class='menu_item'>";
            //                echo "<a href='".site_url($module->module_id)."'>";
            //                echo "<img src='".base_url().'images/menubar/'.$module->module_id.".png' border='0' alt='Menubar Image' /></a><br />";
            //                echo "<a href='".site_url($module->module_id)."'>".$this->lang->line("module_".$module->module_id)."</a>";
            //                echo "</div>";

                        echo "                <li id='mycarousel-item-".$id++."'>\n";
                        echo "                    <a href='".site_url($module->module_id)."'>\n";
                        echo "                        <img src='".base_url().'images/menubar/'.$module->module_id.".png' border='0' alt='Menubar Image' >\n";
                        echo "                    </a>\n";
                        echo "                    ".$this->lang->line("module_".$module->module_id)."\n";
                        echo "                </li>\n";
                    } 
                    ?>
                </ul>
            </div>
        </div>
        
    </body>
    
    
</html>