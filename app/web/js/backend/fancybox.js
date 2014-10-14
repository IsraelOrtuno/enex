// JavaScript Documents
/*
 * This JavaScript perform calling 'Fancybox' effect by using '<a></a>' tag, 'id=formbox'.
 */

$(document).ready(function() {
    $("a#formbox").fancybox({
        'titlePosition' : 'none',
        'overlayColor'  : '#000',
        'overlayOpacity': 0.7
    });
});

$(document).ready(function() {
    $("a#picturebox").fancybox({
        'titlePosition' : 'none',
        'overlayColor'  : '#000',
        'overlayOpacity': 0.9,
        'type'          : 'image'
    });
});