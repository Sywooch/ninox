$('img.svg').each(function(){
    var img = $(this),
        imgID = img.attr('id'),
        imgClass = img.attr('class'),
        imgURL = img.attr('src');

    $("<img/>") // Make in memory copy of image to avoid css issues
        .attr("src", $(img).attr("src"))
        .load(function(){
            var real_width = this.width,
                real_height = this.height;

            $.get(imgURL, function(data){
                // Get the SVG tag, ignore the rest
                var svg = $(data).find('svg'),
                    width = '40',
                    height = '35';

                // Add replaced image's ID to the new SVG
                if(typeof imgID !== 'undefined') {
                    svg = svg.attr('id', imgID);
                }
                // Add replaced image's classes to the new SVG
                if(typeof imgClass !== 'undefined') {
                    svg = svg.attr('class', imgClass + ' replaced-svg');
                }

                // Remove any invalid XML tags as per http://validator.w3.org
                svg = svg.removeAttr('xmlns:a');

                svg.attr('width', width);
                svg.attr('height', height);

                svg.attr('preserveAspectRatio', 'alignment [meet | slice]');
                svg.attr('viewBox', '0 0 ' + real_width + ' ' + real_height);

                // Replace image with new SVG
                img.replaceWith(svg);

            }, 'xml');
        });
});