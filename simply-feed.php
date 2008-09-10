<?php

    /*
    * Plugin Name: Simply Feed
    * Plugin URI:  http://wordpress.org/extend/plugins/simply-feed
    * Version:     0.2
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
    * This method pulls together the simply_feed_get() and simply_feed_render()
    * functions to do the job.  I am uncertain on how WordPress handles URIs
    * but I'll assume it can take a file path or a URL.  When a file path is
    * used, it will never cache the feed.  If a URL is used, the feed at the
    * URL will be cached.  The cache is refreshed as often as it expires.  The
    * expiration argument is how minutes until the next refresh.  By default,
    * it's every 15 minutes.  Also by default, only the first 10 items will
    * be rendered.
    *
    * @param  string  $uri    Feed URI.
    * @param  boolean $render Render items.
    * @param  integer $limit  Item rendering limit.
    * @param  integer $expire Cache expiration time (in minutes).
    * @return array
    */
    function simply_feed ( $uri,
                           $render = false,
                           $limit  = 10,
                           $expire = 15 )
    {
        // Is it a URL?
        if ( substr( $uri, 0, 4 ) == "http" )
        {
            // Encode URL.
            $url = urlencode( $uri );

            // Create path.
            $path = WP_PLUGIN_DIR . '/simply-feed/cache/' . $url;

            // File exists?
            if ( file_exists( $path ) )
            {
                // Get timestamp.
                $modified = filemtime( $path );

                // Mulity expire.
                $expire *= 60;

                // Get modified time difference.
                $modified = time( ) - $modified;

                // Time to refresh?
                if ( $modified >= $expire )
                {
                    // Update feed.
                    $items = simply_feed_get( $uri );

                    // Update cache.
                    file_put_contents( $path, serialize( $items ) );
                }

                // Do not refresh?
                else
                {
                    // Get cached feed.
                    $items = unserialize( file_get_contents( $path ) );
                }
            }

            // File does not exist?
            else
            {
                // Fetch feed.
                $items = simply_feed_get( $uri );

                // Cache feed.
                file_put_contents( $path, serialize( $items ) );
            }
        }

        // Not a URL?
        else
        {
            // Get the feed.
            $items = simply_feed_get( $uri );
        }

        // Should we render the feed?
        if ( $render )
        {
            // Render it.
            simply_feed_render( $items, $limit );
        }

        // Return items.
        return( $items );
    }

    /**
    * Simply Feed (Fetch)
    *
    * This function uses WordPress's fetch_rss() function to fetch the feed,
    * $uri, and boil it down to a simple array.  Some formatting data is lost
    * to allow the current template to properly stylize the items.  By
    * default, the array of all items fetched are returned.
    *
    * @param  string  $uri Feed URI.
    * @return array
    */
    function simply_feed_get ( $uri )
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

        // Return items.
        return( $items );
    }

    /**
    * Simply Feed (Renderer)
    *
    * This function renders each item in the $items array as a list item <li>.
    * To use this within your template, please encapsulate this output with
    * your own <ul> element.
    *
    * @param  array   $items Feed items.
    * @param  integer $limit Rendering limit.
    * @return void
    */
    function simply_feed_render ( $items, $limit )
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

?>
