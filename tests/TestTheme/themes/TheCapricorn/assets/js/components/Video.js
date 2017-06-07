class Video {

    constructor(el) {
        this.setConf();
        this.init(el);
        this.setEvents(el);

        this.register();
    }


    setConf() {
        this.conf = {
            codeParameter : 'data-video-id',
            player: 'video__player',
            overlay: 'videoOverlay',
            overlayLink: 'videoOverlay__link',
            modifierActive: 'video--active',
            modifierReady: 'video--ready',
            modifierLoading: 'video--loading'

        }
    }

    init(el) {
        this.eSection = el;
        this.code = this.eSection.getAttribute(this.conf.codeParameter);
        this.ePlayer = this.eSection.querySelector( '.' + this.conf.player);
        this.eOverlay = this.eSection.querySelector( '.' + this.conf.overlay);
        this.eOverlayLink = this.eSection.querySelector( '.' + this.conf.overlayLink);

        this.isReady = false;
        this.isAuto = false;

    }

    setEvents() {
        this.eOverlayLink.addEventListener(
            "click", 
            (function(self) { return function(event) { self.e_clickOverlay(event) }; })(this)
            , false
            ); 
    }

    // Events
    e_clickOverlay(e) {
        e.preventDefault();
        if (this.isReady) {
            this.player.playVideo();
        }else{
            this.isAuto = true;
            this.eSection.classList.add(this.conf.modifierLoading);
        }
    }

    e_ready(e) {
        this.isReady = true;
        if (this.isAuto) this.player.playVideo();

    }

    e_playerStateChange(e) {
        if (e.data == YT.PlayerState.PLAYING) {
            this.eSection.classList.add(this.conf.modifierActive);
            this.eSection.classList.remove(this.conf.modifierLoading);
        }
        else if (e.data == YT.PlayerState.ENDED || e.data == YT.PlayerState.PAUSED) {
            this.eSection.classList.remove(this.conf.modifierActive);
        }
    }

    // Functions


    removeOverlay() {
        var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        if (iOS) this.eOverlay.classList.add('hidden');
    }

    createPlayer() {
        this.player = new YT.Player(this.ePlayer, {
            width: '100%',
            videoId: this.code,
            events: {
                'onReady': (function(self) { return function(event) { self.e_ready(event) }; })(this),
                'onStateChange': (function(self) { return function(event) { self.e_playerStateChange(event) }; })(this),
            }
        });

        this.eSection.classList.remove(this.conf.modifierLoading);
    }

    register() {
        window.w.registerOneVideo(this);
    }
}

export { Video as default }
