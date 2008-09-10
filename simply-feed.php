<?php

    /*
    * Plugin Name: Simply Feed
    * Plugin URI:  http://wordpress.org/extend/plugins/simply-feed
    * Version:     0.1
    * Description: This plugins simplifies embedding of RSS feeds within templates.
    * Author:      Kevin Herrera
    * Author URI:  http://codealchemy.com/
    */

    // Load feed parser.
    include_once( ABSPATH . WPINC
                          . '/rss.php' );

    /**
    * Simply Feed
    *
    * This function uses WordPress's fetch_rss() function to fetch the feed,
    * $uri, and boil it down to a simple array.  Some formatting data is lost
    * to allow the current template to properly stylize the items.  By
    * default, the array of all items fetched are returned.  Optionally, you
    * may have the function render the items for you by setting $render to
    * true.  You may also specify the limit by providing the $limit argument.
    * The rendered output are list items without the <ul> parent element.
    *
    * @param  string  $uri    Feed URI.
    * @param  boolean $render Render items.
    * @param  integer $limit  Item rendering limit.
    * @return array
    */
    function simply_feed ( $uri,
                           $render = false,
                           $limit  = 10 )
    {
        // Fetch feed.
        $feed = fetch_rss( $uri );

        // Create items array.
        $items = array( );

        // Have feed?
        if ( $feed->items )
        {
            // Loop through feed items.
            foreach ( $feed->items as $item )
            {
                // Add array entry.
                $items []= array(
                    'title'         => $item['title'],
                    'description'   => preg_replace( '/\s+/',
                                                     ' ',
                                                     strip_tags( $item['description'],
                                                                 '<a>' ) ),
                    'link'          => $item['link']
                );
            }
        }

        // Render items?
        if ( $render )
        {
            // Create counter.
            $count = 0;

            // Loop through items.
            foreach ( $items as $item )
            {
                // Stop rendering?
                if ( $count++ >= $limit )
                    break;

                // Print list item.
                print( '<li><a href="'
                     . $item['link']
                     . '" title="'
                     . $item['title']
                     . '" target="_blank">'
                     . $item['description']
                     . '</a></li>' );
            }
        }

        // Return items.
        return( $items );
    }

?>
