<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<?php
echo form_open('items/find_item_info/' . $item_info->item_id, array('id' => 'item_number_form'));
?>
<?php
echo form_close();
echo form_open('items/save/' . $item_info->item_id, array('id' => 'item_form'));
?>
<fieldset id="item_basic_info">
    <legend><?php echo $this->lang->line("items_basic_information"); ?></legend>

    <div class="field_row clearfix">
        <?php echo form_label($this->lang->line('items_item_number') . ':', 'name', array('class' => 'wide')); ?>
        <div class='form_field'>
            <?php
            echo form_input(array(
                'name' => 'item_number',
                'id' => 'item_number',
                'value' => $item_info->item_number)
            );
            ?>
        </div>
    </div>
    
    <div id="divIsForSale" name="divIsForSale" class="field_row clearfix">
        <?php echo form_label($this->lang->line('items_for_sale') . ':', 'item_for_sale', array('class' => 'wide')); ?>
        <div class='form_field'>
            <?php
            $data = array(
                'name' => 'is_for_sale',
                'id' => 'is_for_sale',
                'value' => '1',
                'checked' => ($editing) ? (($item_info->is_forSale) ? 1 : 0) : TRUE,
                'onClick' => 'set_item_for_sale()'
            );

            echo form_checkbox($data);
            ?>
        </div>
    </div>
    
    <div class="field_row clearfix">
        <?php echo form_label($this->lang->line('items_invoice') . ':', 'invoice_number', array('class' => 'wide')); ?>
        <div class='form_field'>
            <?php
            echo form_input(array(
                'name' => 'invoice_number',
                'id' => 'invoice_number',
                'value' => $item_info->invoice_number)
            );
            ?>
        </div>
    </div>
    
    <div class="field_row clearfix">
        <?php echo form_label($this->lang->line('items_name') . ':', 'name', array('class' => 'required wide')); ?>
        <div class='form_field'>
            <?php
            echo form_input(array(
                'name' => 'name',
                'id' => 'name',
                'value' => $item_info->name)
            );
            ?>
            <?php
            echo form_input(array(
                'name' => 'nameinuse',
                'id' => 'nameinuse',
                'value' => $nameinuse,
                'type' => 'hidden')
            );
            ?>
        </div>
    </div>


    <div class="field_row clearfix">
        <?php echo form_label($this->lang->line('items_category') . ':', 'category', array('class' => 'required wide')); ?>
        <div class='form_field'>
            <?php
            echo form_input(array(
                'name' => 'category',
                'id' => 'category',
                'value' => $item_info->category)
            );
            ?>
        </div>
    </div>

    <div class="field_row clearfix">
        <?php echo form_label($this->lang->line('items_supplier') . ':', 'supplier', array('class' => 'required wide')); ?>
        <div class='form_field'>
            <?php echo form_dropdown('supplier_id', $suppliers, $selected_supplier); ?>
        </div>
    </div>
    
    <div id="divItems" name="divItems" class="field_row clearfix">
        <?php echo form_label($this->lang->line('items_item') . ':', 'item', array('class' => 'required wide')); ?>
        <div class='form_field'>
            <?php echo form_dropdown('item_id', $items, $selected_item); ?>
        </div>
    </div>

    <div id="divQuantityToUse" name="divQuantityToUse" class="field_row clearfix">
        <?php echo form_label($this->lang->line('items_quantity_to_use') . ':', 'quantity_to_use', array('class' => 'required wide')); ?>
        <div class='form_field'>
            <?php
            echo form_input(array(
                'name' => 'quantity_to_use',
                'size' => '8',
                'id' => 'quantity_to_use',
                'value' => $item_info->quantity_to_use)
            );
            ?>
        </div>
    </div>
    
    <div id="divQuantityToUse" name="divQuantityToUse_hide" class="field_row clearfix" style="display: none;">
        <?php echo form_label($this->lang->line('items_quantity_to_use') . ':', 'quantity_to_use', array('class' => 'required wide')); ?>
        <div class='form_field'>
            <?php
            echo form_input(array(
                'name' => 'quantity_to_use_hide',
                'size' => '8',
                'id' => 'quantity_to_use_hide',
                'value' => $item_info->quantity_to_use)
            );
            ?>
        </div>
    </div>
    
    <div class="field_row clearfix">
        <?php echo form_label($this->lang->line('items_cost_price') . ':', 'cost_price', array('class' => 'required wide')); ?>
        <div class='form_field'>
            <?php
            echo form_input(array(
                'name' => 'cost_price',
                'size' => '8',
                'id' => 'cost_price',
                'value' => $item_info->cost_price)
            );
            ?>
        </div>
    </div>

    <div id="divUnitPrice" name="divUnitPrice" class="field_row clearfix">
        <?php echo form_label($this->lang->line('items_unit_price') . ':', 'unit_price', array('class' => 'required wide')); ?>
        <div class='form_field'>
            <?php
            echo form_input(array(
                'name' => 'unit_price',
                'size' => '8',
                'id' => 'unit_price',
                'value' => $item_info->unit_price)
            );
            ?>
        </div>
    </div>
    
    <div id="divUnitPrice" name="divUnitPrice_hide" class="field_row clearfix" style="display: none;">
        <?php echo form_label($this->lang->line('items_unit_price') . ':', 'unit_price', array('class' => 'required wide')); ?>
        <div class='form_field'>
            <?php
            echo form_input(array(
                'name' => 'unit_price_hide',
                'size' => '8',
                'id' => 'unit_price_hide',
                'value' => $item_info->unit_price)
            );
            ?>
        </div>
    </div>

    <div id="divItemsTaxSubsidary" name="divItemsTaxSubsidary" class="field_row clearfix">	
        <?php echo form_label($this->lang->line('items_tax_subsidary') . ':', 'taxes', array('class' => 'wide')); ?>
        <div class='form_field'>
            <?php
            $data = array(
                'name' => 'set_taxes',
                'id' => 'set_taxes',
                'value' => '1',
                'checked' => ($editing) ? (($item_info->taxes_from_subsidary) ? 1 : 0) : TRUE,
                'onClick' => 'set_subsidary_taxes()'
            );

            echo form_checkbox($data);
            ?>
        </div>
    </div>

    <div id="divTax1" name="divTax1" class="field_row clearfix">
        <?php echo form_label($this->lang->line('items_tax_1') . ':', 'tax_percent_1', array('class' => 'wide')); ?>
        <div class='form_field'>
            <?php
            echo form_input(array(
                'name' => 'tax_percents[]',
                'id' => 'tax_percent_name_1',
                'size' => '3',
                'value' => isset($item_tax_info[0]['percent']) ? $item_tax_info[0]['percent'] : $default_tax_1_rate)
            );
            ?>
	<span id="percent_1"><?php echo $this->lang->line('items_percent'); ?></span>
        </div>
    </div>

    <div id="divTax2" name="divTax2" class="field_row clearfix">
        <?php echo form_label($this->lang->line('items_tax_2') . ':', 'tax_percent_2', array('class' => 'wide')); ?>
        <div class='form_field'>
            <?php
            echo form_input(array(
                'name' => 'tax_percents[]',
                'id' => 'tax_percent_name_2',
                'size' => '3',
                'value' => isset($item_tax_info[1]['percent']) ? $item_tax_info[1]['percent'] : $default_tax_2_rate)
            );
            ?>
	<span id="percent_2"><?php echo $this->lang->line('items_percent'); ?></span>
        </div>
    </div>


    <div class="field_row clearfix">

        <?php
        if (!isset($use_0_inQuantity) || !$use_0_inQuantity)
        {
            echo form_label($this->lang->line('items_quantity') . ':', 'quantity', array('class' => 'required wide'));
            ?>
            <div class='form_field'>
                <?php
                echo form_input(array(
                    'name' => 'quantity',
                    'id' => 'quantity',
                    'value' => $item_info->quantity));
        }
        ?>
        </div>
    </div>

    <div id="divReorderLevel" name="divReorderLevel" class="field_row clearfix">
        <?php echo form_label($this->lang->line('items_reorder_level') . ':', 'reorder_level', array('class' => 'required wide', 'title' => $this->lang->line('items_reorder_level_tooltip'))); ?>
        <div class='form_field'>
            <?php
            echo form_input(array(
                'name' => 'reorder_level',
                'id' => 'reorder_level',
                'value' => $item_info->reorder_level), '', "title='{$this->lang->line('items_reorder_level_tooltip')}'");
            ?>
        </div>
    </div>
    
    <div id="divReorderLevel" name="divReorderLevel_hide" class="field_row clearfix" style="display: none;">
        <?php echo form_label($this->lang->line('items_reorder_level') . ':', 'reorder_level', array('class' => 'required wide', 'title' => $this->lang->line('items_reorder_level_tooltip'))); ?>
        <div class='form_field'>
            <?php
            echo form_input(array(
                'name' => 'reorder_level_hide',
                'id' => 'reorder_level_hide',
                'value' => $item_info->reorder_level), '', "title='{$this->lang->line('items_reorder_level_tooltip')}'");
            ?>
        </div>
    </div>

    <div class="field_row clearfix">
        <?php echo form_label($this->lang->line('items_description') . ':', 'description', array('class' => 'wide')); ?>
        <div class='form_field'>
            <?php
            echo form_textarea(array(
                'name' => 'description',
                'id' => 'description',
                'value' => $item_info->description,
                'rows' => '5',
                'cols' => '17')
            );
            ?>
        </div>
    </div>

    <div id="divAllowAltDescription" name="divAllowAltDescription" class="field_row clearfix">
        <?php echo form_label($this->lang->line('items_allow_alt_desciption') . ':', 'allow_alt_description', array('class' => 'wide', 'title' => $this->lang->line('allow_alt_description_tooltip'))); ?>
        <div class='form_field'>
            <?php
            echo form_checkbox(array(
                'name' => 'allow_alt_description',
                'id' => 'allow_alt_description',
                'value' => 1,
                'checked' => ($item_info->allow_alt_description) ? 1 : 0)
            );
            ?>
        </div>
    </div>

    <div id="divIsSerialized" name="divIsSerialized" class="field_row clearfix">
        <?php echo form_label($this->lang->line('items_is_serialized') . ':', 'is_serialized', array('class' => 'wide')); ?>
        <div class='form_field'>
            <?php
            echo form_checkbox(array(
                'name' => 'is_serialized',
                'id' => 'is_serialized',
                'value' => 1,
                'checked' => ($item_info->is_serialized) ? 1 : 0)
            );
            ?>
        </div>
    </div>

    <?php
    echo form_submit(array(
        'name' => 'submit',
        'id' => 'submit',
        'value' => $this->lang->line('common_submit'),
        'class' => 'submit_button float_right')
    );
    ?>
