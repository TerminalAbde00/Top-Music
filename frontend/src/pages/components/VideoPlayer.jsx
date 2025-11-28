// eslint-disable-next-line no-unused-vars
import React, { useRef, useEffect } from 'react';
import PropTypes from 'prop-types';
import './css/VideoPlayer.css';

const VideoPlayer = ({ controlloVideo, VID, isMouseMoving, onPlayPause, isPaused }) => {
  const videoRef = useRef(null);
  const audioRef = useRef(null);

  const fileExtension = VID.substring(VID.lastIndexOf('.') + 1).toLowerCase();
  const isMP4 = fileExtension === 'mp4';

  // Sincronizza la riproduzione del video e dell'audio
  const syncPlayback = () => {
    if (videoRef.current && audioRef.current) {
      if (videoRef.current.paused) {
        audioRef.current.pause();
      } else {
        audioRef.current.play();
      }
    }
  };

  // Aggiungi event listener per sincronizzare la riproduzione
  useEffect(() => {
    const video = videoRef.current;
    const audio = audioRef.current;

    if (!isMP4 && video && audio) {
      video.addEventListener('play', syncPlayback);
      video.addEventListener('pause', syncPlayback);
    }

    return () => {
      if (!isMP4 && video && audio) {
        video.removeEventListener('play', syncPlayback);
        video.removeEventListener('pause', syncPlayback);
      }
    };
  }, [isMP4]);

  // Passa la ref del video o dell'audio al componente genitore
  useEffect(() => {
    if (controlloVideo) {
      controlloVideo.current = isMP4 ? videoRef.current : audioRef.current;
    }
  }, [controlloVideo, isMP4]);

  // Aggiungi event listener per rilevare la pausa e la riproduzione
  useEffect(() => {
    const video = videoRef.current;
    const audio = audioRef.current;

    const handlePlay = () => onPlayPause(false);
    const handlePause = () => onPlayPause(true);

    if (video) {
      video.addEventListener('play', handlePlay);
      video.addEventListener('pause', handlePause);
    }

    if (audio) {
      audio.addEventListener('play', handlePlay);
      audio.addEventListener('pause', handlePause);
    }

    return () => {
      if (video) {
        video.removeEventListener('play', handlePlay);
        video.removeEventListener('pause', handlePause);
      }
      if (audio) {
        audio.removeEventListener('play', handlePlay);
        audio.removeEventListener('pause', handlePause);
      }
    };
  }, [onPlayPause]);

  return (
    <div className='videoPlayer'>
      {isMP4 ? (
        <video
          id='myVideo'
          ref={videoRef}
          autoPlay
          loop
          playsInline
          src={`/VID/${VID}`}
          style={{ filter: isMouseMoving || isPaused ? 'blur(4px)' : 'blur(0)' }}
        ></video>
      ) : (
        <>
          <video
            id='myVideo'
            ref={videoRef}
            autoPlay
            loop
            playsInline
            src='/VID/loop/loop.mp4' // Percorso del video predefinito
            style={{ filter: 'blur(4px)' }} // Blur permanente per MP3
          ></video>
          <audio
            ref={audioRef}
            autoPlay
            loop
            src={`/VID/${VID}`} // Percorso del file audio
          ></audio>
        </>
      )}
    </div>
  );
};

VideoPlayer.propTypes = {
  controlloVideo: PropTypes.shape({
    current: PropTypes.instanceOf(HTMLMediaElement),
  }).isRequired,
  VID: PropTypes.string.isRequired,
  isMouseMoving: PropTypes.bool.isRequired,
  onPlayPause: PropTypes.func.isRequired,
  isPaused: PropTypes.bool.isRequired, // Aggiungi questa prop
};

export default VideoPlayer;