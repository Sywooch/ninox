$('img.svg').each(function(){
    var img = jQuery(this),
        imgID = img.attr('id'),
        imgClass = img.attr('class'),
        imgURL = img.attr('src');

    $.get(imgURL, function(data) {
        // Get the SVG tag, ignore the rest
        var svg = jQuery(data).find('svg'),
            width = '40',
            height = '35';

        // Add replaced image's ID to the new SVG
        if(typeof imgID !== 'undefined') {
            svg = svg.attr('id', imgID);
        }
        // Add replaced image's classes to the new SVG
        if(typeof imgClass !== 'undefined') {
            svg = svg.attr('class', imgClass+' replaced-svg');
        }

        // Remove any invalid XML tags as per http://validator.w3.org
        svg = svg.removeAttr('xmlns:a');

        svg.attr('width', width);
        svg.attr('height', height);

        svg.attr('preserveAspectRatio', 'xMinYMin meet');//preserveAspectRatio="xMinYMin meet"
        svg.attr('viewBox', '0 0 ' + (width * 40) + ' ' + (height * 10));


        // Replace image with new SVG
        img.replaceWith(svg);

    }, 'xml');
});
