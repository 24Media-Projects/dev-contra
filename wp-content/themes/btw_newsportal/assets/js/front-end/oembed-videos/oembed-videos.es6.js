"use strict";

/*
  Extends abstract class AbstactOembedVideos
  Extended methods: playVideo, stopVideo
  Parent class methods playVideo, stopVideo contain functionality to play and stop video,
  based on click events of .play_video_<provider>, .stop_video_<provider>
*/


class OembedVideos extends AbstactOembedVideos{

    constructor( params = {} ){
      super( params );
    };


    playVideo( event ){
      super.playVideo( event );
    }

    stopVideo( event ){
      super.stopVideo( event );
    }

}


const oembedVideos = new OembedVideos();

/*
  Youtube API ready callback
*/
window.onYouTubeIframeAPIReady = function(){
  oembedVideos.providerController( 'youtube', false );
  console.log('onYouTubeIframeAPIReady');
}


window.fbAsyncInit = function() {
  oembedVideos.providerController( 'facebook' );
};
