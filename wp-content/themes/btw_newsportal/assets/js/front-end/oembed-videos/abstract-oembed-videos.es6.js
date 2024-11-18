"use strict";
/*
  Base ( Abstact ) Class for oembed Videos
  Contains setup video players, play and stop functionalities
  Extend the playVideo, stopVideo methods to add more functionalities.
  Currently supports: youtube, vimeo, html5
*/

class AbstactOembedVideos{

    /*
      Default youtube video params settings
    */
    ytPlayerVars = {
      height: '100%',
      width: '100%',
      playerVars:{
        'fs': 0,
        'iv_load_policy': 3,
        'cc_load_policy': 3,
        'modestbranding': 1,
        'rel': 0,
        'showinfo': 0,
        'controls':1
      },
    };

    /*
      playVideoBtn: Selector
      stopVideoBtn: Selector
      facebookVideoIdPrefix: Prefix value of facebook video ref
    */
    _defaults = {
      playVideoBtnPrefix: 'play_video_',
      stopVideoBtnPrefix: 'stop_video_',
      fbVideoIdPrefix: 'fb_embed_video_',
      // lazyLoad: OEV.lazy_load,
    }



    constructor( params = {} ){

      this.params = Object.assign( params, this._defaults );

      this.oembedVideosParams = typeof oembedVideosParams !== 'undefined' ? oembedVideosParams : {};

      this.videoPlayers = {
        youtube: {},
        vimeo: {},
        html5: {},
        glomex: {},
        facebook: {},
      };

      if( this.oembedVideosParams.hasOwnProperty( 'vimeo' ) ){
        this.providerController( 'vimeo', false );
      }

      if( this.oembedVideosParams.hasOwnProperty( 'html5' ) ){
        this.providerController( 'html5' );
      }

      if( this.oembedVideosParams.hasOwnProperty( 'glomex' ) ){
        this.providerController( 'glomex' );
      }

    }

    setYouTubePlayers( yt ){
      const YT = window.YT || { Player: function(){} };
      this.videoPlayers.youtube[ yt.video_ref ] = new YT.Player( yt.video_ref, this.ytPlayerVars );
    }

    setVimeoPlayers( vm ){
      if( !vm ){
        return;
      }
      const Vimeo = window.Vimeo || { Player: function(){} };
      this.videoPlayers.vimeo[ vm.video_ref ] = new Vimeo.Player( vm.video_ref, { id: vm.video_id } );
    }

    setHtml5Players( vd ){
      this.videoPlayers.html5[ vd.video_ref ] = document.getElementById( vd.video_ref );
    }

    setGlomexPlayers(){
      this.oembedVideosParams.glomex.forEach( ( vd ) => {
        this.videoPlayers.glomex[ vd.video_ref ] = document.getElementById( vd.video_ref );
      });

    }

    setFacebookPlayers(){

      const FB = window.FB;
      let self = this;

      if( !FB ) return false;

      FB.Event.subscribe( 'xfbml.ready', function( msg ){
        if( msg.type !== 'video' ) return false;

        let videoRef = msg.id.replace( self.params.fbVideoIdPrefix, '' );

        self.videoPlayers.facebook[ videoRef ] = msg.instance;

      });

    }



    setPlayers( provider, params = {} ){

      if( provider == 'youtube' ){
        this.setYouTubePlayers( params );

      }else if( provider == 'vimeo' ){
        this.setVimeoPlayers( params );

      }else if( provider == 'html5' ){
        this.setHtml5Players();
      }
      else if( provider == 'glomex' ){
        this.setGlomexPlayers();
      }
      else if( provider == 'facebook' ){
        this.setFacebookPlayers();
      }


    }

