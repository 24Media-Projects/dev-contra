var time = Date.now();

var Truncate = function( elementSelector, props = {} ){
    
    if( !elementSelector ){
        return;
    }

    this.elementSelector = elementSelector;
    this.settings = Object.assign({}, this.getDefaults(), props);

    this.domLoaded = false;
    this.fontLoaded = false;
        
    this._bindEvents();


}

Truncate.prototype.getDefaults = function() {
    return {
        defaultCss: "font-family: serif; display: inline-block; overflow:hidden; visibility: none;",
        fontFamily: 'PFDINSerif-Reg',
        logs: true,
        time: 10,
        timer: 1,
        maxLines: 4,
    }
}


Truncate.prototype.log = function(message) {
      
    if (!this.settings.logs){
        return;
    }

    if( typeof( message ) === 'object' ){
        console.log( message.join( ' ' ) );

    }else{
        console.log( message );
    }


}

/**
 * Window resize
 * 
 */
Truncate.prototype.winResize = function() {
    if (!this.fontLoaded) return;

    this.truncate();
}

/**
 * Wait font to load
 * 
 * @return Promise
 */
Truncate.prototype.waitFontToLoad = function() {

    let div = document.createElement('div'),
        divInitWidth = 0,
        interval = null;

    div.innerHTML = 'font';
    div.style.cssText = this.settings.defaultCss;
    document.body.appendChild(div);

    divInitWidth = div.offsetWidth;

    div.style.fontFamily = this.settings.fontFamily;

    return new Promise((resolve, reject) => {

        interval = setInterval(() => {
           
            if (div.offsetWidth != divInitWidth) {
                clearInterval(interval);
                div.parentNode.removeChild(div);
                resolve();
            }
        }, this.timer);

    });

}

/**
 * Init function
 * Wait font to load and proceed to truncate
 */
Truncate.prototype.init = async function(  ){

    this.$elements = document.querySelectorAll( this.elementSelector );

    if( !this.$elements ){
        return;
    }

    await this.waitFontToLoad();

    this.fontLoaded = true;
   
    this.truncate();

}


Truncate.prototype.update = function(){
    this.$elements = document.querySelectorAll( this.elementSelector );
}



/**
 * Wait document.readyState to be completed
 */
Truncate.prototype.waitDomToLoad = function(){

    // let mainStyle = Array.from( document.styleSheets ).filter( ( stylesheet ) => {
    //     return stylesheet.ownerNode == document.querySelector( '#archive_styles-css' );
    // });

    // if( mainStyle.length ){
    //     console.log(mainStyle[0].ownerNode);
    // }

    if( document.readyState == 'complete' && this.domLoaded === false ){
        console.log( 'Dom Ready', document.readyState, ( Date.now() - time ) / 1000 );
        this.domLoaded = true;
        this.init();
    }
}

/**
 * Add Event listeners
 */
Truncate.prototype._bindEvents = function(){
    
    window.addEventListener( 'resize', this.winResize.bind( this) );
    document.addEventListener( 'DOMContentLoaded', this.waitDomToLoad.bind( this ) );
    document.addEventListener( 'readystatechange', this.waitDomToLoad.bind( this ) );
}

/**
 * Check if text needs truncate
 */
Truncate.prototype.truncate = function() {
    
    /**
     * If font isnt loaded yes, return
     */
    if( this.fontLoaded === false ){
        return;
    }
     
    this.$elements.forEach(($el) => {

        var formatedText = this.formatText($el.innerHTML);

        $el.dataset.defaultText = $el.dataset.defaultText || formatedText;
        $el.innerHTML = $el.dataset.defaultText;

        this.log($el.innerHTML);

        let maxLines = $el.dataset.truncateLines || this.settings.maxLines;

        $el.innerHTML = this.getTextLines($el) > maxLines ?
            this.truncatedText($el) :
            $el.innerHTML;

    });
}

/**
 * Wrap all text elements to html. 
 * 
 * @param {string} text 
 * @returns {string}
 */
Truncate.prototype.formatText = function(text) {

    let html = [];

    while (text.indexOf('<strong>') !== -1) {

        let pos = text.indexOf('<strong>');

        let sub = text.substring(0, pos);

        text = text.substring(pos);

        if (sub) {
            html.push('<span>' + sub + '</span>');
        }

        pos = text.indexOf('</strong>');

        sub = text.substring(0, pos + 9);

        html.push(sub);

        text = text.substring(pos + 9);

    }

    if (text) {
        html.push('<span>' + text + '</span>');
    }

    this.log(html);

    return html.join('');

}

/**
 * Count the lines of the element html
 * 
 * @param {Node} $el 
 * @returns {bool}
 */
Truncate.prototype.getTextLines = function($el) {
    var css = window.getComputedStyle($el),
        offsetHeight = Math.floor($el.getBoundingClientRect().height),
        lineHeight = Math.floor(Number(css.getPropertyValue('line-height').replace('px', '')));

        this.log( [offsetHeight, lineHeight, $el.innerHTML] );
    return Math.floor(offsetHeight / lineHeight);
}


/**
 * Truncate functionality
 * 
 * @param {Node} $el
 * @returns {String}
 */
Truncate.prototype.truncatedText = function($el) {

    let maxLines = $el.dataset.truncateLines || this.settings.maxLines,
        $clone = $el.cloneNode(true),
        final = [],
        $children;

    $el.parentNode.appendChild($clone);
    //Put child nodes on array
    $children = [].concat([], $clone.childNodes.arrayFrom());

    $clone.innerHTML = '';
    $clone.style.cssText = 'display: block !important; visibility: hidden !important;';

    for (let $child of $children) {

        let tagName = $child.tagName.toLowerCase(),
            text = $child.textContent,
            textArr = [];

        let $c = document.createElement(tagName);
        $clone.appendChild($c);

        // split textContent with space ( words ) and check the height of the $clone with the max lines
        for (var index = 0; index < text.split(' ').length; index++) {

            var word = text.split(' ')[index];

            textArr.push(word);
            $c.textContent = textArr.join(' ');

            this.log( [ $clone.innerHTML, this.getTextLines($clone) , maxLines ] );

            // If word doesnt fit, remove it
            if (this.getTextLines($clone) > maxLines) {

                textArr.pop();
                $c.textContent = textArr.join(' ') + '...';

                // if the three dots dont fit, remove the last word
                if (this.getTextLines($clone) > maxLines) {
                    textArr.pop();
                }

                $c.textContent = textArr.join(' ') + '...';
                final = $clone.innerHTML;
                $clone.remove();
                return final;
            }

        }

    }

    final = $clone.innerHTML;
    $clone.remove();
    return final;

}

window.Truncate = Truncate;
    
       


        window.onload = function(){
          
            console.log('loaded', ( Date.now() - time) / 1000 );


        }

