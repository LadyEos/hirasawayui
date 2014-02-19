$(function() {
		
	$("#jquery_jplayer_1").jPlayer({
        ready: function () {
          $(this).jPlayer("setMedia", {
            mp3: $("#filePath").val()
            //oga: "http://www.jplayer.org/audio/ogg/Miaow-07-Bubble.ogg"
          });
        },
        swfPath: "/js/jQuery.jPlayer.2.5.0",
        supplied: "mp3"
      });
	
	/*$( "#audioPlayerContainer" ).jPlayer({
			 swfPath: '.public/js/jQuery.jPlayer.2.5.0',
			 solution: 'html, flash',
			 supplied: 'mp3',
			 preload: 'metadata',
			 volume: 0.8,
			 muted: false,
			 backgroundColor: '#000000',
			 cssSelectorAncestor: '#jp_container_1',
			 cssSelector: {
			  videoPlay: '.jp-video-play',
			  play: '.jp-play',
			  pause: '.jp-pause',
			  stop: '.jp-stop',
			  seekBar: '.jp-seek-bar',
			  playBar: '.jp-play-bar',
			  mute: '.jp-mute',
			  unmute: '.jp-unmute',
			  volumeBar: '.jp-volume-bar',
			  volumeBarValue: '.jp-volume-bar-value',
			  volumeMax: '.jp-volume-max',
			  playbackRateBar: '.jp-playback-rate-bar',
			  playbackRateBarValue: '.jp-playback-rate-bar-value',
			  currentTime: '.jp-current-time',
			  duration: '.jp-duration',
			  fullScreen: '.jp-full-screen',
			  restoreScreen: '.jp-restore-screen',
			  repeat: '.jp-repeat',
			  repeatOff: '.jp-repeat-off',
			  gui: '.jp-gui',
			  noSolution: '.jp-no-solution'
			 },
			 errorAlerts: false,
			 warningAlerts: false
			});*/
});