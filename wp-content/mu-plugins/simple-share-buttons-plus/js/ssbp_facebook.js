(function( $ ) {
    var urls = [];
    $( '.ssbp-total-facebook-shares' ).each(function( index, ele ) {
        var url = $( ele ).data( 'url' );
        if ( url && -1 === $.inArray( url, urls ) ) {
            urls.push( url );
        }
    });
    $.each( urls, function( index, url ) {
        $.getJSON( 'https://graph.facebook.com/' + encodeURIComponent( url ) )
        .then(function( resp ) {
            if ( resp.share && resp.share.share_count ) {
                $( '.ssbp-total-facebook-shares[data-url="' + url + '"]' )
                .text( formatShares( resp.share.share_count ) );
            }
        });
    });

    function formatShares( num ) {
        if ( num >= 1000 ) {
            num = Math.round( num / 1000 ).toLocaleString() + 'k';
        } else {
            num = num.toLocaleString();
        }
        return num;
    }
})( jQuery );
