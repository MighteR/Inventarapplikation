<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<link href="<?php echo base_url('application/views/template/css/select2.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/select2.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/highstock.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/highstock_exporting.js'); ?>"></script>
<script type="text/javascript">
    //<![CDATA[
$(document).ready(function(){
    $('button').button();
    
    /*var changed     = <?php echo $changed; ?>;
    var sMessage    ='<?php echo $this->lang->line('notice_unsaved_data') ?>';

    $(window).bind('beforeunload', function(e){
        if (changed) return sMessage;
    });
    
    $(document).keypress(function(e){ 
        var element = e.target.nodeName.toLowerCase(); 
        if (e.keyCode == 13 && element != 'textarea'){ 
            return false; 
        }
    });

    $("input[type='text'], select, textarea").change(function(){
        changed = true;
    });*/
        
    $('#list').click(function(){
        price_list();
    });
    
    $('#trend').click(function(){
        generate_price_trend();
    });
    
    $('#reset').click(function(){

    });
    
    $('#form').submit(function(){
        changed = false;
        $('input:disabled, select:disabled').each(function(i){
            this.disabled = false;
        });

        $("input[type='submit']").each(function(i){
            this.disabled = true;
        });
    });
    
    $('#product').select2({
        formatSelection: function(selection){ 
            return selection.text; 
        },
        placeholder: '<?php echo lang('title_search_product'); ?>',
        formatNoMatches: function(term){
            return '<?php echo lang('title_no_matches_found'); ?>';
        },
        formatSearching: function(term){
            return '<?php echo lang('title_searching'); ?>';
        },
        formatLoadMore: function(page){
            return '<?php echo lang('title_loading_more_results'); ?>';
        },
        allowClear: true,
        quietMillis: 100,
        ajax: {
            url: '<?php echo base_url('product/simple_search_list'); ?>',
            type: 'POST',
            dataType: 'json',
            quietMillis: 100,
            data: function (term, page) {
                return {
                    name: term,
                    page: page
                };
            },
            results: function (data, page) {
                var more = (page * 10) < data.total;

                return {
                    results: data.results,
                    more: more
                };
            }
        }
    });    
    
    $("#date_from").each(function(){
        $(this).datepicker({            
            dateFormat: 'dd.mm.yy',
            altField: '#' + $(this).attr('name') + '_db',
            altFormat: "yymmdd",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 3,
            onClose: function(selectedDate){
                $("#date_to").datepicker("option","minDate",selectedDate);
            }
        });
    });
    
    $("#date_to").each(function(){
        $(this).datepicker({            
            dateFormat: 'dd.mm.yy',
            altField: '#' + $(this).attr('name') + '_db',
            altFormat: "yymmdd",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 3,
            onClose: function(selectedDate){
                $("#date_from").datepicker("option","maxDate",selectedDate);
            }
        });
    });
    
    function generate_price_trend(){
        clearErrors();
        
        $('#loader').dialog({
                closeOnEscape: false,
                dialogClass: 'loader',
                height: 50,
                resizable: false,
                width: 50
        });

        $.ajax({
            complete: function(html){
                $('#loader').dialog('close');
            },
            url: '<?php echo base_url('report/price_picture'); ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                'id': $('#product').val(),
                'date_from': $('#date_from_db').val(),
                'date_to': $('#date_to_db').val()
            },
            success: function(data){
                if(data.verify){
                    $('#price_data').css('min-height','600px');
                    
                    var unit_price = [],
                        unit_quantity = [],
                        seriesOptions = [];

                    var counter = 0;

                    for(var i = 0; i < data.unit_data.length; i++) {
                        unit_price.push([
                            data.unit_data[i][0],
                            data.unit_data[i][1]
                        ]);

                        unit_quantity.push([
                            data.unit_data[i][0],
                            data.unit_data[i][2]
                        ])
                    }

                    seriesOptions[counter++] = {
                        name: 'Unit Preis',
                        data: unit_price
                    };

                    seriesOptions[counter++] = {
                        name: 'Unit Quantity',
                        data: unit_quantity,
                        yAxis: 1
                    };

                    if(data.package_data != undefined){
                        var package_price = [],
                            package_quantity = [];

                        for(var i = 0; i < data.package_data.length; i++) {
                            package_price.push([
                                data.package_data[i][0],
                                data.package_data[i][1]
                            ]);

                            package_quantity.push([
                                data.package_data[i][0],
                                data.package_data[i][2]
                            ])
                        }

                        seriesOptions[counter++] = {
                            name: 'Package Preis',
                            data: package_price
                        };

                        seriesOptions[counter++] = {
                            name: 'Package Quantity',
                            data: package_quantity,
                            yAxis: 1
                        };

                    }

                    chart = new Highcharts.StockChart({
                        chart: {
                            renderTo: 'price_data'
                        },
                        rangeSelector: {
                            //selected: 1
                            enabled: false
                        },
                        title: {
                            text: 'Preisentwicklung'
                        },
                        navigator: {
                            top: 500
                        },
                        legend: {
                            enabled: true
                        },
                        yAxis: [{
                            title: {
                                text: 'Preis'
                            },
                            height: 200,
                            lineWidth: 2
                        }, {
                            title: {
                                text: 'Quantität'
                            },
                            top: 300,
                            height: 200,
                            offset: 0,
                            lineWidth: 2
                        }],
                        series: seriesOptions                    
                    });
                }else{
                    if(data.error.date_from){
                        $('#error_class_date_from').parent().prepend('<div id="notice_date_from" class="notice">' + data.error.date_from + '</div>');
                        $('#error_class_date_from').addClass('text_left_error');
                        $('#date_from').addClass('formular_error');
                    }

                    if(data.error.date_to){
                        $('#error_class_date_to').parent().prepend('<div id="notice_date_to" class="notice">' + data.error.date_to + '</div>');
                        $('#error_class_date_to').addClass('text_left_error');
                        $('#date_to').addClass('formular_error');
                    }
                    
                    if(data.error.product){
                        $('#error_class_product').parent().prepend('<div id="notice_product" class="notice">' + data.error.product + '</div>');
                        $('#error_class_product').addClass('text_left_error');
                    }
                    
                    if(data.error.trend){
                        alert(data.error.trend);
                    }
                }
            }
        });
    }
});

