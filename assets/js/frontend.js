jQuery(document).ready(function(){
    
    /**
     * Every time the BB settings form is initialized, attach the SoulGrid dropdown listener
     **/
    FLBuilder.addHook('settings-form-init', setupSoulGridSelectListener);

    /**
     * The SoulGrid select listener listens for any changes that occur in the
     * SoulGrid Columns & Gutters selector inside the BB Settings form.
     **/
    function setupSoulGridSelectListener(event){
        setTimeout(function(){
            jQuery( '.fl-builder-settings:visible' ).find( 'select[name="soul_grid_column_gutter_dropdown"]' ).on( 'change', null, {'screenSize' : 'full'}, updateSoulGridClass);
            jQuery( '.fl-builder-settings:visible' ).find( 'select[name="soul_grid_column_gutter_dropdown_medium"]' ).on( 'change', null, {'screenSize' : 'medium'}, updateSoulGridClass);
            jQuery( '.fl-builder-settings:visible' ).find( 'select[name="soul_grid_column_gutter_dropdown_responsive"]' ).on( 'change', null, {'screenSize' : 'responsive'}, updateSoulGridClass);
        }, 10);
    }

    /**
     * Applies the appropriate SoulGrid column and gutter class to the current BB item based on
     * what the user has selected in the SoulGrid Columns & Gutters dropdown in the BB settings form
     **/
    function updateSoulGridClass(event){

        // exit if any critical data is missing
        if(!event || !event.target || !event.data || !event.data.screenSize){
            return;
        }

        // get the node that's currently being edited
        var formNodeId = jQuery( '.fl-builder-settings[data-node]' ).attr( 'data-node' );

        // get the currently selected SoulGrid col & gutter option
        var selectedOption = jQuery(event.target).val();

        // create a list of SoulGrid classes to remove based on the screensize the user has selected
        var classList = '';
        if(event.data.screenSize === 'full'){
            classList = 'sg-1c-0g sg-1c-1g sg-2c-1g sg-2c-2g sg-3c-2g sg-3c-3g \
                         sg-4c-3g sg-4c-4g sg-5c-4g sg-5c-5g sg-6c-5g sg-6c-6g \
                         sg-7c-6g sg-7c-7g sg-8c-7g sg-8c-8g sg-9c-8g sg-9c-9g \
                         sg-10c-9g sg-10c-10g sg-11c-10g sg-11c-11g sg-12c-11g sg-12c-12g'; 
        }else if(event.data.screenSize === 'medium'){
            classList = 'sg-1c-0g_medium sg-1c-1g_medium sg-2c-1g_medium sg-2c-2g_medium sg-3c-2g_medium sg-3c-3g_medium \
                         sg-4c-3g_medium sg-4c-4g_medium sg-5c-4g_medium sg-5c-5g_medium sg-6c-5g_medium sg-6c-6g_medium \
                         sg-7c-6g_medium sg-7c-7g_medium sg-8c-7g_medium sg-8c-8g_medium sg-9c-8g_medium sg-9c-9g_medium \
                         sg-10c-9g_medium sg-10c-10g_medium sg-11c-10g_medium sg-11c-11g_medium sg-12c-11g_medium sg-12c-12g_medium'; 

            // if the user's screensize is medium, add the _medium suffix to the class
            selectedOption += '_medium';
        }else if(event.data.screenSize === 'responsive'){
            classList = 'sg-1c-0g_responsive sg-1c-1g_responsive sg-2c-1g_responsive sg-2c-2g_responsive sg-3c-2g_responsive sg-3c-3g_responsive \
                         sg-4c-3g_responsive sg-4c-4g_responsive sg-5c-4g_responsive sg-5c-5g_responsive sg-6c-5g_responsive sg-6c-6g_responsive \
                         sg-7c-6g_responsive sg-7c-7g_responsive sg-8c-7g_responsive sg-8c-8g_responsive sg-9c-8g_responsive sg-9c-9g_responsive \
                         sg-10c-9g_responsive sg-10c-10g_responsive sg-11c-10g_responsive sg-11c-11g_responsive sg-12c-11g_responsive sg-12c-12g_responsive';

            // if the user's screensize is responsive, add the _responsive suffix to the class
            selectedOption += '_responsive';
        }

        // remove the existing SoulGrid col & gutter class from the current node and add the one that the user has just selected
        jQuery('.fl-node-' + formNodeId).removeClass(classList).addClass(selectedOption);
    }
});
