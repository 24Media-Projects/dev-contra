NodeList.prototype.arrayFrom = function(){

  var _array = [];

  this.forEach( function( node ){
    _array.push( node );
  });

  return _array;
}

NodeList.prototype.getElementIndex = function( $element ){
  return this.arrayFrom().indexOf( $element );
}


Node.prototype.isVisible = function( per ){

per = !per ? 0 : per;

var $element           = this,
    viewTop       = window.scrollY,
    viewBottom    = viewTop + window.innerHeight,
    elementOffsets = $element.getBoundingClientRect(),
    _top          = elementOffsets.top + viewTop,
    _bottom       = _top + $element.offsetHeight,
    partial       = per != 0 ? true : false,
    compareTop    = partial === true ? _bottom - ( $element.offsetHeight * per / 100 ) : _top,
    compareBottom = partial === true ? _top + ( $element.offsetHeight * per / 100 ): _bottom;

    return ( ( compareBottom <= viewBottom ) && ( compareTop >= viewTop ) );
}


Node.prototype.isVisibleHorizontal = function (per, $container, customCheck) {
  per = per || 100;
  customCheck = customCheck || true;
  $container = $container || window;

  var $element = this,
    viewLeft = $container !== window ? $container.scrollLeft : window.scrollX,
    viewRight = viewLeft + $container.offsetWidth,
    elementOffsets = $element.getBoundingClientRect(),
    _left = elementOffsets.left + viewLeft,
    _right = _left + elementOffsets.width,
    partial = per != 0 ? true : false,
    compareLeft = partial === true ? _right - (elementOffsets.width * per / 100) : _left,
    compareRight = partial === true ? _left + (elementOffsets.width * per / 100) : _right;

  return ((_right <= viewRight) && (_left >= viewLeft)) && customCheck;
}




const fetchData = async ( method = 'GET', props ) => {

  let queryVars = new URLSearchParams( props.queryStrings ),
      url = BTW.ajaxUrl + ( method === 'GET' ? '?' + queryVars.toString() : '' ),
      requestArgs = {
        method: method,
        mode: 'same-origin',
        headers: {
          'Content-Type': method === 'GET' ? 'application/json' : 'application/x-www-form-urlencoded',
        },
      };


  if( method === 'POST' ){
    requestArgs.body = queryVars.toString();
  }

  try{

    let response = await fetch( url, requestArgs );

    let data = await response.json();

    return data;

  }catch( error ){

    console.log( error );
    return false;

  }

}


window.btwFetchData = fetchData;


const fetchDataHTML = async ( url ) => {

  let requestArgs = {
      method: 'GET',
      mode: 'same-origin',
      headers: {
        'Content-Type': 'text/html',
      },
    };

  try{

    let response = await fetch( url, requestArgs );

    let data = await response.text();

    let domParser = new DOMParser(),
	      htmlDocument = domParser.parseFromString( data, 'text/html' );

    return htmlDocument;

  }catch( error ){

    console.log( error );
    return false;

  }

}

window.btwFetchDataHTML = fetchDataHTML;



if( !String.prototype.startsWith ){
  Object.defineProperty( String.prototype, 'startsWith', {
      value: function( search, rawPos ){
          var pos = rawPos > 0 ? rawPos|0 : 0;
          return this.substring( pos, pos + search.length ) === search;
      }
  });
}
