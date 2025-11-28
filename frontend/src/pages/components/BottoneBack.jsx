// eslint-disable-next-line no-unused-vars
import React from 'react';
import { useNavigate } from 'react-router-dom'; // Importa useNavigate
import './css/BottoneBack.css';

const BottoneBack = () => {
  const navigate = useNavigate(); // Ottieni la funzione di navigazione

  const handleBackClick = () => {
    navigate('/'); // Reindirizza alla pagina principale
  };

  return (
    <button className="glow-on-hover" type="button" onClick={handleBackClick}>
      <img 
        src="/iconaBottoneBack.svg" 
        alt="Back Arrow" 
      />
    </button>
  );
};

export default BottoneBack;