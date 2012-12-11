<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-1.8.2.min.js'); ?>"></script>
<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/highstock.src.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/exporting.js'); ?>"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    var unit_result = <?php echo $unit_result; ?>;
    //var package_result = <?php echo $package_result; ?>;

    var unit_price = [],
        unit_quantity = [];

    for(var i = 0; i < unit_result.length; i++) {
        unit_price.push([
            unit_result[i][0],
            unit_result[i][1]
        ]);

        unit_quantity.push([
            unit_result[i][0],
            unit_result[i][2]
        ])
    }

    chart = new Highcharts.StockChart({
        chart: {
            renderTo: 'container',
            alignTicks: false
        },

        rangeSelector: {
            selected: 0
        },

        title: {
            text: 'Preisentwicklung'
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

        series: [{
            name: 'Preis',
            data: unit_price
        }, {
            name: 'Quantität',
            data: unit_quantity,
            yAxis: 1
        }]
    }, function(chart){
        setTimeout(function(){
            $('input.highcharts-range-selector', $('#' + chart.options.chart.renderTo)).datepicker();
        },0)
    });

    $.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd',
        onSelect: function() {
            this.onchange();
            this.onblur();
        }
    });
});
//]]>
</script>
<div id="container" style="height: 600px;"></div>