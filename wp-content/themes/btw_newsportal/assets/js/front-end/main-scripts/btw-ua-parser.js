let parser = new UAParser();

const btwUaParser = {
  parser: parser,
  isMobileRegex: new RegExp( 'android|ios|windows-?(phone|mobile)', 'g' ),
  os: parser.getOS(),
  matchedOsName: () => { return this.os().name.toLowerCase().match( /android|ios|windows\s?(phone|mobile)?|mac os/g ) },
  osName: () => { return this.matchedOsName ? this.matchedOsName.shift().replace( /\s/g, '-' ) : null },
  isMobile: () => { return this.isMobileRegex.test( this.osName ) || window.innerWidth < 1100 },
}

window.btwUaParser = btwUaParser;
