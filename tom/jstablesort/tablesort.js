    jQuery( document ).ready( function () {
    
    jQuery( 'body' ).on( 'click', '.js_sort_toggle', function(e) {

        let sort_term = jQuery(this).data('sort_term'); // last_updated, age, count, status as a data attr on the tr element
        let sort_order = jQuery(this).data('sort_order'); // ASC, DESC


        jQuery(this).parents('thead').find('span.material-symbols-outlined').html('unfold_more');//material icon to show up/down arrows
        jQuery('.js_sort_toggle').data('sort_order', 'DESC');

        if(sort_order == 'ASC'){
            jQuery(this).data('sort_order', 'DESC');
            jQuery(this).find('span.material-symbols-outlined').html('expand_more');//material icon to show up/down arrows
        }else if(sort_order == 'DESC'){
            jQuery(this).data('sort_order', 'ASC');
            jQuery(this).find('span.material-symbols-outlined').html('expand_less');//material icon to show up/down arrows
        }
            
        let theTableElement =  jQuery(this).parents('.layout-table');
        let theTabelBody =  theTableElement.find('tbody');

        let items = theTableElement.find('tbody tr');

        items.sort(function (a, b) {
            if(sort_order == 'ASC'){
                return +a.dataset[sort_term] - +b.dataset[sort_term];
            } else if(sort_order == 'DESC'){
                return +b.dataset[sort_term] - +a.dataset[sort_term];
            } 
        }).appendTo( theTabelBody );

    });

} );

/**
    <table id="table_locations" class="layout-table">
        <thead>
            <tr>
                <td>Location</td>
                <td class="js_sort_toggle" data-sort_order="DESC" data-sort_term="last_updated" >Last Updated <span class="material-symbols-outlined" >unfold_more</span></td>
                <td class="js_sort_toggle" data-sort_order="DESC" data-sort_term="status" >Status <span class="material-symbols-outlined" >unfold_more</span></td>
                <td>Users</td>
                <td>Manage Location</td>
            </tr>
        </thead>
        <tbody>
            <tr data-last_updated="<?= get_the_modified_time('U', $post->ID) ?>" data-status="<?= $status_int ?>" >
                <td>Some Text</td>
                <td>10-25-1995</td>
                <td>Open</td>
                <td>
                    <a href="#">View Users</a>
                </td>
                <td>
                    <a href="#">Edit</a>
                </td>
            </tr>
            <tr data-last_updated="<?= get_the_modified_time('U', $post->ID) ?>" data-status="<?= $status_int ?>" >
                <td>Some Text</td>
                <td>06-20-1995</td>
                <td>Open</td>
                <td>
                    <a href="#">View Users</a>
                </td>
                <td>
                    <a href="#">Edit</a>
                </td>
            </tr>
        </tbody>
 */