    /*
      Main Provider Controller.
      Set up video players
      Bind click events for play, stop video.
    */
    providerController( provider, setPlayers = true ){

      if( setPlayers ){
        this.setPlayers( provider );
      }

      document.querySelectorAll( '.' + this.params.playVideoBtnPrefix + provider ).forEach( ( element ) => {

        element.addEventListener('click', this.playVideo.bind(this) );

      });

      document.querySelectorAll( '.' + this.params.stopVideoBtnPrefix + provider ).forEach( ( element ) => {

        element.addEventListener('click', this.stopVideo.bind(this) );

      });

    }

    /*
      player: player object containing props and methods from provider
      action: play / stop
      callback: function name, is set
    */

    onVideoFrameLoad( player, action, callback ){


      if( action == 'play' ){
        // Youtube
        if( typeof player.playVideo === 'function' ){
          player.playVideo();

        // Vimeo, html5, glomex, facebook
        }else{
          player.play();
        }

      }else{

        // Youtube
        if( typeof player.stopVideo === 'function' ){
          player.stopVideo();

        // Vimeo, html5, glomex, facebook
        }else{
          player.pause();

          // Vimeo
          if( typeof player.setCurrentTime === 'function' ){
            player.setCurrentTime(0);

          // Facebook
          }else if( typeof player.seek === 'function' ){
            player.seek( 0 );

          // Html5
          }else{
            player.currentTime = 0;
          }
        }

      }

      if( typeof callback === 'function' ){
        callback();
      }

    }


    playVideo( event ){

      event.preventDefault();

      let dataset = event.currentTarget.dataset,
          provider = dataset.provider,
          videoRef = dataset.videoRef,
          videoID = dataset.videoId,
          videoFrame,
          videoFrameLoaded,
          player;


      if( [ 'facebook', 'glomex' ].indexOf( provider ) !== -1 ){

        if( this.videoPlayers[ provider ][ videoRef ] === undefined ) return false;

        player = this.videoPlayers[ provider ][ videoRef ];
        this.onVideoFrameLoad( player, 'play' );
        return true;

      }

      videoFrame = provider != 'html5' ? document.getElementById( videoRef ) : document.getElementById( videoRef ).querySelector( 'source' );
      videoFrameLoaded = provider == 'html5' ? true : ( videoFrame.dataset.loaded || false );

      if( !videoFrame.src ){
        videoFrame.src = videoFrame.dataset.src;
        videoFrame.removeAttribute( 'data-src' );
      }

      if( this.videoPlayers[ provider ][ videoRef ] === undefined ){
        this.setPlayers( provider, { video_id: videoID, video_ref: videoRef } );
      }

      player = this.videoPlayers[ provider ][ videoRef ];

      if( videoFrameLoaded ){
        this.onVideoFrameLoad( player, 'play' );

      }else{
        videoFrame.onload = this.onVideoFrameLoad.bind( this, player, 'play', () => {
          videoFrame.dataset.loaded = true;
          // console.log('loaded');
        });

      }


    }

    stopVideo( event ){

      event.preventDefault();

      let dataset = event.currentTarget.dataset,
          provider = dataset.provider,
          videoRef = dataset.videoRef,
          videoFrame,
          videoFrameLoaded,
          player;

      if( this.videoPlayers[ provider ][ videoRef ] === undefined ) return false;

      player = this.videoPlayers[ provider ][ videoRef ];

      if( [ 'facebook', 'glomex' ].indexOf( provider ) !== -1 ){
        this.onVideoFrameLoad( player, 'stop' );
        return true;
      }


      videoFrame = provider != 'html5' ? document.getElementById( videoRef ) : document.getElementById( videoRef ).querySelector( 'source' ),
      videoFrameLoaded = provider == 'html5' ? true : ( videoFrame.dataset.loaded || false );

      if( videoFrameLoaded ){
        this.onVideoFrameLoad( player, 'stop' );

      }else{
        videoFrame.onload = function(){
          videoFrame.dataset.loaded = true;
          // console.log('FALLBACK');
        }
      }

    }



}

window.AbstactOembedVideos = AbstactOembedVideos;