</fieldset>
<?php
echo form_close();
?>
<script type='text/javascript'>

    //validation and submit handling
    $(document).ready(function()
    {
        set_item_for_sale();
        
        set_subsidary_taxes();
    
        $("#category").autocomplete("<?php echo site_url('items/suggest_category'); ?>",{max:100,minChars:0,delay:10});
        $("#category").result(function(event, data, formatted){});
        $("#category").search();

        $('#item_form').validate({
            submitHandler:function(form)
            {
                /*
                        make sure the hidden field #item_number gets set
                        to the visible scan_item_number value
                 */
                $('#item_number').val($('#scan_item_number').val());
                $(form).ajaxSubmit({
                    success:function(response)
                    {
                        tb_remove();
                        post_item_form_submit(response); 
                    },
                    dataType:'json'
                });

            },
            errorLabelContainer: "#error_message_box",
            wrapper: "li",
            rules:
                {
                invoice_number:
                { 
                    number:true
                },
                name: "required",
                
                category:"required",
                nameinuse:"required",
                quantity_to_use:
                    {
                    required:true,
                    number:true
                },
                cost_price:
                    {
                    required:true,
                    number:true
                },

                unit_price:
                    {
                    required:true,
                    number:true
                },
                tax_percent:
                    {
                    required:true,
                    number:true
                },
                quantity:
                    {
                    required:true,
                    number:true
                },
                reorder_level:
                    {
                    required:true,
                    number:true
                }
            },
            messages:
                {
                invoice_number:
                {
                    number:"<?php echo $this->lang->line('items_invoice_number'); ?>"
                },
                name:"<?php echo $this->lang->line('items_name_required'); ?>",
                nameinuse:"<?php echo $this->lang->line('items_name_in_use'); ?>",
                category:"<?php echo $this->lang->line('items_category_required'); ?>",
                quantity_to_use:
                    {
                    required:"<?php echo $this->lang->line('items_quantity_to_use_required'); ?>",
                    number:"<?php echo $this->lang->line('items_quantity_to_use_number'); ?>"
                },
                cost_price:
                    {
                    required:"<?php echo $this->lang->line('items_cost_price_required'); ?>",
                    number:"<?php echo $this->lang->line('items_cost_price_number'); ?>"
                },
                unit_price:
                    {
                    required:"<?php echo $this->lang->line('items_unit_price_required'); ?>",
                    number:"<?php echo $this->lang->line('items_unit_price_number'); ?>"
                },
                tax_percent:
                    {
                    required:"<?php echo $this->lang->line('items_tax_percent_required'); ?>",
                    number:"<?php echo $this->lang->line('items_tax_percent_number'); ?>"
                },
                quantity:
                    {
                    required:"<?php echo $this->lang->line('items_quantity_required'); ?>",
                    number:"<?php echo $this->lang->line('items_quantity_number'); ?>"
                },
                reorder_level:
                    {
                    required:"<?php echo $this->lang->line('items_reorder_level_required'); ?>",
                    number:"<?php echo $this->lang->line('items_reorder_level_number'); ?>"
                }

            }
        });
    });
 
    function set_item_for_sale()
    {
        $.ajax({        
            type: "POST",
            url: "<?php echo site_url(); ?>/items/set_item_for_sale",
            dataType: "json",
            success: function() {
                if($('#is_for_sale').attr('checked'))
                {
                    //$("#unit_price").val("");
                    <?php if($editing) { ?> 
                        $("#quantity_to_use").val($("#quantity_to_use_hide").val());
                    <?php } else { ?>
                        $("#quantity_to_use").val(0);
                    <?php } ?>
                    $("#divUnitPrice").css('display','block');
                    
                    $("#divItems").css('display','none');
                    $("#divQuantityToUse").css('display','none');
                    
                    $("#divItemsTaxSubsidary").css('display','block');
                    $("#divTax1").css('display','block');
                    $("#divTax2").css('display','block');
                    //$("#reorder_level").val("");//HL (2013-05-16)
                    $("#divReorderLevel").css('display','block');
                    $("#divAllowAltDescription").css('display','block');
                    $("#divIsSerialized").css('display','block');       
                }
                else 
                {
                    <?php if($editing) { ?> 
                        $("#unit_price").val($("#unit_price_hide").val());
                    <?php } else { ?>
                        $("#unit_price").val(0);
                    <?php } ?>
                    //$("#quantity_to_use").val("");
                    
                    $("#divUnitPrice").css('display','none');
                    
                    $("#divItems").css('display','block');
                    $("#divQuantityToUse").css('display','block');
                    
                    $("#divItemsTaxSubsidary").css('display','none');
                    $("#divTax1").css('display','none');
                    $("#divTax2").css('display','none');
                    
                    <?php if($editing) { ?> 
                        $("#reorder_level").val($("#reorder_level_hide").val());
                    <?php } else { ?>
                        $("#reorder_level").val(0);
                    <?php } ?>
                        
                    $("#divReorderLevel").css('display','none');
                    $("#divAllowAltDescription").css('display','none');
                    $("#divIsSerialized").css('display','none');
                }
            }
        });
    }
    
    function set_subsidary_taxes()
    {
        //var $data = $('#set_taxes').attr('checked');
        $.ajax({        
            type: "POST",
            url: "<?php echo site_url(); ?>/items/set_subsidary_taxes",
            //data : "checked="+$data,
            dataType: "json",
            success: function(result) {
                if($('#set_taxes').attr('checked'))
                {
                    /*if(result.tax_percent1 && result.tax_percent2)
                    {
                    $('#divTax1').css('display','block');
                    $('#divTax2').css('display','block');*/
                    //$('#tax_percent_name_1').css('display','none');
                    $("#tax_percent_name_1").val(result.tax_percent1);  
                    $("#tax_percent_name_2").val(result.tax_percent2);
                    $('#tax_percent_name_1').css('background-color','grey');
                    $('#tax_percent_name_2').css('background-color','grey');
                    $('#tax_percent_name_1').attr('readonly','true');
                    $('#tax_percent_name_2').attr('readonly','true');
                    //}             
                }
                else 
                {
                    /*if(result.tax_percent1 && result.tax_percent2)
                    {
                    $('#divTax1').css('display','block');
                    $('#divTax2').css('display','block');*/
                    //$('#tax_percent_name_1').css('display','block');
                    $('#tax_percent_name_1').css('background-color','white');
                    $('#tax_percent_name_2').css('background-color','white');
                    $('#tax_percent_name_1').attr('readonly','');
                    $('#tax_percent_name_2').attr('readonly','');
                    //}
                }
            }
        });
    }
 
    //esto tiene su mareito HL(2013-03-15)
    $("#name").change(function(){
        checkname();
    });
    
    $("#item_form").submit(function(){
        
        var l = $("#error_message_box li").length;
        $("#error_message_box li").each(function (){
            if($(this).css("display") == "none")
                l--;
        });
        if(l == 0)
            $('#submit').remove();
    });
    
    function checkname(){
        $.post("<?php echo site_url('items/check_item_name'); ?>", {iten_name: $("#name").val()}, function(data){
            $("#nameinuse").val(data);
        }, "html");
    }

</script>