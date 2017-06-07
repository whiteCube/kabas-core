/*
 |--------------------------------------------------------------------------
 | Capricorn front file
 |--------------------------------------------------------------------------
 |
 */

/**
 * Imports
 */
import { Home , Contact, HSlider, LSlider, Quotes, Gallery, Video } from './imports';

// Config
const CONFIG = {
    component : 'data-component',
    page      : 'data-page'
};

// Pages
const PAGES = {
    home : Home
};

// Components
const COMPONENTS = {
    hSlider : HSlider,
    quotes  : Quotes,
    gallery : Gallery,
    video   : Video
};

/**
 * Main class
 */
class W {

    constructor() {
        this.body = document.getElementsByTagName('body')[0];
    }

    run() {
        this.setupVideo();
        this.initCurrentPage();
        this.initComponents();
        this.videoScript();
    }

    /**
     * Initialise components
     */
    initComponents() {
        this.components = [];
        [].forEach.call(document.querySelectorAll('[' + CONFIG.component + ']'), (el) => {
            let instance;
            if (!el.hasAttribute(CONFIG.component)) return;
            let id = el.getAttribute(CONFIG.component);
            if (typeof COMPONENTS[id] == 'undefined') return;
            instance = new COMPONENTS[id](el);
            this.components.push(instance);
        });
    }

    /**
     * Initialise the current page
     */
    initCurrentPage() {
        if (!this.body.hasAttribute(CONFIG.page)) return;
        let id = this.body.getAttribute(CONFIG.page);
        if (typeof PAGES[id] == 'undefined') return;
        this.currentPage = new PAGES[id];   
    }

    /*
        Video
     */

    setupVideo() {
        this.videos = [];
    }

    registerOneVideo(video) {
        this.videos.push(video);
    }

    launchVideos()
    {
        this.videos.forEach(function(video) {
            video.createPlayer();
        });
    }

    videoScript(){
        if (this.videos.length >= 1) {
            var tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            this.scriptWrited = true;
        }
    }
}

window.onYouTubeIframeAPIReady = function() {
     window.w.launchVideos();
}


window.addEventListener("load", function(event){
      window.w = new W();
      window.w.run();
}, false);