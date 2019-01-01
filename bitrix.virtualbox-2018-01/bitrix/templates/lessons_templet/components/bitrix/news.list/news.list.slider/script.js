(function(window){
    if (window.JSCarouselElement)
        return;

    window.JSCarouselElement = function(arParams) {
        this.id = arParams.id;
        this.carouselInner = $('#'+ this.id + ' .carousel-inner')[0];
        this.videoList = arParams.video;
        this.player = null;
        this.init();
        // console.log(this);
    };

    window.JSCarouselElement.prototype = {
        init: function () {
            var self = this;

            $(this.carouselInner).width( $(window).width() );
            this.innerWidthHeight( $(window).width(), $(window).height() );
            this.imgResize();

            $(window).resize(function() {
                var w = $(window).width(),
                    h = $(window).height();
                self.innerWidthHeight(w, h);
                self.imgResize();
            });

            this.loadVideo(this.videoList);

            $('#'+this.id).on('slide.bs.carousel', function (e) {
                console.log(e);
                if (e.relatedTarget.childNodes[1].id){
                    console.log( e.relatedTarget.childNodes[1] );
                }
                else {
                }
            });

            $('#'+this.id).carousel({
                interval: 1000
            });
            $('#cycle1').click(function () {
                $('#'+this.id).carousel('cycle')
            });
            $('#pause1').click(function () {
                $('#'+this.id).carousel('pause')
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
            var playerYT = Array();

            // 2. This code loads the IFrame Player API code asynchronously.
            var tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

            window.onYouTubeIframeAPIReady = function () {
                for (var i in videoList) {
                    playerYT[i] = new YT.Player(videoList[i].id, {
                        height: '100%',
                        width: '100%',
                        videoId: videoList[i].videoId,
                        events: {
                            'onReady': onPlayerReady,
                            'onStateChange': onPlayerStateChange
                        },
                        playerVars: {
                            //'autoplay': 1,
                            'controls': 0,
                            'autohide': 0,
                            'showinfo' : 0,
                            'wmode': 'opaque',
                            'rel': 0,
                            //'loop': 1
                            'fs' : 0
                        }
                    });
                }
            };

            function onPlayerStateChange(event) {
                if (event.data === YT.PlayerState.ENDED) {
                    event.target.playVideo();
                }
            }

            window.onPlayerReady = function(event) {
                event.target.mute();
                event.target.playVideo();
            };
            this.player = playerYT;
        }


    };
})(window);








