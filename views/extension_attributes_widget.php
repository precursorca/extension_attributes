<?php if(isset($attributeName)):?>
    <?php $attributeList = [$attributeName]?>
<?php else:?>
    <?php configAppendFile(__DIR__ . '/../config.php')?>
    <?php $attributeList = conf('attributes_to_track', [])?>
<?php endif?>

<?php foreach( $attributeList as $string):?>
<div class="col-lg-4 col-md-6">
    <div class="panel panel-default extension-attributes-widget" data-ident="<?php echo $string;?>">
        <div class="panel-heading" data-container="body">
            <h3 class="panel-title"><i class="fa fa-qrcode"></i> <span></span></h3>
        </div>
        <div class="list-group scroll-box"></div>
    </div><!-- /panel -->
</div><!-- /col -->
<?php endforeach?>

<script>

$(document).on('appReady', function(){

    var baseUrl = appUrl + '/module/extension_attributes/attributesWidget/',
        attributes = [];

    // Compile list of attributes
    $('.extension-attributes-widget').each(function(){

        var attributeName = $(this).data('ident');
        // Set tooltip
        $(this).find('div.panel-heading')
            .attr('title', i18n.t('extension_attributes.extension_attributes', {attribute: attributeName}))
            .tooltip();

        // Create url
        var url = appUrl + '/show/listing/extension_attributes/extension_attributes/#' + attributeName;

        // Set title
        $(this).find('.panel-title span')
            .text(i18n.t('extension_attributes.widget_header') +" — "+attributeName)
            .after('<a href="'+url+'" class="btn btn-xs pull-right"><i class="fa fa-list"></i></a>');

        // Add to attribute list
        attributes.push({
            name: attributeName,
            list: $(this).find('div.list-group')
        });
    });

    $(document).on('appUpdate', function(){

        // Get data for attributes
        $.each(attributes, function(i, attribute){
            $.getJSON(baseUrl + attribute.name, function(data){

                // Empty old list
                attribute.list.empty();

                // Make a list item for every datapoint (d)
                // Sort on result (prefix with x to disable float comparison)
                data.sort(function(a,b){
                    return mr.naturalSort('x'+b.result, 'x'+a.result);
                });

                $.each(data, function(j, d){
                    attribute.list.append($('<a>')
                        .addClass('list-group-item')
                        .attr('href', appUrl + '/show/listing/extension_attributes/extension_attributes/#' + attribute.name)
                        .text(d.result)
                        .append($('<span>')
                            .addClass('badge pull-right')
                            .text(d.count)));
                });
            });
        });
    }); // end appUpdate
});
</script>
