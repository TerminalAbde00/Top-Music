// eslint-disable-next-line no-unused-vars
import React, { useState, useEffect, useRef } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import Axios from 'axios';
import VideoPlayer from './components/VideoPlayer';
import CardPlayer from './components/CardPlayer';
import ProgressBar from './components/ProgressBar';
import BottoneBack from './components/BottoneBack';

const PlayerPage = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const controlloVideo = useRef(null);

  const [post, setPost] = useState({nomeCanzone: '', autore: '', img: '', vid: '', id: ''});

  const [isMouseMoving, setIsMouseMoving] = useState(false);
  const [isMP3, setIsMP3] = useState(false);
  const [isPaused, setIsPaused] = useState(false);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await Axios.get(`http://localhost:3002/api/getFromId/${id}`);
        const data = response.data[0];

        if (data) {
          setPost({
            nomeCanzone: data.NomeCanzone || 'Nome non disponibile',
            autore: data.Autore || 'Autore non disponibile',
            img: data.IMG || 'default.jpg',
            vid: data.VID || '',
            id: data.Id || ''
          });

          if (data.VID) {
            const fileExtension = data.VID.substring(data.VID.lastIndexOf('.') + 1).toLowerCase();
            setIsMP3(fileExtension === 'mp3');
          }
        } else {
          navigate('/'); // Reindirizza alla pagina principale se non ci sono dati
        }
      } catch (error) {
        console.error('Errore durante il recupero dei dati:', error);
        navigate('/');
      }
    };

    fetchData();
  }, [id, navigate]);

  useEffect(() => {
    let mouseMoveTimeout;

    const handleMouseMove = () => {
      setIsMouseMoving(true);
      clearTimeout(mouseMoveTimeout);
      mouseMoveTimeout = setTimeout(() => setIsMouseMoving(false), 4000);
    };

    window.addEventListener('mousemove', handleMouseMove);

    return () => {
      window.removeEventListener('mousemove', handleMouseMove);
      clearTimeout(mouseMoveTimeout);
    };
  }, []);

  const handlePlayPause = (isPaused) => {
    setIsPaused(isPaused);
  };

  

  return (
    <div>
      <div className='audioPlayer' style={{ height: '100%', width: '100%' }}>
      <VideoPlayer 
        controlloVideo={controlloVideo}
        VID={post.vid}
        isMouseMoving={isMouseMoving}
        onPlayPause={handlePlayPause}
        isPaused={isPaused} // Passa lo stato di pausa
      />
      </div>
      {(isMouseMoving || isMP3 || isPaused) && (
        <>
          <CardPlayer controlloVideo={controlloVideo} nomeCanzone={post.nomeCanzone} autore={post.autore} img={post.img} />
          <ProgressBar controlloVideo={controlloVideo} />
          <BottoneBack />
        </>
      )}
    </div>
  );
};

export default PlayerPage;