function price_list(){
    clearErrors();

    $('#loader').dialog({
        closeOnEscape: false,
        dialogClass: 'loader',
        height: 50,
        resizable: false,
        width: 50
    });

    $.ajax({
        complete: function(html){
            $('#loader').dialog('close');
        },
        url: '<?php echo base_url('report/price_index'); ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            'id': $('#product').val(),
            'date_from': $('#date_from_db').val(),
            'date_to': $('#date_to_db').val()
        },
        success: function(data){
            if(data.verify){
                $('#price_data').html(data.output);
            }else{
                if(data.error.date_from){
                    $('#error_class_date_from').parent().prepend('<div id="notice_date_from" class="notice">' + data.error.date_from + '</div>');
                    $('#error_class_date_from').addClass('text_left_error');
                    $('#date_from').addClass('formular_error');
                }

                if(data.error.date_to){
                    $('#error_class_date_to').parent().prepend('<div id="notice_date_to" class="notice">' + data.error.date_to + '</div>');
                    $('#error_class_date_to').addClass('text_left_error');
                    $('#date_to').addClass('formular_error');
                }

                if(data.error.product){
                    $('#error_class_product').parent().prepend('<div id="notice_product" class="notice">' + data.error.product + '</div>');
                    $('#error_class_product').addClass('text_left_error');
                }

                if(data.error.trend){
                    alert(data.error.trend);
                }
            }
        },
        error: function(a,b,c){
            document.write(a.responseText);
        }
    });
}

function reset(){
    clearErrors();
}

function clearErrors(){
    $('#price_data').html('');
    $('#notice_date_from').remove();
    $('#error_class_date_from').removeClass('text_left_error');
    $('#date_from').removeClass('formular_error');
    $('#notice_date_to').remove();
    $('#error_class_date_to').removeClass('text_left_error');
    $('#date_to').removeClass('formular_error');
    $('#notice_product').remove();
    $('#error_class_product').removeClass('text_left_error');
}
//]]>
</script>
<div id="content_title">
    <span><?php echo lang('title_price_trend'); ?></span>
</div>
<div class="first">
    <div style="float:left;width:40%;">
        <div id="error_class_date_from" class="text_left">
            <?php echo lang('title_date_from','date_from'); ?><span class="important">*</span>
        </div>
        <div class="text_right">
            <input name="date_from" id="date_from" class="formular date" type="text" size="10" value="" />
            <input name="date_from_db" id="date_from_db" type="hidden" value="" />
        </div>
    </div>
    <div style="float:left;width:40%;">
        <div id="error_class_date_to" class="text_left">
            <?php echo lang('title_date_to','date_to'); ?><span class="important">*</span>
        </div>
        <div class="text_right">
            <input name="date_to" id="date_to" class="formular date" type="text" size="10" value="" />
            <input name="date_to_db" id="date_to_db" type="hidden" value="" />
        </div>
    </div>
</div>
<div class="second">
    <div id="error_class_product" class="text_left">
        <?php echo lang('title_product','set_product'); ?><span class="important">*</span>
    </div>
    <div class="text_right">
        <input name="product" id="product" style="width:300px;" type="hidden" value=""/>
    </div>
</div>
<div class="first">
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <button name="list" type="button" id="list" ><?php echo lang('title_submit'); ?></button>
        <button name="trend" type="button" id="trend" ><img alt="excel" name="excel" src="<?php echo base_url('application/views/template/images/reactivate.png'); ?>" />&nbsp;<?php echo lang('title_submit'); ?></button>
        <button name="reset" type="button" id="reset" ><?php echo lang('title_reset'); ?></button>
    </div>
</div>
<div id="price_data"></div>