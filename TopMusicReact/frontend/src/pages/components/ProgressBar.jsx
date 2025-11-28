// eslint-disable-next-line no-unused-vars
import React, { useRef, useEffect, useState } from 'react';
import PropTypes from 'prop-types';
import './css/ProgressBar.css';

const ProgressBar = ({ controlloVideo }) => {
  const progressBarRef = useRef(null); // Riferimento alla barra di progresso
  const nowRef = useRef(null); // Riferimento alla parte "riempita" della barra
  const [currentTime, setCurrentTime] = useState(0); // Stato per il tempo corrente

  // Funzione per convertire i secondi in formato "mm:ss"
  const conversion = (value) => {
    const minute = Math.floor(value / 60);
    const second = Math.floor(value % 60);
    return `${minute < 10 ? '0' + minute : minute}:${second < 10 ? '0' + second : second}`;
  };

  // Gestore del clic sulla barra di progresso
  const handleProgressClick = (event) => {
    const progressBar = progressBarRef.current;
    const mediaElement = controlloVideo.current;

    if (progressBar && mediaElement) {
      const coordStart = progressBar.getBoundingClientRect().left;
      const coordEnd = event.pageX;
      const p = (coordEnd - coordStart) / progressBar.offsetWidth;

      // Imposta la larghezza della barra di progresso
      nowRef.current.style.width = `${p * 100}%`;

      // Aggiorna il tempo corrente del video o dell'audio
      mediaElement.currentTime = p * mediaElement.duration;
    }
  };

  // Aggiorna la barra di progresso ogni secondo
  useEffect(() => {
    const mediaElement = controlloVideo.current;

    const updateProgress = () => {
      if (mediaElement) {
        const progress = (mediaElement.currentTime / mediaElement.duration) * 100;
        nowRef.current.style.width = `${progress}%`;
        setCurrentTime(mediaElement.currentTime);
      }
    };

    // Aggiungi un listener per l'evento "timeupdate" del video o dell'audio
    if (mediaElement) {
      mediaElement.addEventListener('timeupdate', updateProgress);
    }

    // Rimuovi il listener quando il componente viene smontato
    return () => {
      if (mediaElement) {
        mediaElement.removeEventListener('timeupdate', updateProgress);
      }
    };
  }, [controlloVideo]);

  return (
    <div className="progress">
<span className="start" style={{ display: 'none' }}>{conversion(currentTime)}</span>      <div
        className="progress-bar"
        ref={progressBarRef}
        onClick={handleProgressClick}
      >
        <div className="now" ref={nowRef}></div>
      </div>
      <span className="end"style={{ display: 'none' }}>{conversion(controlloVideo.current?.duration || 0)}</span>
    </div>
  );
};

ProgressBar.propTypes = {
  controlloVideo: PropTypes.shape({
    current: PropTypes.instanceOf(HTMLMediaElement),
  }).isRequired,
};

export default ProgressBar;