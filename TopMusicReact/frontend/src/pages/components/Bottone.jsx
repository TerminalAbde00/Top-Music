import React, { useEffect } from 'react';
import PropTypes from 'prop-types';
import './css/Bottone.css';

function Bottone({ controlloVideo }) {
  const [isPlaying, setIsPlaying] = React.useState(true);

  const togglePlayPause = () => {
    if (controlloVideo.current) {
      if (controlloVideo.current.paused) {
        controlloVideo.current.play();
      } else {
        controlloVideo.current.pause();
      }
    }
  };

  useEffect(() => {
    const mediaElement = controlloVideo.current;

    const handlePlay = () => setIsPlaying(true);
    const handlePause = () => setIsPlaying(false);

    if (mediaElement) {
      mediaElement.addEventListener('play', handlePlay);
      mediaElement.addEventListener('pause', handlePause);
    }

    return () => {
      if (mediaElement) {
        mediaElement.removeEventListener('play', handlePlay);
        mediaElement.removeEventListener('pause', handlePause);
      }
    };
  }, [controlloVideo]);

  return (
    <div className='buttonControl'>
      <button onClick={togglePlayPause}>
        {isPlaying ? 'Pause' : 'Play'}
      </button>
    </div>
  );
}

Bottone.propTypes = {
  controlloVideo: PropTypes.shape({
    current: PropTypes.instanceOf(HTMLMediaElement), // Cambiato da HTMLVideoElement a HTMLMediaElement
  }).isRequired,
};

export default Bottone;