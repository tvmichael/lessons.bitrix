(function(window){
    if (window.JSCarouselElement)
        return;
    console.log('--- JSCarouselElement START!:');

    window.JSCarouselElement = function(arParams) {
        console.log('--- JSCarouselElement CRETE!');
        this.id = arParams.id;
        this.carouselInner = $('#'+ this.id + ' .carousel-inner')[0];
        this.videoList = arParams.video;

        this.init();

        // console.log(this);
    };

    window.JSCarouselElement.prototype = {
        init: function () {
            console.log('--- JSCarouselElement > init');
            var self = this;

            $(this.carouselInner).width( $(window).width() );
            this.innerWidthHeight( $(window).width(), $(window).height() );
            this.imgResize();

            $(window).resize(function() {
                console.log('--- JSCarouselElement > resize');
                var w = $(window).width(),
                    h = $(window).height();
                self.innerWidthHeight(w, h);
                self.imgResize();
            });

            this.loadVideo(this.videoList);

            $('.carousel').carousel({
                // interval: 10000,
            });
            $('.carousel').on('slide.bs.carousel', function (e) {
                //console.log(e);
                //self.imgResize();
            });
        },

        innerWidthHeight: function (wW, wH) {
            $(this.carouselInner).width(wW);

            if( wW < 800 || wH < 400)
                $(this.carouselInner).height(240);
            else $(this.carouselInner).height(469);
        },

        imgResize: function () {
            var cH = $(this.carouselInner).height(),
                cW = $(this.carouselInner).width();

            var activeImg,
                iNaturalW,
                iNaturalH,
                iW, iH,
                ratio,
                top, left;

            var img = $('img', this.carouselInner),
                n = img.length,
                i;

            for(i = 0; i < n;  i++){
                activeImg = img[i]; // $('.active img', this.carouselInner)[0],
                iNaturalW = activeImg.naturalWidth;
                iNaturalH = activeImg.naturalHeight;

                // scaled image
                ratio = cW / iNaturalW;
                iW = Math.round(cW);
                iH = Math.round(iNaturalH * ratio);

                if(cH > iH){
                    ratio = cH / iH;
                    iW = Math.round(iW * ratio);
                    iH = Math.round(iH * ratio);
                }
                $(activeImg).css("max-width",iW);
                $(activeImg).width(iW);
                $(activeImg).height(iH);

                // center image
                left = Math.round( (cW - iW) / 2);
                top = Math.round( (cH - iH) / 2);
                $(activeImg).css('left', left);
                $(activeImg).css('top', top);
            }
        },

        loadVideo: function (videoList) {
            console.log('--- JSCarouselElement > loadvideo');

            var i,
                currentVideo,
                containerVideo;

            /*
            // console.log(videoList);
            for( i in videoList ) {
                currentVideo = videoList[i];
                console.log('- ' + currentVideo.name);

            } /**/

                    console.log('--->> onYouTubeIframeAPIReady 0');
                    var player111;
                    window.onYouTubeIframeAPIReady = function () {
                        console.log('--->> onYouTubeIframeAPIReady 1');
                        player111 = new YT.Player('xxx777', {
                            height: '100%',
                            width: '100%',
                            videoId: videoList[0].videoId,
                            events: {
                                'onReady': onPlayerReady
                            }
                        });
                    }
                    window.onPlayerReady = function(event) {
                        console.log('--->> onYouTubeIframeAPIReady 2');
                        event.target.playVideo();
                    }





                /**/






            /*
            // 2. This code loads the IFrame Player API code asynchronously.
            var tag = document.createElement('script');

            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

            // 3. This function creates an <iframe> (and YouTube player)
            //    after the API code downloads.
            var player;
            function onYouTubeIframeAPIReady() {
                player = new YT.Player('player', {
                    height: '360',
                    width: '640',
                    videoId: 'M7lc1UVf-VE',
                    events: {
                        'onReady': onPlayerReady,
                        'onStateChange': onPlayerStateChange
                    }
                });
            }

            // 4. The API will call this function when the video player is ready.
            function onPlayerReady(event) {
                event.target.playVideo();
            }

            // 5. The API calls this function when the player's state changes.
            //    The function indicates that when playing a video (state=1),
            //    the player should play for six seconds and then stop.
            var done = false;
            function onPlayerStateChange(event) {
                if (event.data == YT.PlayerState.PLAYING && !done) {
                    setTimeout(stopVideo, 6000);
                    done = true;
                }
            }
            function stopVideo() {
                player.stopVideo();
            }
            /**/
        }


    };

    console.log('--- JSCarouselElement > END!');
})(window);








