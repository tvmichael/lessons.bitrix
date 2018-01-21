(function(window){
    if (window.JSCarouselElement)
        return;

    window.JSCarouselElement = function(arParams) {
        console.log('JSCarouselElement CREATE!');
        this.id = arParams.id;
        this.carouselInner = $('#'+ this.id + ' .carousel-inner')[0];

        this.init();
        console.log(this);
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

            $('.carousel').carousel({
                // interval: 10000,
            });

            $('.carousel').on('slide.bs.carousel', function (e) {
                //console.log(e);
                //self.imgResize();
            });

            $('.carousel').on('slid.bs.carousel', function (e) {
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
        }



    };
})(window);
