// rss-ticker.js

jQuery(document).ready(function($) {
    // Pause ticker animation on hover
    $('.rss-ticker-container').hover(function() {
        $(this).find('.rss-ticker').css('animation-play-state', 'paused');
    }, function() {
        $(this).find('.rss-ticker').css('animation-play-state', 'running');
    });

    // Function to start ticker animation
    function startTickerAnimation() {
        var tickerWidth = $('.rss-ticker').width(); // Get width of ticker content
        var tickerSpeed = tickerWidth / 120; // Adjust as needed for speed

        // Set animation duration based on ticker content width
        $('.rss-ticker').css('animation-duration', tickerSpeed + 's');
    }

    // Start ticker animation when document is ready
    startTickerAnimation();
});
