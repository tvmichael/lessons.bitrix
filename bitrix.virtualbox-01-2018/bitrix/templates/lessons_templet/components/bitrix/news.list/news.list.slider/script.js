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
            this.carouselInnerWidthHeight( $(window).width(), $(window).height() );
            this.carouselInnerImg();

            $(window).resize(function() {
                var w = $(window).width(),
                    h = $(window).height();
                self.carouselInnerWidthHeight(w, h);

                self.carouselInnerImg();
            });
        },

        carouselInnerWidthHeight: function (wW, wH) {
            $(this.carouselInner).width(wW);
        },

        carouselInnerImg: function () {
            var cH = $(this.carouselInner).height(),
                cW = $(this.carouselInner).width();

            var activeImg = $('.active img', this.carouselInner)[0],
                iNaturalW = activeImg.naturalWidth,
                iNaturalH = activeImg.naturalHeight;
                //iW = $(activeImg).width(),
                //iH = $(activeImg).height();

            var ratioH, ratioW = 0;

            ratioW = cW / iNaturalW;
            iW = cW;
            iH = Math.round(iNaturalH * ratioW);

            if(cH > iH){
                ratioH = cH / iH;
                iW = Math.round(iW * ratioH);
                iH = Math.round(iH * ratioH);
            }
            $(activeImg).css("max-width",iW);
            $(activeImg).width(iW);
            $(activeImg).height(iH);

        }



    };
})(window);
