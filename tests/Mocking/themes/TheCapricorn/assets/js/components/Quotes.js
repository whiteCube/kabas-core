class Quotes {

      constructor(el) {
            this.setConf();
            this.init();
            this.getGal(el);
            this.setMinHeights();
            this.setCustomItem(this.currentItem);
            this.setEvents();
            this.activeGal();
      }

    setConf() {
        this.conf = {
            modifierActive: 'quotes--active',
            modifierAnim: 'quotes--anim',
            list: 'quotes__list',

            item: 'quote',
            itemLeft: 'quote--left',
            itemRight: 'quote--right',
            itemActive: 'quote--active',
            itemHidden: 'quote--hidden',
            itemAnim: 'quote--anim',

            pager: 'quotesPagers__link',
            pagerActive: 'quotesPagers__link--active',


            firstItem: 0,
            transitionDuration: 400
        }
    }

    init() {
        var currentClass = this;
        this.currentItem = this.conf.firstItem;
        this.duration = this.conf.transitionDuration;
        this.isAnimating = false;
        this.isActive = false;
        this.direction = null;
    }

    setEvents() {
        $(document).on('keyup', this.e_keyPress.bind(this));


        this.aItems.forEach((item) => {
            item.$pagers.on('click',
                (function(self) { return function(event) { self.e_clickPager(event, item); }; })(this)
            );
        });
    }



    // FUNCTIONS

    setMinHeights() {
        this.minHeight = 0;

        this.aItems.forEach((item) => {
            item.iHeight = item.$item.outerHeight();
            this.minHeight = item.iHeight > this.minHeight ? item.iHeight : this.minHeight;
        });
        this.$list.css('height', this.minHeight);

    }

    activeGal() {
        this.$gal.addClass(this.conf.modifierActive);
        this.$gal.addClass(this.conf.modifierAnim);

        this.isActive = true;
    }


    //GET GAL


    getGal(el) {
        this.$gal = $(el).first();
        this.$list = this.$gal.find('.' + this.conf.list);

        this.aItems = [];
        this.$gal.find('.' + this.conf.item).each((index, element) => {
            this.aItems.push(this.getItem(index, element));
        });
    }

    getItem(key, el) {
        var o = {};
        o.key = key;
        o.$item = $(el).first();
        o.id = o.$item.attr('id');
        o.$pagers = $('a[href="#' + o.id + '"]')
        return o;
    }


    //SET ITEM
    setCustomItem(key, direction = false) {
        this.direction = direction ? direction : this.getDirection(key);
        this.isAnimating = true;
        this.$gal.removeClass(this.conf.modifierAnim);
        this.prepareItem(key);

        //this.prepareControlButton(key);

        setTimeout(() => {
            this.$gal.addClass(this.conf.modifierAnim);
            this.moveItem(key);
            this.currentItem = key;
            setTimeout(() => {
                this.isAnimating = false;
            }, this.duration);
        }, 50);
    }

    setPreviousItem() {
        this.setCustomItem(this.getPreviousKey(), 'left');
    }

    setNextItem() {
        this.setCustomItem(this.getNextKey(), 'right');
    }

    prepareItem(key) {
        this.aItems.forEach((item) => {
            if (item.key === this.currentItem) {
                this.setActive(item);
            } else if (item.key === key && this.direction == 'left') { this.setLeft(item); } else if (item.key === key && this.direction == 'right') { this.setRight(item); } else { this.setHidden(item); }
        });
    }

    moveItem(key) {
        this.aItems.forEach((item) => {
            if (item.key === key) {
                this.setActive(item);
                this.setPagerActive(item);
            } else {
                this.unsetPagerActive(item);

                if (this.currentItem === item.key && this.direction == 'left') { this.setRight(item); } else if (this.currentItem === item.key && this.direction == 'right') { this.setLeft(item); } else { this.setHidden(item); }
            }
        });
    }

    // SET ITEM
    setActive(o) {
        if (!o.isActive) {
            this.removeAllClass(o);
            o.$item.addClass(this.conf.itemActive);
            o.isHidden = false;
            o.isActive = true;
            o.isLeft = false;
            o.isRight = false;
        }
    }

    setLeft(o) {
        if (!o.isLeft) {
            this.removeAllClass(o);
            o.$item.addClass(this.conf.itemLeft);
            o.isHidden = false;
            o.isActive = false;
            o.isLeft = true;
            o.isRight = false;
        }
    }

    setRight(o) {
        if (!o.isRight) {
            this.removeAllClass(o);
            o.$item.addClass(this.conf.itemRight);
            o.isHidden = false;
            o.isActive = false;
            o.isLeft = false;
            o.isRight = true;
        }
    }

    setHidden(o) {
        if (!o.isHidden) {
            this.removeAllClass(o);
            o.$item.addClass(this.conf.itemHidden);
            o.isHidden = true;
            o.isLeft = false;
            o.isRight = false;
            o.isActive = false;
        }
    }

    removeAllClass(o) {
        o.$item.removeClass(this.conf.itemActive + ' ' +
            this.conf.itemLeft + ' ' +
            this.conf.itemRight + ' ' +
            this.conf.itemHidden);
    }

    setPagerActive(o) {
        o.$pagers.addClass(this.conf.pagerActive);
    }

    unsetPagerActive(o) {
        o.$pagers.removeClass(this.conf.pagerActive);
    }

    // DIRECTION
    getDirection(key) {
        if (this.currentItem == 0 && key == this.aItems.length - 1) {
            return 'left'; } else if (this.currentItem == this.aItems.length - 1 && key == 0) {
            return 'right'; } else if (key < this.currentItem) {
            return 'left'; } else {
            return 'right'; }
    }

    // KEY
    getPreviousKey() {
        if (this.currentItem - 1 >= 0) {
            return this.currentItem - 1;
        } else {
            return this.aItems.length - 1;
        }
    }

    getNextKey() {
        if (this.currentItem + 1 < this.aItems.length) {
            return this.currentItem + 1;
        } else {
            return 0;
        }
    }

    // EVENTS

    e_clickNext(e) {
        e.preventDefault();
        this.setPreviousItem();
    }

    e_clickPrev(e) {
        e.preventDefault();
        this.setPreviousItem();
    }

    e_clickPager(e, item) {
        e.preventDefault();
        this.setCustomItem(item.key);
    }

    e_keyPress(e) {

        if (e.keyCode == 37) {
            console.log(this);
            if (!this.isAnimating) {
                this.setPreviousItem();
            }
        } else if (e.keyCode == 39) {
            if (!this.isAnimating) {
                this.setNextItem();
            }
        }
    }
}

export { Quotes as default }
