class Gallery {

      constructor(el) {
            this.setConf();
            this.init();
            this.getGal(el);
            this.setEvents();
      }

    setConf() {
        this.conf = {
            items: 'galleryItem__link',

            lightbox: 'lightbox',
            lightboxHidden : 'lightbox--hidden',
            container: 'lightbox__container',
            figure: 'lightbox__figure',
            img: 'lightbox__image',
            prev: 'lightbox__prev',
            next: 'lightbox__next',
            close: 'lightbox__close',
            
            transition: 300
        }
    }

    init() {
        this.isOpen = false;
        this.currentKey = null;    
    }

    getGal(el) {
        this.eGallery = el;
        this.items = [];

        Array.from(this.eGallery.getElementsByClassName('galleryItem__link')).forEach((el, index) => {
            var o = {};
            o.el = el;
            o.key = index;
            o.target = el.getAttribute('href');
            o.alt = o.el.parentElement.querySelector('img').getAttribute('alt');
            this.items.push(o);
        });
    }

    setEvents() {
        this.items.forEach((item) => {
            item.el.addEventListener("click", 
                (function(self) { return function(event) { self.e_click(event, item); }; })(this)
            );
        });
    }

    // EVENTS
    e_click(e, item) {
        e.preventDefault();
        this.createLightBox(item);
    }

    e_close(e) {
        e.preventDefault();
        if (e.target.classList.contains(this.conf.lightbox) || e.target.classList.contains(this.conf.close)){
            this.closeLightBox();
        } 
    }

    e_prev(e) {
        e.preventDefault();
        this.changeImage(this.items[this.getPrevKey()]);
    }

    e_next(e) {
        e.preventDefault();
        this.changeImage(this.items[this.getNextKey()]);
    }


    // FUNCTIONS
    createLightBox(item) {

        if (this.isOpen) {
            this.changeImage(item); return;
        }
        this.currentKey= item.key;

        this.lightbox = document.createElement("div");
        this.lightbox.classList.add(this.conf.lightbox);
        this.lightbox.addEventListener("click", 
            (function(self) { return function(event) { self.e_close(event); }; })(this)
        );


        var container = document.createElement("div");
            container.classList.add(this.conf.container);

        var figure = document.createElement("figure");
            figure.classList.add(this.conf.figure);
        
        var close = document.createElement("a");
            close.classList.add(this.conf.close);
            close.setAttribute("href", '#close');
            close.innerHTML = "Retournez à la page";

        var img = document.createElement("img");
            img.classList.add(this.conf.img);
            img.setAttribute("src", item.target);
            img.setAttribute("alt", item.alt);


        var prev = document.createElement("a");
            prev.classList.add(this.conf.prev);
            prev.setAttribute("href", this.items[this.getPrevKey()].target);
            prev.innerHTML = "Voir la photo précédente";
            prev.addEventListener("click", 
                (function(self) { return function(event) { self.e_prev(event); }; })(this)
            );

        var next = document.createElement("a");
            next.classList.add(this.conf.next);
            next.setAttribute("href", this.items[this.getNextKey()].target);
            next.innerHTML = "Voir la photo suivante";
            next.addEventListener("click",
                (function(self) { return function(event) { self.e_next(event); }; })(this)
            );

        figure.appendChild(img);
        container.appendChild(prev);
        container.appendChild(next);
        container.appendChild(figure);
        container.appendChild(close);

        container.appendChild(close);
        this.lightbox.classList.add(this.conf.lightboxHidden);
        this.lightbox.appendChild(container);
        document.body.appendChild(this.lightbox);

        setTimeout(
            () => { 
                this.lightbox.classList.remove(this.conf.lightboxHidden);
                this.isOpen = true;
            },
            50
        );

    }

    closeLightBox() {
        this.lightbox.classList.add(this.conf.lightboxHidden);
        setTimeout(
            () => { 
                this.lightbox.parentElement.removeChild(this.lightbox); 
                this.isOpen = false;
            },
            this.conf.transition
        );
    }

    changeImage(item) {
        this.currentKey= item.key;

        var img = this.lightbox.querySelector('.' + this.conf.img);
            img.setAttribute("src", item.target);
            img.setAttribute("alt", item.alt);

        this.lightbox.querySelector('.' + this.conf.prev).setAttribute("href", this.items[this.getPrevKey()].target);
        this.lightbox.querySelector('.' + this.conf.next).setAttribute("href", this.items[this.getNextKey()].target);
    }

    getNextKey(){
        if (this.items.length-1 < this.currentKey +1) return 0;
        return this.currentKey + 1;
    }

    getPrevKey(){
        if ( this.currentKey - 1 < 0) return this.items.length-1;
        return this.currentKey - 1;
    }
}

export { Gallery as default }
